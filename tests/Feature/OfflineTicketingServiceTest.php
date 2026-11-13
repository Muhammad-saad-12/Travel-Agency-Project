<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\OfflineRequest;
use App\Services\OfflineTicketingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfflineTicketingServiceTest extends TestCase
{
    use RefreshDatabase;

    private OfflineTicketingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OfflineTicketingService::class);
    }

    public function test_can_create_offline_request(): void
    {
        $request = $this->service->createRequest([
            'booking_ref' => 'FB-2026-OFF1',
            'pnr' => 'AIR990',
            'airline' => 'PIA (PK)',
            'route' => 'KHI - ISB',
            'passenger_name' => 'Waqas Ali',
            'request_type' => 'ticketing',
            'status' => 'Applied',
            'is_urgent' => true,
        ]);

        $this->assertDatabaseHas('offline_requests', [
            'booking_ref' => 'FB-2026-OFF1',
            'pnr' => 'AIR990',
            'is_urgent' => true,
        ]);
    }

    public function test_can_filter_requests(): void
    {
        OfflineRequest::factory()->create([
            'request_type' => 'ticketing',
            'airline' => 'Airblue (PA)',
            'is_urgent' => true,
            'passenger_name' => 'Urgent Flyer',
        ]);

        OfflineRequest::factory()->create([
            'request_type' => 'refund',
            'airline' => 'Emirates (EK)',
            'is_urgent' => false,
            'passenger_name' => 'Refund Flyer',
        ]);

        $ticketing = $this->service->listRequests(['request_type' => 'ticketing']);
        $this->assertEquals(1, $ticketing->total());
        $this->assertEquals('Airblue (PA)', $ticketing->items()[0]->airline);

        $urgent = $this->service->listRequests(['is_urgent' => true]);
        $this->assertEquals(1, $urgent->total());
        $this->assertEquals('Urgent Flyer', $urgent->items()[0]->passenger_name);
    }

    public function test_can_toggle_urgent(): void
    {
        $request = OfflineRequest::factory()->create(['is_urgent' => false]);

        $updated = $this->service->toggleUrgent($request);
        $this->assertTrue($updated->is_urgent);

        $toggledBack = $this->service->toggleUrgent($updated);
        $this->assertFalse($toggledBack->is_urgent);
    }

    public function test_can_process_ticketing_and_sync_booking(): void
    {
        $booking = Booking::factory()->create([
            'pnr' => 'SYNC99',
            'status' => 'Confirmed',
            'ticket_ref' => null,
        ]);

        $req = OfflineRequest::factory()->create([
            'pnr' => 'SYNC99',
            'booking_ref' => $booking->booking_reference,
            'request_type' => 'ticketing',
            'status' => 'Applied',
        ]);

        $completed = $this->service->processTicketing($req, '214-5566778899', 'Usman Qazi', 'Issued via Amadeus terminal');

        $this->assertEquals('Completed', $completed->status);
        $this->assertEquals('214-5566778899', $completed->ticket_number);
        $this->assertEquals('Usman Qazi', $completed->assigned_user);

        // Assert booking synchronized
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Ticketed',
            'ticket_ref' => '214-5566778899',
        ]);
    }

    public function test_can_process_refund_and_sync_booking(): void
    {
        $booking = Booking::factory()->create([
            'pnr' => 'REF990',
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
        ]);

        $req = OfflineRequest::factory()->create([
            'pnr' => 'REF990',
            'booking_ref' => $booking->booking_reference,
            'request_type' => 'refund',
            'status' => 'Applied',
        ]);

        $completed = $this->service->processRefund($req, 75000, 15000, 'Airline penalty applied per fare rule');

        $this->assertEquals('Completed', $completed->status);
        $this->assertEquals(75000, (float) $completed->refund_amount);
        $this->assertEquals(15000, (float) $completed->penalty_amount);

        // Assert booking updated
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Cancelled',
            'payment_status' => 'Refunded',
        ]);
    }

    public function test_can_get_statistics_and_airlines_list(): void
    {
        OfflineRequest::factory()->create(['request_type' => 'ticketing', 'status' => 'Applied', 'airline' => 'PIA (PK)']);
        OfflineRequest::factory()->create(['request_type' => 'refund', 'status' => 'Applied', 'airline' => 'Fly Jinnah (9P)']);

        $stats = $this->service->getStatistics();
        $this->assertGreaterThanOrEqual(1, $stats['ticketing_pending']);
        $this->assertGreaterThanOrEqual(1, $stats['refund_pending']);

        $airlines = $this->service->getAirlinesList();
        $this->assertContains('PIA (PK)', $airlines);
        $this->assertContains('Fly Jinnah (9P)', $airlines);
    }
}
