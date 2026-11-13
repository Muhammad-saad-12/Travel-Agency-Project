<?php

namespace App\Livewire\Admin\Packages;

use App\Services\DestinationService;
use App\Services\TourPackageService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Tour Package Management (Module 11)')]
class PackageManager extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public string $filterDestination = '';

    public string $filterDifficulty = '';

    public string $filterUmrah = '';

    public string $filterFeatured = '';

    // Modal state
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingPackageId = null;

    public ?int $deletingPackageId = null;

    // Form inputs
    public ?int $destination_id = null;

    public string $title = '';

    public string $slug = '';

    public int $duration_days = 7;

    public float|int|string $price_pkr = 125000;

    public string $difficulty_rating = 'Easy';

    public bool $is_umrah = false;

    public string $itinerary_text = '';

    public string $inclusions_text = '';

    public bool $is_featured = false;

    public bool $savedSuccessfully = false;

    /**
     * Validation rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        $uniqueSlug = 'nullable|string|max:255';
        if ($this->editingPackageId) {
            $uniqueSlug .= '|unique:tour_packages,slug,'.$this->editingPackageId;
        } else {
            $uniqueSlug .= '|unique:tour_packages,slug';
        }

        return [
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|string|min:3|max:255',
            'slug' => $uniqueSlug,
            'duration_days' => 'required|integer|min:1|max:365',
            'price_pkr' => 'required|numeric|min:0',
            'difficulty_rating' => 'required|in:Easy,Moderate,Challenging',
            'is_umrah' => 'boolean',
            'itinerary_text' => 'nullable|string',
            'inclusions_text' => 'nullable|string',
            'is_featured' => 'boolean',
        ];
    }

    public function updated(string $propertyName): void
    {
        $this->savedSuccessfully = false;
        $this->validateOnly($propertyName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDestination(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDifficulty(): void
    {
        $this->resetPage();
    }

    public function updatingFilterUmrah(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFeatured(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(DestinationService $destinationService): void
    {
        $this->resetValidation();
        $this->editingPackageId = null;
        $firstDest = $destinationService->getAllActive()->first();
        $this->destination_id = $firstDest?->id;
        $this->title = '';
        $this->slug = '';
        $this->duration_days = 7;
        $this->price_pkr = 125000;
        $this->difficulty_rating = 'Easy';
        $this->is_umrah = false;
        $this->itinerary_text = "Day 1: Arrival & Hotel Check-in\nDay 2: City Sightseeing & Historical Exploration\nDay 3: Scenic Excursion & Cultural Tour";
        $this->inclusions_text = "Return Airline Tickets\nHotel Stay with Breakfast\nPrivate AC Transport\nLicensed Tour Guide";
        $this->is_featured = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id, TourPackageService $service): void
    {
        $this->resetValidation();
        $package = $service->findById($id);

        $this->editingPackageId = $package->id;
        $this->destination_id = $package->destination_id;
        $this->title = $package->title;
        $this->slug = $package->slug;
        $this->duration_days = $package->duration_days;
        $this->price_pkr = (float) $package->price_pkr;
        $this->difficulty_rating = $package->difficulty_rating;
        $this->is_umrah = (bool) $package->is_umrah;

        if (is_array($package->itinerary)) {
            $lines = [];
            foreach ($package->itinerary as $item) {
                if (is_array($item) && isset($item['title'])) {
                    $lines[] = 'Day '.($item['day'] ?? '').': '.$item['title'].($item['details'] ? ' - '.$item['details'] : '');
                } else {
                    $lines[] = is_string($item) ? $item : json_encode($item);
                }
            }
            $this->itinerary_text = implode("\n", $lines);
        } else {
            $this->itinerary_text = (string) $package->itinerary;
        }

        if (is_array($package->inclusions)) {
            $this->inclusions_text = implode("\n", $package->inclusions);
        } else {
            $this->inclusions_text = (string) $package->inclusions;
        }

        $this->is_featured = (bool) $package->is_featured;
        $this->showModal = true;
    }

    public function save(TourPackageService $service): void
    {
        $validated = $this->validate();

        $data = [
            'destination_id' => $validated['destination_id'],
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?: null,
            'duration_days' => $validated['duration_days'],
            'price_pkr' => $validated['price_pkr'],
            'difficulty_rating' => $validated['difficulty_rating'],
            'is_umrah' => $validated['is_umrah'],
            'itinerary' => $validated['itinerary_text'],
            'inclusions' => $validated['inclusions_text'],
            'is_featured' => $validated['is_featured'],
        ];

        if ($this->editingPackageId) {
            $service->update($this->editingPackageId, $data);
            session()->flash('status', 'Tour package updated successfully.');
        } else {
            $service->create($data);
            session()->flash('status', 'Tour package created successfully.');
        }

        $this->savedSuccessfully = true;
        $this->showModal = false;
    }

    public function toggleFeatured(int $id, TourPackageService $service): void
    {
        $service->toggleFeatured($id);
        session()->flash('status', 'Tour package featured status toggled.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingPackageId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(TourPackageService $service): void
    {
        if ($this->deletingPackageId) {
            $service->delete($this->deletingPackageId);
            $this->deletingPackageId = null;
            $this->showDeleteModal = false;
            session()->flash('status', 'Tour package deleted successfully.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
    }

    public function render(TourPackageService $service, DestinationService $destService)
    {
        $packages = $service->list([
            'search' => $this->search,
            'destination_id' => $this->filterDestination,
            'difficulty_rating' => $this->filterDifficulty,
            'is_umrah' => $this->filterUmrah,
            'is_featured' => $this->filterFeatured,
        ], 10);

        return view('livewire.admin.packages.package-manager', [
            'packages' => $packages,
            'destinations' => $destService->getAllActive(),
        ]);
    }
}
