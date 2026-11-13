<?php

namespace App\Services;

use App\Models\TourPackage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TourPackageService
{
    /**
     * Get paginated tour packages with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = TourPackage::query()->with('destination');

        if (! empty($filters['search'])) {
            $search = '%'.trim((string) $filters['search']).'%';
            $query->where('title', 'like', $search);
        }

        if (! empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (! empty($filters['difficulty_rating'])) {
            $query->where('difficulty_rating', $filters['difficulty_rating']);
        }

        if (isset($filters['is_umrah']) && $filters['is_umrah'] !== '') {
            $query->where('is_umrah', (bool) $filters['is_umrah']);
        }

        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price_pkr', '<=', $filters['max_price']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find tour package by ID.
     */
    public function findById(int $id): TourPackage
    {
        return TourPackage::query()->with('destination')->findOrFail($id);
    }

    /**
     * Create a new tour package.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): TourPackage
    {
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $originalSlug = $data['slug'];
        $count = 1;
        while (TourPackage::query()->where('slug', $data['slug'])->exists()) {
            $data['slug'] = "{$originalSlug}-{$count}";
            $count++;
        }

        if (isset($data['inclusions']) && is_string($data['inclusions'])) {
            $data['inclusions'] = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $data['inclusions'])))));
        }

        if (isset($data['itinerary']) && is_string($data['itinerary'])) {
            $decoded = json_decode($data['itinerary'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['itinerary'] = $decoded;
            } else {
                $lines = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $data['itinerary'])))));
                $itinerary = [];
                foreach ($lines as $idx => $line) {
                    $itinerary[] = ['day' => $idx + 1, 'title' => $line, 'details' => ''];
                }
                $data['itinerary'] = $itinerary;
            }
        }

        return TourPackage::query()->create($data);
    }

    /**
     * Update an existing tour package.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(TourPackage|int $package, array $data): TourPackage
    {
        $model = $package instanceof TourPackage ? $package : $this->findById($package);

        if (! empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (! empty($data['slug'])) {
            $originalSlug = $data['slug'];
            $count = 1;
            while (TourPackage::query()->where('slug', $data['slug'])->where('id', '!=', $model->id)->exists()) {
                $data['slug'] = "{$originalSlug}-{$count}";
                $count++;
            }
        }

        if (isset($data['inclusions']) && is_string($data['inclusions'])) {
            $data['inclusions'] = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $data['inclusions'])))));
        }

        if (isset($data['itinerary']) && is_string($data['itinerary'])) {
            $decoded = json_decode($data['itinerary'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['itinerary'] = $decoded;
            } else {
                $lines = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $data['itinerary'])))));
                $itinerary = [];
                foreach ($lines as $idx => $line) {
                    $itinerary[] = ['day' => $idx + 1, 'title' => $line, 'details' => ''];
                }
                $data['itinerary'] = $itinerary;
            }
        }

        $model->update($data);

        return $model->fresh(['destination']);
    }

    /**
     * Delete a tour package.
     */
    public function delete(TourPackage|int $package): bool
    {
        $model = $package instanceof TourPackage ? $package : $this->findById($package);

        return (bool) $model->delete();
    }

    /**
     * Toggle featured status of a tour package.
     */
    public function toggleFeatured(TourPackage|int $package): TourPackage
    {
        $model = $package instanceof TourPackage ? $package : $this->findById($package);
        $model->update(['is_featured' => ! $model->is_featured]);

        return $model->fresh();
    }
}
