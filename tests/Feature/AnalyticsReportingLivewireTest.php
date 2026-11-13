<?php

namespace Tests\Feature;

use App\Livewire\Admin\Analytics\AnalyticsReporting;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsReportingLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_reporting_page_loads_successfully(): void
    {
        Booking::factory()->create([
            'route' => 'KHI - DXB',
            'status' => 'Ticketed',
            'total_amount' => 95000,
        ]);

        Payment::factory()->create([
            'channel_category' => 'raast',
            'status' => 'Verified',
            'amount' => 95000,
        ]);

        $response = $this->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertSee('Advanced Analytics & Reporting');
        $response->assertSee('Module 15');
        $response->assertSee('Conversion Rate');
        $response->assertSee('Average Booking Value');
        $response->assertSee('Monthly Comparisons Matrix');
        $response->assertSee('Service Revenue Contribution');
    }

    public function test_can_trigger_executive_analytics_csv_export(): void
    {
        Booking::factory()->create([
            'route' => 'ISB - LHR',
            'status' => 'Confirmed',
            'total_amount' => 215000,
        ]);

        Livewire::test(AnalyticsReporting::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
