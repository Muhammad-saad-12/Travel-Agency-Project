<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DestinationService
{
    /**
     * Get paginated destinations with optional filtering and search.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Destination::query()->withCount(['hotels', 'tourPackages']);

        if (! empty($filters['search'])) {
            $search = '%'.trim((string) $filters['search']).'%';
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', $search)
                    ->orWhere('city', 'like', $search)
                    ->orWhere('country', 'like', $search)
                    ->orWhere('region', 'like', $search);
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all active destinations for select dropdowns.
     *
     * @return Collection<int, Destination>
     */
    public function getAllActive(): Collection
    {
        return Destination::query()->active()->orderBy('name')->get();
    }

    /**
     * Find a destination by ID.
     */
    public function findById(int $id): Destination
    {
        return Destination::query()->findOrFail($id);
    }

    /**
     * Create a new destination.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Destination
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $count = 1;
        while (Destination::query()->where('slug', $data['slug'])->exists()) {
            $data['slug'] = "{$originalSlug}-{$count}";
            $count++;
        }

        return Destination::query()->create($data);
    }

    /**
     * Update an existing destination.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Destination|int $destination, array $data): Destination
    {
        $model = $destination instanceof Destination ? $destination : $this->findById($destination);

        if (! empty($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (! empty($data['slug'])) {
            $originalSlug = $data['slug'];
            $count = 1;
            while (Destination::query()->where('slug', $data['slug'])->where('id', '!=', $model->id)->exists()) {
                $data['slug'] = "{$originalSlug}-{$count}";
                $count++;
            }
        }

        $model->update($data);

        return $model->fresh();
    }

    /**
     * Delete a destination.
     */
    public function delete(Destination|int $destination): bool
    {
        $model = $destination instanceof Destination ? $destination : $this->findById($destination);

        return (bool) $model->delete();
    }

    /**
     * Toggle the active status of a destination.
     */
    public function toggleStatus(Destination|int $destination): Destination
    {
        $model = $destination instanceof Destination ? $destination : $this->findById($destination);
        $model->update(['is_active' => ! $model->is_active]);

        return $model->fresh();
    }
}
