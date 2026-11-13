<?php

namespace App\Livewire\Admin\Hotels;

use App\Services\DestinationService;
use App\Services\HotelService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Hotel Management (Module 13)')]
class HotelManager extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public string $filterDestination = '';

    public string $filterStars = '';

    public string $filterPartner = '';

    // Modal state
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingHotelId = null;

    public ?int $deletingHotelId = null;

    // Form inputs
    public ?int $destination_id = null;

    public string $name = '';

    public int $star_rating = 4;

    public string $room_types_input = 'Standard Room, Deluxe Double, Executive Suite';

    public string $location_address = '';

    public float|int|string $nightly_rate_pkr = 25000;

    public int $total_rooms = 40;

    public int $available_rooms = 20;

    public bool $is_partner = false;

    public bool $savedSuccessfully = false;

    /**
     * Validation rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|min:2|max:255',
            'star_rating' => 'required|integer|min:1|max:5',
            'room_types_input' => 'required|string',
            'location_address' => 'required|string|max:500',
            'nightly_rate_pkr' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0|lte:total_rooms',
            'is_partner' => 'boolean',
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

    public function updatingFilterStars(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPartner(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(DestinationService $destinationService): void
    {
        $this->resetValidation();
        $this->editingHotelId = null;
        $firstDest = $destinationService->getAllActive()->first();
        $this->destination_id = $firstDest?->id;
        $this->name = '';
        $this->star_rating = 4;
        $this->room_types_input = 'Standard Room, Deluxe Double, Executive Suite';
        $this->location_address = '';
        $this->nightly_rate_pkr = 25000;
        $this->total_rooms = 40;
        $this->available_rooms = 20;
        $this->is_partner = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id, HotelService $service): void
    {
        $this->resetValidation();
        $hotel = $service->findById($id);

        $this->editingHotelId = $hotel->id;
        $this->destination_id = $hotel->destination_id;
        $this->name = $hotel->name;
        $this->star_rating = $hotel->star_rating;
        $this->room_types_input = is_array($hotel->room_types) ? implode(', ', $hotel->room_types) : '';
        $this->location_address = $hotel->location_address;
        $this->nightly_rate_pkr = (float) $hotel->nightly_rate_pkr;
        $this->total_rooms = $hotel->total_rooms;
        $this->available_rooms = $hotel->available_rooms;
        $this->is_partner = (bool) $hotel->is_partner;

        $this->showModal = true;
    }

    public function save(HotelService $service): void
    {
        $validated = $this->validate();

        $data = [
            'destination_id' => $validated['destination_id'],
            'name' => $validated['name'],
            'star_rating' => $validated['star_rating'],
            'room_types' => $validated['room_types_input'],
            'location_address' => $validated['location_address'],
            'nightly_rate_pkr' => $validated['nightly_rate_pkr'],
            'total_rooms' => $validated['total_rooms'],
            'available_rooms' => $validated['available_rooms'],
            'is_partner' => $validated['is_partner'],
        ];

        if ($this->editingHotelId) {
            $service->update($this->editingHotelId, $data);
            session()->flash('status', 'Hotel updated successfully.');
        } else {
            $service->create($data);
            session()->flash('status', 'Hotel registered successfully.');
        }

        $this->savedSuccessfully = true;
        $this->showModal = false;
    }

    public function togglePartner(int $id, HotelService $service): void
    {
        $service->togglePartner($id);
        session()->flash('status', 'Hotel partner badge toggled.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingHotelId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(HotelService $service): void
    {
        if ($this->deletingHotelId) {
            $service->delete($this->deletingHotelId);
            $this->deletingHotelId = null;
            $this->showDeleteModal = false;
            session()->flash('status', 'Hotel deleted successfully.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
    }

    public function render(HotelService $service, DestinationService $destService)
    {
        $hotels = $service->list([
            'search' => $this->search,
            'destination_id' => $this->filterDestination,
            'star_rating' => $this->filterStars,
            'is_partner' => $this->filterPartner,
        ], 10);

        return view('livewire.admin.hotels.hotel-manager', [
            'hotels' => $hotels,
            'destinations' => $destService->getAllActive(),
        ]);
    }
}
