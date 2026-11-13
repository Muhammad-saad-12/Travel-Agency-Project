<?php

namespace App\Livewire\Public;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\TourPackage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.public')]
#[Title('FlyAero Travels | Flights, Umrah & Pakistan Tour Packages')]
class HomePage extends Component
{
    // Search Tabs
    public string $activeTab = 'flights'; // flights, hotels, packages

    // Hotel Filters
    public string $hotelSearch = '';

    public ?int $hotelDestinationId = null;

    // Package Filters
    public string $packageSearch = '';

    public ?int $packageDestinationId = null;

    public string $packageType = 'all'; // all, umrah, general

    // Popular Destinations Region Filter
    public string $selectedRegion = 'Northern Pakistan';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setRegion(string $region): void
    {
        $this->selectedRegion = $region;
    }

    public function render()
    {
        // Destinations for search dropdowns
        $destinations = Destination::query()->active()->orderBy('name')->get();

        // Featured Packages
        $featuredPackages = TourPackage::query()
            ->with('destination')
            ->featured()
            ->take(3)
            ->get();

        // Umrah Packages
        $umrahPackages = TourPackage::query()
            ->with('destination')
            ->umrah()
            ->take(2)
            ->get();

        // Filtered Hotels in Hotels Tab
        $hotelsQuery = Hotel::query()->with('destination');
        if (! empty($this->hotelSearch)) {
            $hotelsQuery->where('name', 'like', '%'.trim($this->hotelSearch).'%');
        }
        if ($this->hotelDestinationId) {
            $hotelsQuery->where('destination_id', $this->hotelDestinationId);
        }
        $tabHotels = $hotelsQuery->take(6)->get();

        // Filtered Packages in Packages Tab
        $packagesQuery = TourPackage::query()->with('destination');
        if (! empty($this->packageSearch)) {
            $packagesQuery->where('title', 'like', '%'.trim($this->packageSearch).'%');
        }
        if ($this->packageDestinationId) {
            $packagesQuery->where('destination_id', $this->packageDestinationId);
        }
        if ($this->packageType === 'umrah') {
            $packagesQuery->where('is_umrah', true);
        } elseif ($this->packageType === 'general') {
            $packagesQuery->where('is_umrah', false);
        }
        $tabPackages = $packagesQuery->take(6)->get();

        // Popular destinations by selected region
        $regionalDestinations = Destination::query()
            ->active()
            ->where('region', $this->selectedRegion)
            ->withCount(['hotels', 'tourPackages'])
            ->get();

        return view('livewire.public.home-page', [
            'destinations' => $destinations,
            'featuredPackages' => $featuredPackages,
            'umrahPackages' => $umrahPackages,
            'tabHotels' => $tabHotels,
            'tabPackages' => $tabPackages,
            'regionalDestinations' => $regionalDestinations,
        ]);
    }
}
