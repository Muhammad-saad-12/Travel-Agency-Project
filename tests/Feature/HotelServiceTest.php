<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Hotel;
use App\Services\HotelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelServiceTest extends TestCase
{
    use RefreshDatabase;

    private HotelService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(HotelService::class);
    }

    public function test_can_list_hotels_with_filters(): void
    {
        $dest = Destination::factory()->create(['name' => 'Hunza Valley']);

        Hotel::factory()->create([
            'destination_id' => $dest->id,
            'name' => 'Hunza Serena Inn',
            'star_rating' => 5,
            'is_partner' => true,
            'nightly_rate_pkr' => 45000,
        ]);

        Hotel::factory()->create([
            'name' => 'City Budget Lodge',
            'star_rating' => 2,
            'is_partner' => false,
            'nightly_rate_pkr' => 8000,
        ]);

        $partners = $this->service->list(['is_partner' => true]);
        $this->assertCount(1, $partners->items());
        $this->assertEquals('Hunza Serena Inn', $partners->items()[0]->name);

        $fiveStars = $this->service->list(['star_rating' => 5]);
        $this->assertCount(1, $fiveStars->items());
    }

    public function test_can_create_hotel(): void
    {
        $dest = Destination::factory()->create();

        $hotel = $this->service->create([
            'destination_id' => $dest->id,
            'name' => 'Shangrila Resort Hotel',
            'star_rating' => 4,
            'room_types' => 'Lake View Cottage, Presidential Suite',
            'location_address' => 'Lower Kachura Lake, Skardu',
            'nightly_rate_pkr' => 38000,
            'total_rooms' => 50,
            'available_rooms' => 20,
            'is_partner' => true,
        ]);

        $this->assertInstanceOf(Hotel::class, $hotel);
        $this->assertEquals('Shangrila Resort Hotel', $hotel->name);
        $this->assertIsArray($hotel->room_types);
        $this->assertContains('Lake View Cottage', $hotel->room_types);
        $this->assertDatabaseHas('hotels', ['name' => 'Shangrila Resort Hotel']);
    }

    public function test_can_update_hotel(): void
    {
        $hotel = Hotel::factory()->create(['nightly_rate_pkr' => 20000]);

        $updated = $this->service->update($hotel, [
            'name' => 'Renovated Luxury Hotel',
            'nightly_rate_pkr' => 35000,
        ]);

        $this->assertEquals('Renovated Luxury Hotel', $updated->name);
        $this->assertEquals('35000.00', $updated->nightly_rate_pkr);
    }

    public function test_can_toggle_hotel_partner_status(): void
    {
        $hotel = Hotel::factory()->create(['is_partner' => false]);

        $toggled = $this->service->togglePartner($hotel);
        $this->assertTrue($toggled->is_partner);
    }

    public function test_can_delete_hotel(): void
    {
        $hotel = Hotel::factory()->create();

        $this->service->delete($hotel);

        $this->assertDatabaseMissing('hotels', ['id' => $hotel->id]);
    }
}
