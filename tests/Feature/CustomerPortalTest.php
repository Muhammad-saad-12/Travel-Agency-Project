<?php

namespace Tests\Feature;

use App\Livewire\Public\HomePage;
use App\Models\AgencySetting;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\TourPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_portal_homepage_loads_successfully(): void
    {
        AgencySetting::factory()->create([
            'business_name' => 'FlyAero Travels & Tours',
            'whatsapp_number' => '+92 300 1234567',
            'default_currency' => 'PKR',
        ]);

        $dest = Destination::factory()->create(['name' => 'Hunza Valley', 'region' => 'Northern Pakistan']);
        Hotel::factory()->create(['destination_id' => $dest->id, 'name' => 'Hunza Serena Inn']);
        TourPackage::factory()->featured()->create(['destination_id' => $dest->id, 'title' => '8-Day Autumn Hunza']);
        TourPackage::factory()->umrah()->create(['title' => '14-Day Executive Umrah']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('FlyAero Travels');
        $response->assertSee('Flight Inquiries');
        $response->assertSee('PIA');
        $response->assertSee('Airblue');
        $response->assertSee('Emirates');
        $response->assertSee('Saudia');
        $response->assertSee('Raast');
        $response->assertSee('JazzCash');
        $response->assertSee('WhatsApp Booking Desk');
    }

    public function test_tab_switching_on_homepage(): void
    {
        $dest = Destination::factory()->create();
        Hotel::factory()->create(['destination_id' => $dest->id, 'name' => 'Test Grand Hotel']);

        Livewire::test(HomePage::class)
            ->assertSet('activeTab', 'flights')
            ->call('setTab', 'hotels')
            ->assertSet('activeTab', 'hotels')
            ->assertSee('Test Grand Hotel')
            ->call('setTab', 'packages')
            ->assertSet('activeTab', 'packages');
    }

    public function test_region_filtering_on_homepage(): void
    {
        Destination::factory()->create(['name' => 'Swat', 'region' => 'Northern Pakistan']);
        Destination::factory()->create(['name' => 'Baku', 'region' => 'Central Asia']);

        Livewire::test(HomePage::class)
            ->assertSet('selectedRegion', 'Northern Pakistan')
            ->assertSee('Swat')
            ->call('setRegion', 'Central Asia')
            ->assertSet('selectedRegion', 'Central Asia')
            ->assertSee('Baku');
    }
}
