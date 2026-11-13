<?php

namespace Tests\Feature;

use App\Livewire\Admin\Packages\PackageManager;
use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TourPackageLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_packages_page_loads_successfully(): void
    {
        $pkg = TourPackage::factory()->create([
            'title' => 'Executive Umrah 14-Day',
            'price_pkr' => 425000,
            'is_umrah' => true,
        ]);

        $response = $this->get('/admin/packages');

        $response->assertStatus(200);
        $response->assertSee('Tour Package Management');
        $response->assertSee('Module 11');
        $response->assertSee('Executive Umrah 14-Day');
        $response->assertSee('425,000');
    }

    public function test_realtime_validation_requires_package_fields(): void
    {
        Livewire::test(PackageManager::class)
            ->call('openCreateModal')
            ->set('title', '')
            ->assertHasErrors(['title' => 'required'])
            ->set('duration_days', 0)
            ->assertHasErrors(['duration_days' => 'min'])
            ->set('difficulty_rating', 'Extreme')
            ->assertHasErrors(['difficulty_rating' => 'in']);
    }

    public function test_can_create_tour_package_via_livewire(): void
    {
        $dest = Destination::factory()->create(['name' => 'Hunza']);

        Livewire::test(PackageManager::class)
            ->call('openCreateModal')
            ->set('destination_id', $dest->id)
            ->set('title', '7-Day Hunza Adventure')
            ->set('duration_days', 7)
            ->set('price_pkr', 115000)
            ->set('difficulty_rating', 'Moderate')
            ->set('is_umrah', false)
            ->set('inclusions_text', "Hotel\nTransport\nGuide")
            ->set('itinerary_text', "Day 1: Arrival\nDay 2: Forts")
            ->set('is_featured', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('tour_packages', [
            'title' => '7-Day Hunza Adventure',
            'duration_days' => 7,
            'destination_id' => $dest->id,
            'is_featured' => true,
        ]);
    }

    public function test_can_edit_tour_package_via_livewire(): void
    {
        $pkg = TourPackage::factory()->create([
            'title' => 'Initial Title',
            'price_pkr' => 90000,
        ]);

        Livewire::test(PackageManager::class)
            ->call('openEditModal', $pkg->id)
            ->set('title', 'Updated Deluxe Package')
            ->set('price_pkr', 120000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Updated Deluxe Package', $pkg->fresh()->title);
        $this->assertEquals('120000.00', $pkg->fresh()->price_pkr);
    }

    public function test_can_toggle_featured_badge_via_livewire(): void
    {
        $pkg = TourPackage::factory()->create(['is_featured' => false]);

        Livewire::test(PackageManager::class)
            ->call('toggleFeatured', $pkg->id);

        $this->assertTrue((bool) $pkg->fresh()->is_featured);
    }

    public function test_can_delete_tour_package_via_livewire(): void
    {
        $pkg = TourPackage::factory()->create();

        Livewire::test(PackageManager::class)
            ->call('confirmDelete', $pkg->id)
            ->call('delete');

        $this->assertDatabaseMissing('tour_packages', ['id' => $pkg->id]);
    }
}
