<?php

namespace App\Services;

use App\Models\FlightInquiry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FlightInquiryService
{
    /**
     * Create a new flight inquiry with auto-generated reference.
     *
     * @param  array<string, mixed>  $data
     */
    public function createInquiry(array $data): FlightInquiry
    {
        if (empty($data['reference_no'])) {
            $data['reference_no'] = FlightInquiry::generateReferenceNo();
        }

        // Clean up multi_city segments if applicable
        if (($data['trip_type'] ?? '') !== 'multi_city') {
            $data['multi_city_segments'] = null;
        }

        if (($data['trip_type'] ?? '') === 'one_way') {
            $data['return_date'] = null;
        }

        return FlightInquiry::query()->create($data);
    }

    /**
     * Get paginated inquiries with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = FlightInquiry::query();

        if (! empty($filters['status']) && $filters['status'] !== 'All') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['trip_type'])) {
            $query->where('trip_type', $filters['trip_type']);
        }

        if (! empty($filters['cabin_class'])) {
            $query->where('cabin_class', $filters['cabin_class']);
        }

        if (! empty($filters['search'])) {
            $search = '%'.trim((string) $filters['search']).'%';
            $query->where(function ($q) use ($search): void {
                $q->where('reference_no', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhere('customer_phone', 'like', $search)
                    ->orWhere('customer_email', 'like', $search)
                    ->orWhere('origin', 'like', $search)
                    ->orWhere('destination', 'like', $search);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find inquiry by ID.
     */
    public function findById(int $id): FlightInquiry
    {
        return FlightInquiry::query()->findOrFail($id);
    }

    /**
     * Find inquiry by reference number.
     */
    public function findByReference(string $referenceNo): ?FlightInquiry
    {
        return FlightInquiry::query()->where('reference_no', $referenceNo)->first();
    }

    /**
     * Update inquiry status.
     */
    public function updateStatus(FlightInquiry|int $inquiry, string $status): FlightInquiry
    {
        $model = $inquiry instanceof FlightInquiry ? $inquiry : $this->findById($inquiry);
        $model->update(['status' => $status]);

        return $model->fresh();
    }

    /**
     * Update quoted PKR price and status.
     */
    public function updateQuote(FlightInquiry|int $inquiry, float $pricePkr, ?string $status = 'Quoted'): FlightInquiry
    {
        $model = $inquiry instanceof FlightInquiry ? $inquiry : $this->findById($inquiry);

        $payload = ['quoted_price_pkr' => $pricePkr];
        if ($status) {
            $payload['status'] = $status;
        }

        $model->update($payload);

        return $model->fresh();
    }

    /**
     * Update entire inquiry details.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateInquiry(FlightInquiry|int $inquiry, array $data): FlightInquiry
    {
        $model = $inquiry instanceof FlightInquiry ? $inquiry : $this->findById($inquiry);
        $model->update($data);

        return $model->fresh();
    }

    /**
     * Delete an inquiry.
     */
    public function delete(FlightInquiry|int $inquiry): bool
    {
        $model = $inquiry instanceof FlightInquiry ? $inquiry : $this->findById($inquiry);

        return (bool) $model->delete();
    }

    /**
     * Get counts broken down by status for UI badge tabs.
     *
     * @return array<string, int>
     */
    public function getStatusCounts(): array
    {
        return [
            'All' => FlightInquiry::query()->count(),
            'Pending' => FlightInquiry::query()->where('status', 'Pending')->count(),
            'Contacted' => FlightInquiry::query()->where('status', 'Contacted')->count(),
            'Quoted' => FlightInquiry::query()->where('status', 'Quoted')->count(),
            'Booked' => FlightInquiry::query()->where('status', 'Booked')->count(),
        ];
    }
}
