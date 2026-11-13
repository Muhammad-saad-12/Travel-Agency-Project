<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Services\DestinationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationServiceTest extends TestCase
{
    use RefreshDatabase;

    private DestinationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestinationService::class);
    }

    public function test_can_list_destinations_with_filters(): void
    {
        Destination::factory()->create([
            'name' => 'Skardu Valley',
            'type' => 'domestic',
            'region' => 'Northern Pakistan',
        ]);

        Destination::factory()->create([
            'name' => 'Baku City',
            'type' => 'international',
            'region' => 'Central Asia',
        ]);

        $domestic = $this->service->list(['type' => 'domestic']);
        $this->assertCount(1, $domestic->items());
        $this->assertEquals('Skardu Valley', $domestic->items()[0]->name);

        $searchResult = $this->service->list(['search' => 'Baku']);
        $this->assertCount(1, $searchResult->items());
        $this->assertEquals('Baku City', $searchResult->items()[0]->name);
    }

    public function test_can_create_destination_with_automatic_slug(): void
    {
        $destination = $this->service->create([
            'name' => 'Hunza Valley',
            'country' => 'Pakistan',
            'city' => 'Karimabad',
            'type' => 'domestic',
            'region' => 'Northern Pakistan',
            'description' => 'Mountain paradise.',
            'is_active' => true,
        ]);

        $this->assertEquals('hunza-valley', $destination->slug);
        $this->assertDatabaseHas('destinations', [
            'name' => 'Hunza Valley',
            'slug' => 'hunza-valley',
        ]);
    }

    public function test_can_update_destination(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Old Swat',
            'slug' => 'old-swat',
        ]);

        $updated = $this->service->update($destination, [
            'name' => 'Swat Kalam Valley',
            'slug' => 'swat-kalam-valley',
            'city' => 'Kalam',
            'country' => 'Pakistan',
            'type' => 'domestic',
            'region' => 'Northern Pakistan',
        ]);

        $this->assertEquals('Swat Kalam Valley', $updated->name);
        $this->assertEquals('swat-kalam-valley', $updated->slug);
    }

    public function test_can_toggle_destination_active_status(): void
    {
        $destination = Destination::factory()->create(['is_active' => true]);

        $toggled = $this->service->toggleStatus($destination);
        $this->assertFalse($toggled->is_active);

        $toggledAgain = $this->service->toggleStatus($toggled);
        $this->assertTrue($toggledAgain->is_active);
    }

    public function test_can_delete_destination(): void
    {
        $destination = Destination::factory()->create();

        $result = $this->service->delete($destination);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
    }
}
