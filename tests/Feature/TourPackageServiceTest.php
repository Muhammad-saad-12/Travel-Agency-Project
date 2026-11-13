<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\TourPackage;
use App\Services\TourPackageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourPackageServiceTest extends TestCase
{
    use RefreshDatabase;

    private TourPackageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TourPackageService::class);
    }

    public function test_can_list_packages_with_filters(): void
    {
        $dest = Destination::factory()->create();

        TourPackage::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Executive Umrah 14 Days',
            'is_umrah' => true,
            'is_featured' => true,
        ]);

        TourPackage::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Swat Valley Tour',
            'is_umrah' => false,
            'is_featured' => false,
        ]);

        $umrahPackages = $this->service->list(['is_umrah' => true]);
        $this->assertCount(1, $umrahPackages->items());
        $this->assertEquals('Executive Umrah 14 Days', $umrahPackages->items()[0]->title);

        $featured = $this->service->list(['is_featured' => true]);
        $this->assertCount(1, $featured->items());
    }

    public function test_can_create_tour_package_with_slug_and_inclusions(): void
    {
        $dest = Destination::factory()->create();

        $package = $this->service->create([
            'destination_id' => $dest->id,
            'title' => '8-Day Autumn Hunza Tour',
            'duration_days' => 8,
            'price_pkr' => 125000,
            'difficulty_rating' => 'Moderate',
            'is_umrah' => false,
            'inclusions' => "Return Airfare\n4-Star Hotel\nPrivate AC Transport",
            'itinerary' => "Day 1: Departure\nDay 2: Arrival at Hunza\nDay 3: Khunjerab Pass",
            'is_featured' => true,
        ]);

        $this->assertInstanceOf(TourPackage::class, $package);
        $this->assertEquals('8-day-autumn-hunza-tour', $package->slug);
        $this->assertIsArray($package->inclusions);
        $this->assertCount(3, $package->inclusions);
        $this->assertDatabaseHas('tour_packages', ['title' => '8-Day Autumn Hunza Tour']);
    }

    public function test_can_update_tour_package(): void
    {
        $package = TourPackage::factory()->create(['price_pkr' => 100000]);

        $updated = $this->service->update($package, [
            'price_pkr' => 135000,
            'difficulty_rating' => 'Challenging',
        ]);

        $this->assertEquals('135000.00', $updated->price_pkr);
        $this->assertEquals('Challenging', $updated->difficulty_rating);
    }

    public function test_can_toggle_featured_status(): void
    {
        $package = TourPackage::factory()->create(['is_featured' => false]);

        $toggled = $this->service->toggleFeatured($package);
        $this->assertTrue($toggled->is_featured);
    }

    public function test_can_delete_tour_package(): void
    {
        $package = TourPackage::factory()->create();

        $this->service->delete($package);

        $this->assertDatabaseMissing('tour_packages', ['id' => $package->id]);
    }
}
