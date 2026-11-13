<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HotelService
{
    /**
     * Get paginated hotels with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Hotel::query()->with('destination');

        if (! empty($filters['search'])) {
            $search = '%'.trim((string) $filters['search']).'%';
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', $search)
                    ->orWhere('location_address', 'like', $search);
            });
        }

        if (! empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (! empty($filters['star_rating'])) {
            $query->where('star_rating', $filters['star_rating']);
        }

        if (isset($filters['is_partner']) && $filters['is_partner'] !== '') {
            $query->where('is_partner', (bool) $filters['is_partner']);
        }

        if (! empty($filters['max_rate'])) {
            $query->where('nightly_rate_pkr', '<=', $filters['max_rate']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find hotel by ID.
     */
    public function findById(int $id): Hotel
    {
        return Hotel::query()->with('destination')->findOrFail($id);
    }

    /**
     * Create a new hotel.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Hotel
    {
        if (isset($data['room_types']) && is_string($data['room_types'])) {
            $data['room_types'] = array_values(array_filter(array_map('trim', explode(',', $data['room_types']))));
        }

        return Hotel::query()->create($data);
    }

    /**
     * Update an existing hotel.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Hotel|int $hotel, array $data): Hotel
    {
        $model = $hotel instanceof Hotel ? $hotel : $this->findById($hotel);

        if (isset($data['room_types']) && is_string($data['room_types'])) {
            $data['room_types'] = array_values(array_filter(array_map('trim', explode(',', $data['room_types']))));
        }

        $model->update($data);

        return $model->fresh(['destination']);
    }

    /**
     * Delete a hotel.
     */
    public function delete(Hotel|int $hotel): bool
    {
        $model = $hotel instanceof Hotel ? $hotel : $this->findById($hotel);

        return (bool) $model->delete();
    }

    /**
     * Toggle partner hotel badge.
     */
    public function togglePartner(Hotel|int $hotel): Hotel
    {
        $model = $hotel instanceof Hotel ? $hotel : $this->findById($hotel);
        $model->update(['is_partner' => ! $model->is_partner]);

        return $model->fresh();
    }
}
