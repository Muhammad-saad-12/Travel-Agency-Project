<?php

namespace Tests\Feature;

use App\Livewire\Admin\Dashboard\AdminDashboard;
use App\Models\Booking;
use App\Models\FlightInquiry;
use App\Models\OfflineRequest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_page_loads_at_root_and_dashboard_routes(): void
    {
        Booking::factory()->create([
            'status' => 'Ticketed',
            'total_amount' => 145000,
            'customer_name' => 'Departure Passenger',
            'travel_date' => now()->addDays(2),
        ]);

        FlightInquiry::factory()->create(['status' => 'Pending']);
        OfflineRequest::factory()->create(['is_urgent' => true, 'status' => 'Applied']);
        Payment::factory()->create(['status' => 'Pending']);

        $responseRoot = $this->get('/admin');
        $responseRoot->assertStatus(200);
        $responseRoot->assertSee('Admin Operations Dashboard');
        $responseRoot->assertSee('Module 3');
        $responseRoot->assertSee('Amadeus (1A)');
        $responseRoot->assertSee('Fly Jinnah (9P)');
        $responseRoot->assertSee('AirSial (PF)');

        $responseDash = $this->get('/admin/dashboard');
        $responseDash->assertStatus(200);
        $responseDash->assertSee('Admin Operations Dashboard');
    }

    public function test_dashboard_renders_livewire_kpis_and_departures(): void
    {
        Booking::factory()->create([
            'status' => 'Ticketed',
            'customer_name' => 'Ghulam Nabi',
            'route' => 'ISB - JED',
            'travel_date' => now()->addDays(3),
            'total_amount' => 180000,
        ]);

        Livewire::test(AdminDashboard::class)
            ->assertSee('Total Confirmed Revenue')
            ->assertSee('Operations Action Required')
            ->assertSee('Upcoming Departures')
            ->assertSee('Ghulam Nabi')
            ->assertSee('ISB - JED')
            ->assertSee('Supplier Health')
            ->assertSee('Pakistan International Airlines');
    }
}
