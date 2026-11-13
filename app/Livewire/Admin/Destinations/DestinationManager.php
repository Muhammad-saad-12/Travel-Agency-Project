<?php

namespace App\Livewire\Admin\Destinations;

use App\Services\DestinationService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Destination Management (Module 12)')]
class DestinationManager extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public string $filterType = '';

    public string $filterRegion = '';

    public string $filterActive = '';

    // Modal state
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingDestinationId = null;

    public ?int $deletingDestinationId = null;

    // Form inputs
    public string $name = '';

    public string $slug = '';

    public string $country = 'Pakistan';

    public string $city = '';

    public string $type = 'domestic';

    public string $region = 'Northern Pakistan';

    public string $description = '';

    public bool $is_active = true;

    public bool $savedSuccessfully = false;

    /**
     * Validation rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        $uniqueSlug = 'nullable|string|max:255';
        if ($this->editingDestinationId) {
            $uniqueSlug .= '|unique:destinations,slug,'.$this->editingDestinationId;
        } else {
            $uniqueSlug .= '|unique:destinations,slug';
        }

        return [
            'name' => 'required|string|min:2|max:255',
            'slug' => $uniqueSlug,
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'type' => 'required|in:domestic,international',
            'region' => 'required|string|max:100',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Real-time validation hook.
     */
    public function updated(string $propertyName): void
    {
        $this->savedSuccessfully = false;
        $this->validateOnly($propertyName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRegion(): void
    {
        $this->resetPage();
    }

    public function updatingFilterActive(): void
    {
        $this->resetPage();
    }

    /**
     * Open create modal with clean fields.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->editingDestinationId = null;
        $this->name = '';
        $this->slug = '';
        $this->country = 'Pakistan';
        $this->city = '';
        $this->type = 'domestic';
        $this->region = 'Northern Pakistan';
        $this->description = '';
        $this->is_active = true;
        $this->showModal = true;
    }

    /**
     * Open edit modal with existing destination values.
     */
    public function openEditModal(int $id, DestinationService $service): void
    {
        $this->resetValidation();
        $destination = $service->findById($id);

        $this->editingDestinationId = $destination->id;
        $this->name = $destination->name;
        $this->slug = $destination->slug;
        $this->country = $destination->country;
        $this->city = $destination->city;
        $this->type = $destination->type;
        $this->region = $destination->region;
        $this->description = $destination->description ?? '';
        $this->is_active = (bool) $destination->is_active;

        $this->showModal = true;
    }

    /**
     * Save destination (create or update).
     */
    public function save(DestinationService $service): void
    {
        $validated = $this->validate();

        if ($this->editingDestinationId) {
            $service->update($this->editingDestinationId, $validated);
            session()->flash('status', 'Destination updated successfully.');
        } else {
            $service->create($validated);
            session()->flash('status', 'Destination created successfully.');
        }

        $this->savedSuccessfully = true;
        $this->showModal = false;
    }

    /**
     * Toggle destination active status.
     */
    public function toggleStatus(int $id, DestinationService $service): void
    {
        $service->toggleStatus($id);
        session()->flash('status', 'Destination status updated.');
    }

    /**
     * Prompt delete confirmation modal.
     */
    public function confirmDelete(int $id): void
    {
        $this->deletingDestinationId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Execute deletion.
     */
    public function delete(DestinationService $service): void
    {
        if ($this->deletingDestinationId) {
            $service->delete($this->deletingDestinationId);
            $this->deletingDestinationId = null;
            $this->showDeleteModal = false;
            session()->flash('status', 'Destination deleted successfully.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
    }

    public function render(DestinationService $service)
    {
        $destinations = $service->list([
            'search' => $this->search,
            'type' => $this->filterType,
            'region' => $this->filterRegion,
            'is_active' => $this->filterActive,
        ], 10);

        return view('livewire.admin.destinations.destination-manager', [
            'destinations' => $destinations,
        ]);
    }
}
