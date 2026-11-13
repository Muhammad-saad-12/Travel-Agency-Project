<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\OfflineRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class OfflineTicketingService
{
    /**
     * Retrieve paginated offline requests with applied filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listRequests(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(OfflineRequest::query(), $filters)
            ->orderByDesc('is_urgent')
            ->latest('requested_at')
            ->paginate($perPage);
    }

    /**
     * Apply filter criteria to offline requests query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $sub) use ($search): void {
                $sub->where('booking_ref', 'like', "%{$search}%")
                    ->orWhere('pnr', 'like', "%{$search}%")
                    ->orWhere('passenger_name', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhere('route', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['request_type']) && $filters['request_type'] !== 'all') {
            $query->where('request_type', $filters['request_type']);
        }

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['airline']) && $filters['airline'] !== 'all') {
            $query->where('airline', $filters['airline']);
        }

        if (isset($filters['is_urgent']) && $filters['is_urgent'] !== '' && $filters['is_urgent'] !== null) {
            $query->where('is_urgent', (bool) $filters['is_urgent']);
        }

        return $query;
    }

    /**
     * Create a new offline queue request.
     *
     * @param  array<string, mixed>  $data
     */
    public function createRequest(array $data): OfflineRequest
    {
        if (empty($data['travel_date'])) {
            $data['travel_date'] = Carbon::now()->addDays(7)->toDateString();
        }

        if (empty($data['requested_at'])) {
            $data['requested_at'] = Carbon::now();
        }

        return OfflineRequest::create($data);
    }

    /**
     * Update an offline queue request.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateRequest(OfflineRequest $request, array $data): OfflineRequest
    {
        $request->update($data);

        return $request->fresh();
    }

    /**
     * Toggle urgency state on an offline request.
     */
    public function toggleUrgent(OfflineRequest $request): OfflineRequest
    {
        $request->update([
            'is_urgent' => ! $request->is_urgent,
        ]);

        return $request->fresh();
    }

    /**
     * Assign user to request.
     */
    public function assignUser(OfflineRequest $request, string $userName): OfflineRequest
    {
        $request->update([
            'assigned_user' => $userName,
            'status' => $request->status === 'Applied' ? 'Processing' : $request->status,
        ]);

        return $request->fresh();
    }

    /**
     * Complete a ticketing request with an issued e-ticket number.
     */
    public function processTicketing(OfflineRequest $request, string $ticketNumber, ?string $assignedUser = null, ?string $remarks = null): OfflineRequest
    {
        $updateData = [
            'ticket_number' => $ticketNumber,
            'status' => 'Completed',
        ];

        if ($assignedUser !== null) {
            $updateData['assigned_user'] = $assignedUser;
        }

        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        $request->update($updateData);

        // Synchronize corresponding booking if matched by PNR or booking reference
        if (! empty($request->pnr) || ! empty($request->booking_ref)) {
            $booking = Booking::query()
                ->when($request->pnr, fn ($q) => $q->where('pnr', $request->pnr))
                ->orWhere('booking_reference', $request->booking_ref)
                ->first();

            if ($booking) {
                $booking->update([
                    'ticket_ref' => $ticketNumber,
                    'status' => 'Ticketed',
                ]);
            }
        }

        return $request->fresh();
    }

    /**
     * Process a refund request with penalty and refund PKR amounts.
     */
    public function processRefund(OfflineRequest $request, float $refundAmount, float $penaltyAmount = 0, ?string $remarks = null): OfflineRequest
    {
        $updateData = [
            'refund_amount' => $refundAmount,
            'penalty_amount' => $penaltyAmount,
            'status' => 'Completed',
        ];

        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        $request->update($updateData);

        // Update corresponding booking payment status to Refunded if exists
        if (! empty($request->pnr) || ! empty($request->booking_ref)) {
            $booking = Booking::query()
                ->when($request->pnr, fn ($q) => $q->where('pnr', $request->pnr))
                ->orWhere('booking_reference', $request->booking_ref)
                ->first();

            if ($booking) {
                $booking->update([
                    'payment_status' => 'Refunded',
                    'status' => 'Cancelled',
                ]);
            }
        }

        return $request->fresh();
    }

    /**
     * Update request status (e.g. Processing, Rejected).
     */
    public function updateStatus(OfflineRequest $request, string $status, ?string $remarks = null): OfflineRequest
    {
        $updateData = ['status' => $status];
        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        $request->update($updateData);

        return $request->fresh();
    }

    /**
     * Get aggregate statistics for the offline operations queue.
     *
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        return [
            'total_requests' => OfflineRequest::count(),
            'urgent_count' => OfflineRequest::urgent()->count(),
            'ticketing_pending' => OfflineRequest::ticketing()->whereIn('status', ['Applied', 'Processing'])->count(),
            'refund_pending' => OfflineRequest::refunds()->whereIn('status', ['Applied', 'Processing'])->count(),
            'void_pending' => OfflineRequest::voids()->whereIn('status', ['Applied', 'Processing'])->count(),
            'schedule_pending' => OfflineRequest::scheduleChanges()->whereIn('status', ['Applied', 'Processing'])->count(),
            'completed_count' => OfflineRequest::where('status', 'Completed')->count(),
        ];
    }

    /**
     * Retrieve list of unique airlines present in offline requests.
     *
     * @return list<string>
     */
    public function getAirlinesList(): array
    {
        return OfflineRequest::query()
            ->select('airline')
            ->distinct()
            ->whereNotNull('airline')
            ->orderBy('airline')
            ->pluck('airline')
            ->all();
    }
}
