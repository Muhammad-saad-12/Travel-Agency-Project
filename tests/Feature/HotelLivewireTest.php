<?php

namespace Tests\Feature;

use App\Livewire\Admin\Hotels\HotelManager;
use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HotelLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotels_page_loads_successfully(): void
    {
        $hotel = Hotel::factory()->create([
            'name' => 'Makkah Clock Royal Tower',
            'nightly_rate_pkr' => 95000,
        ]);

        $response = $this->get('/admin/hotels');

        $response->assertStatus(200);
        $response->assertSee('Hotel Management');
        $response->assertSee('Module 13');
        $response->assertSee('Makkah Clock Royal Tower');
        $response->assertSee('95,000');
    }

    public function test_realtime_validation_requires_hotel_fields(): void
    {
        Livewire::test(HotelManager::class)
            ->call('openCreateModal')
            ->set('name', '')
            ->assertHasErrors(['name' => 'required'])
            ->set('star_rating', 6)
            ->assertHasErrors(['star_rating' => 'max']);
    }

    public function test_realtime_validation_available_rooms_cannot_exceed_total_rooms(): void
    {
        Livewire::test(HotelManager::class)
            ->call('openCreateModal')
            ->set('total_rooms', 20)
            ->set('available_rooms', 25)
            ->assertHasErrors(['available_rooms' => 'lte']);
    }

    public function test_can_create_hotel_via_livewire(): void
    {
        $dest = Destination::factory()->create(['name' => 'Swat Valley']);

        Livewire::test(HotelManager::class)
            ->call('openCreateModal')
            ->set('destination_id', $dest->id)
            ->set('name', 'Serena Swat Hotel')
            ->set('star_rating', 4)
            ->set('room_types_input', 'Deluxe Room, Heritage Suite')
            ->set('location_address', 'Saidu Sharif, Swat')
            ->set('nightly_rate_pkr', 28000)
            ->set('total_rooms', 50)
            ->set('available_rooms', 30)
            ->set('is_partner', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('hotels', [
            'name' => 'Serena Swat Hotel',
            'destination_id' => $dest->id,
            'is_partner' => true,
        ]);
    }

    public function test_can_edit_hotel_via_livewire(): void
    {
        $hotel = Hotel::factory()->create([
            'name' => 'Old Hotel',
            'nightly_rate_pkr' => 15000,
        ]);

        Livewire::test(HotelManager::class)
            ->call('openEditModal', $hotel->id)
            ->set('nightly_rate_pkr', 32000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('32000.00', $hotel->fresh()->nightly_rate_pkr);
    }

    public function test_can_toggle_partner_badge_via_livewire(): void
    {
        $hotel = Hotel::factory()->create(['is_partner' => false]);

        Livewire::test(HotelManager::class)
            ->call('togglePartner', $hotel->id);

        $this->assertTrue((bool) $hotel->fresh()->is_partner);
    }

    public function test_can_delete_hotel_via_livewire(): void
    {
        $hotel = Hotel::factory()->create();

        Livewire::test(HotelManager::class)
            ->call('confirmDelete', $hotel->id)
            ->call('delete');

        $this->assertDatabaseMissing('hotels', ['id' => $hotel->id]);
    }
}
