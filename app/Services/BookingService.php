<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingService
{
    /**
     * Retrieve paginated bookings filtered by parameters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listBookings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(Booking::query(), $filters)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Apply filter conditions to the bookings query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $sub) use ($search): void {
                $sub->where('booking_reference', 'like', "%{$search}%")
                    ->orWhere('pnr', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('ticket_ref', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('airline', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['supplier'])) {
            $query->where('supplier', 'like', '%'.$filters['supplier'].'%');
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('travel_date', '>=', Carbon::parse($filters['date_from'])->toDateString());
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('travel_date', '<=', Carbon::parse($filters['date_to'])->toDateString());
        }

        return $query;
    }

    /**
     * Create a new booking record.
     *
     * @param  array<string, mixed>  $data
     */
    public function createBooking(array $data): Booking
    {
        $fare = (float) ($data['fare_amount'] ?? 0);
        $tax = (float) ($data['tax_amount'] ?? 0);
        $anc = (float) ($data['ancillaries_amount'] ?? 0);
        $markup = (float) ($data['markup_amount'] ?? 0);

        if (! isset($data['total_amount']) || (float) $data['total_amount'] <= 0) {
            $data['total_amount'] = $fare + $tax + $anc + $markup;
        }

        return Booking::create($data);
    }

    /**
     * Update an existing booking.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateBooking(Booking $booking, array $data): Booking
    {
        if (isset($data['fare_amount']) || isset($data['tax_amount']) || isset($data['ancillaries_amount']) || isset($data['markup_amount'])) {
            $fare = (float) ($data['fare_amount'] ?? $booking->fare_amount);
            $tax = (float) ($data['tax_amount'] ?? $booking->tax_amount);
            $anc = (float) ($data['ancillaries_amount'] ?? $booking->ancillaries_amount);
            $markup = (float) ($data['markup_amount'] ?? $booking->markup_amount);
            if (! isset($data['total_amount'])) {
                $data['total_amount'] = $fare + $tax + $anc + $markup;
            }
        }

        $booking->update($data);

        return $booking->fresh();
    }

    /**
     * Update status and optional remarks.
     */
    public function updateStatus(Booking $booking, string $status, ?string $remarks = null): Booking
    {
        $updateData = ['status' => $status];
        if ($remarks !== null) {
            $updateData['remarks'] = $remarks;
        }

        $booking->update($updateData);

        return $booking->fresh();
    }

    /**
     * Issue ticket with electronic ticket reference.
     */
    public function issueTicket(Booking $booking, string $ticketRef): Booking
    {
        $booking->update([
            'ticket_ref' => $ticketRef,
            'status' => 'Ticketed',
        ]);

        return $booking->fresh();
    }

    /**
     * Delete a booking.
     */
    public function deleteBooking(Booking $booking): bool
    {
        return (bool) $booking->delete();
    }

    /**
     * Compute summary statistics for KPI dashboards.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return [
            'total_bookings' => Booking::count(),
            'flight_count' => Booking::flight()->count(),
            'hotel_count' => Booking::hotel()->count(),
            'group_count' => Booking::group()->count(),
            'confirmed_count' => Booking::where('status', 'Confirmed')->count(),
            'ticketed_count' => Booking::where('status', 'Ticketed')->count(),
            'pending_count' => Booking::where('status', 'Pending')->count(),
            'total_revenue_pkr' => (float) Booking::whereIn('status', ['Confirmed', 'Ticketed'])->sum('total_amount'),
        ];
    }

    /**
     * Export filtered bookings as a downloadable CSV stream.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $fileName = 'bookings-export-'.now()->format('Y-m-d-His').'.csv';

        $query = $this->applyFilters(Booking::query(), $filters)->latest();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($handle, [
                'Booking Reference',
                'PNR',
                'Customer Name',
                'Customer Email',
                'Customer Phone',
                'Type',
                'Service',
                'Route / Destination',
                'Airline / Flight',
                'Travel Date',
                'Passengers',
                'Total Amount (PKR)',
                'Status',
                'Payment Status',
                'Supplier',
                'Ticket Ref',
                'Booking Date',
            ]);

            $query->chunk(100, function ($bookings) use ($handle): void {
                foreach ($bookings as $booking) {
                    fputcsv($handle, [
                        $booking->booking_reference,
                        $booking->pnr ?? 'N/A',
                        $booking->customer_name,
                        $booking->customer_email,
                        $booking->customer_phone,
                        ucfirst($booking->type),
                        $booking->service,
                        $booking->route ?: $booking->destination,
                        trim(($booking->airline ?? '').' '.($booking->flight_number ?? '')),
                        $booking->travel_date ? $booking->travel_date->format('Y-m-d') : 'N/A',
                        $booking->passengers_count,
                        number_format((float) $booking->total_amount, 2, '.', ''),
                        $booking->status,
                        $booking->payment_status,
                        $booking->supplier ?? 'N/A',
                        $booking->ticket_ref ?? 'Pending',
                        $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
