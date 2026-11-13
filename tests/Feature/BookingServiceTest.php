<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(BookingService::class);
    }

    public function test_can_create_booking_and_auto_generate_reference(): void
    {
        $booking = $this->service->createBooking([
            'pnr' => 'TST123',
            'customer_name' => 'Rashid Mehmood',
            'customer_email' => 'rashid@test.pk',
            'customer_phone' => '+92 300 1122334',
            'service' => 'Flight Ticket',
            'type' => 'flight',
            'route' => 'KHI - ISB',
            'airline' => 'Airblue',
            'flight_number' => 'PA200',
            'travel_date' => Carbon::now()->addDays(5)->toDateString(),
            'passengers_count' => 2,
            'fare_amount' => 40000,
            'tax_amount' => 10000,
            'ancillaries_amount' => 2000,
            'markup_amount' => 1000,
            'currency' => 'PKR',
        ]);

        $this->assertNotEmpty($booking->booking_reference);
        $this->assertStringStartsWith('PK-BK-', $booking->booking_reference);
        $this->assertEquals(53000, (float) $booking->total_amount);
        $this->assertEquals('Confirmed', $booking->status);
        $this->assertDatabaseHas('bookings', [
            'booking_reference' => $booking->booking_reference,
            'pnr' => 'TST123',
        ]);
    }

    public function test_can_filter_bookings_by_search_type_and_status(): void
    {
        Booking::factory()->create([
            'booking_reference' => 'FB-2026-SRCH1',
            'customer_name' => 'UniqueCustomer Khan',
            'type' => 'flight',
            'status' => 'Confirmed',
        ]);

        Booking::factory()->create([
            'booking_reference' => 'FB-2026-SRCH2',
            'customer_name' => 'Hotel Guest',
            'type' => 'hotel',
            'status' => 'Pending',
        ]);

        $flightResults = $this->service->listBookings(['type' => 'flight']);
        $this->assertEquals(1, $flightResults->total());
        $this->assertEquals('FB-2026-SRCH1', $flightResults->items()[0]->booking_reference);

        $searchName = $this->service->listBookings(['search' => 'UniqueCustomer']);
        $this->assertEquals(1, $searchName->total());

        $pendingResults = $this->service->listBookings(['status' => 'Pending']);
        $this->assertEquals(1, $pendingResults->total());
        $this->assertEquals('FB-2026-SRCH2', $pendingResults->items()[0]->booking_reference);
    }

    public function test_can_issue_ticket_and_transition_status(): void
    {
        $booking = Booking::factory()->create([
            'status' => 'Confirmed',
            'ticket_ref' => null,
        ]);

        $updated = $this->service->issueTicket($booking, '214-9988776655');

        $this->assertEquals('Ticketed', $updated->status);
        $this->assertEquals('214-9988776655', $updated->ticket_ref);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Ticketed',
            'ticket_ref' => '214-9988776655',
        ]);
    }

    public function test_can_update_status_and_remarks(): void
    {
        $booking = Booking::factory()->create([
            'status' => 'Confirmed',
        ]);

        $updated = $this->service->updateStatus($booking, 'Cancelled', 'Client requested full cancellation');

        $this->assertEquals('Cancelled', $updated->status);
        $this->assertEquals('Client requested full cancellation', $updated->remarks);
    }

    public function test_can_compute_statistics(): void
    {
        Booking::factory()->count(3)->create(['type' => 'flight', 'status' => 'Confirmed', 'total_amount' => 50000]);
        Booking::factory()->count(2)->create(['type' => 'hotel', 'status' => 'Ticketed', 'total_amount' => 30000]);

        $stats = $this->service->getStatistics();

        $this->assertEquals(5, $stats['total_bookings']);
        $this->assertEquals(3, $stats['flight_count']);
        $this->assertEquals(2, $stats['hotel_count']);
        $this->assertEquals(3, $stats['confirmed_count']);
        $this->assertEquals(2, $stats['ticketed_count']);
        $this->assertEquals(210000, $stats['total_revenue_pkr']);
    }

    public function test_can_export_bookings_to_csv(): void
    {
        Booking::factory()->create([
            'booking_reference' => 'FB-2026-CSV1',
            'customer_name' => 'CSV Test Customer',
        ]);

        $response = $this->service->exportCsv();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
