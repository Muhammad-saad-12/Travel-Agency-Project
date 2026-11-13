<?php

namespace Tests\Feature;

use App\Models\FlightInquiry;
use App\Services\FlightInquiryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightInquiryServiceTest extends TestCase
{
    use RefreshDatabase;

    private FlightInquiryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FlightInquiryService::class);
    }

    public function test_can_create_flight_inquiry_with_auto_reference(): void
    {
        $inquiry = $this->service->createInquiry([
            'trip_type' => 'round_trip',
            'origin' => 'Islamabad (ISB)',
            'destination' => 'Jeddah (JED)',
            'departure_date' => now()->addDays(5)->format('Y-m-d'),
            'return_date' => now()->addDays(15)->format('Y-m-d'),
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'cabin_class' => 'Economy',
            'customer_name' => 'Tariq Mehmood',
            'customer_phone' => '+92 300 1234567',
            'customer_email' => 'tariq@gmail.com',
            'notes' => 'Umrah flight preference',
        ]);

        $this->assertInstanceOf(FlightInquiry::class, $inquiry);
        $this->assertStringStartsWith('INQ-'.date('Y'), $inquiry->reference_no);
        $this->assertEquals('Pending', $inquiry->status);
        $this->assertDatabaseHas('flight_inquiries', [
            'reference_no' => $inquiry->reference_no,
            'customer_name' => 'Tariq Mehmood',
        ]);
    }

    public function test_can_list_and_filter_inquiries(): void
    {
        FlightInquiry::factory()->create([
            'reference_no' => 'INQ-2026-TEST1',
            'customer_name' => 'Sara Khan',
            'status' => 'Pending',
        ]);

        FlightInquiry::factory()->create([
            'reference_no' => 'INQ-2026-TEST2',
            'customer_name' => 'Zain Ahmed',
            'status' => 'Booked',
        ]);

        $pending = $this->service->list(['status' => 'Pending']);
        $this->assertCount(1, $pending->items());
        $this->assertEquals('Sara Khan', $pending->items()[0]->customer_name);

        $search = $this->service->list(['search' => 'Zain']);
        $this->assertCount(1, $search->items());
        $this->assertEquals('Zain Ahmed', $search->items()[0]->customer_name);
    }

    public function test_can_update_inquiry_status(): void
    {
        $inquiry = FlightInquiry::factory()->pending()->create();

        $updated = $this->service->updateStatus($inquiry, 'Contacted');

        $this->assertEquals('Contacted', $updated->status);
        $this->assertDatabaseHas('flight_inquiries', [
            'id' => $inquiry->id,
            'status' => 'Contacted',
        ]);
    }

    public function test_can_update_quote_in_pkr(): void
    {
        $inquiry = FlightInquiry::factory()->pending()->create();

        $updated = $this->service->updateQuote($inquiry, 285000.00, 'Quoted');

        $this->assertEquals('285000.00', $updated->quoted_price_pkr);
        $this->assertEquals('Quoted', $updated->status);
    }

    public function test_get_status_counts(): void
    {
        FlightInquiry::factory()->create(['status' => 'Pending']);
        FlightInquiry::factory()->create(['status' => 'Pending']);
        FlightInquiry::factory()->create(['status' => 'Booked']);

        $counts = $this->service->getStatusCounts();

        $this->assertEquals(3, $counts['All']);
        $this->assertEquals(2, $counts['Pending']);
        $this->assertEquals(1, $counts['Booked']);
    }
}
