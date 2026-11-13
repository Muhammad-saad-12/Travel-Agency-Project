<?php

namespace Tests\Feature;

use App\Livewire\Admin\Inquiries\InquiryInbox;
use App\Models\FlightInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InquiryInboxLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_inbox_page_loads_successfully(): void
    {
        FlightInquiry::factory()->create([
            'reference_no' => 'INQ-2026-VIEW',
            'customer_name' => 'Khurram Shehzad',
            'origin' => 'Islamabad',
            'destination' => 'Dubai',
        ]);

        $response = $this->get('/admin/inquiries');

        $response->assertStatus(200);
        $response->assertSee('Flight Inquiry Inbox');
        $response->assertSee('Module 5');
        $response->assertSee('INQ-2026-VIEW');
        $response->assertSee('Khurram Shehzad');
    }

    public function test_can_filter_inbox_by_status_tabs(): void
    {
        FlightInquiry::factory()->create([
            'reference_no' => 'INQ-2026-PEND',
            'status' => 'Pending',
        ]);

        FlightInquiry::factory()->create([
            'reference_no' => 'INQ-2026-BOOK',
            'status' => 'Booked',
        ]);

        Livewire::test(InquiryInbox::class)
            ->call('setStatusFilter', 'Pending')
            ->assertSee('INQ-2026-PEND')
            ->assertDontSee('INQ-2026-BOOK')
            ->call('setStatusFilter', 'Booked')
            ->assertSee('INQ-2026-BOOK')
            ->assertDontSee('INQ-2026-PEND');
    }

    public function test_can_open_and_view_detail_modal(): void
    {
        $inquiry = FlightInquiry::factory()->create([
            'notes' => 'Very specific seat preference near window',
        ]);

        Livewire::test(InquiryInbox::class)
            ->call('openDetailModal', $inquiry->id)
            ->assertSet('showDetailModal', true)
            ->assertSee('Very specific seat preference near window')
            ->call('closeDetailModal')
            ->assertSet('showDetailModal', false);
    }

    public function test_can_update_quote_price_in_pkr(): void
    {
        $inquiry = FlightInquiry::factory()->pending()->create();

        Livewire::test(InquiryInbox::class)
            ->call('openQuoteModal', $inquiry->id)
            ->assertSet('showQuoteModal', true)
            ->set('quotePrice', 225000)
            ->set('quoteStatus', 'Quoted')
            ->call('saveQuote')
            ->assertHasNoErrors()
            ->assertSet('showQuoteModal', false);

        $this->assertEquals('225000.00', $inquiry->fresh()->quoted_price_pkr);
        $this->assertEquals('Quoted', $inquiry->fresh()->status);
    }

    public function test_can_change_status_via_quick_dropdown(): void
    {
        $inquiry = FlightInquiry::factory()->create(['status' => 'Pending']);

        Livewire::test(InquiryInbox::class)
            ->call('updateQuickStatus', $inquiry->id, 'Contacted');

        $this->assertEquals('Contacted', $inquiry->fresh()->status);
    }

    public function test_can_delete_inquiry(): void
    {
        $inquiry = FlightInquiry::factory()->create();

        Livewire::test(InquiryInbox::class)
            ->call('deleteInquiry', $inquiry->id);

        $this->assertDatabaseMissing('flight_inquiries', ['id' => $inquiry->id]);
    }
}
