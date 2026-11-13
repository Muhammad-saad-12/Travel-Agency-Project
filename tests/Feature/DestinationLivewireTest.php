<?php

namespace Tests\Feature;

use App\Livewire\Admin\Destinations\DestinationManager;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DestinationLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_destinations_page_loads_successfully(): void
    {
        Destination::factory()->create(['name' => 'Hunza Valley']);

        $response = $this->get('/admin/destinations');

        $response->assertStatus(200);
        $response->assertSee('Destination Management');
        $response->assertSee('Module 12');
        $response->assertSee('Hunza Valley');
    }

    public function test_realtime_validation_requires_name(): void
    {
        Livewire::test(DestinationManager::class)
            ->call('openCreateModal')
            ->set('name', '')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_can_create_destination_via_livewire(): void
    {
        Livewire::test(DestinationManager::class)
            ->call('openCreateModal')
            ->set('name', 'Skardu Valley')
            ->set('country', 'Pakistan')
            ->set('city', 'Skardu')
            ->set('type', 'domestic')
            ->set('region', 'Northern Pakistan')
            ->set('description', 'K2 gateway')
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('destinations', [
            'name' => 'Skardu Valley',
            'city' => 'Skardu',
            'type' => 'domestic',
        ]);
    }

    public function test_can_edit_destination_via_livewire(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Makkah',
            'country' => 'Saudi Arabia',
            'city' => 'Makkah',
            'type' => 'international',
            'region' => 'Middle East',
        ]);

        Livewire::test(DestinationManager::class)
            ->call('openEditModal', $destination->id)
            ->assertSet('name', 'Makkah')
            ->set('name', 'Makkah Al-Mukarramah')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => 'Makkah Al-Mukarramah',
        ]);
    }

    public function test_can_toggle_destination_status_via_livewire(): void
    {
        $destination = Destination::factory()->create(['is_active' => true]);

        Livewire::test(DestinationManager::class)
            ->call('toggleStatus', $destination->id);

        $this->assertFalse((bool) $destination->fresh()->is_active);
    }

    public function test_can_delete_destination_via_livewire(): void
    {
        $destination = Destination::factory()->create();

        Livewire::test(DestinationManager::class)
            ->call('confirmDelete', $destination->id)
            ->assertSet('showDeleteModal', true)
            ->call('delete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
    }
}
