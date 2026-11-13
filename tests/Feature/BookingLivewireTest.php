<?php

namespace Tests\Feature;

use App\Livewire\Admin\Bookings\BookingManager;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_manager_page_loads_successfully(): void
    {
        Booking::factory()->create([
            'booking_reference' => 'FB-2026-ADM1',
            'customer_name' => 'Daniyal Qureshi',
            'pnr' => 'KHI991',
        ]);

        $response = $this->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertSee('Booking Management');
        $response->assertSee('Modules 7 & 8');
        $response->assertSee('FB-2026-ADM1');
        $response->assertSee('Daniyal Qureshi');
        $response->assertSee('KHI991');
    }

    public function test_can_filter_bookings_by_search_in_livewire(): void
    {
        Booking::factory()->create([
            'booking_reference' => 'FB-2026-MATCH',
            'customer_name' => 'Matched User',
        ]);

        Booking::factory()->create([
            'booking_reference' => 'FB-2026-OTHER',
            'customer_name' => 'Other Individual',
        ]);

        Livewire::test(BookingManager::class)
            ->set('search', 'Matched')
            ->assertSee('FB-2026-MATCH')
            ->assertDontSee('FB-2026-OTHER');
    }

    public function test_can_filter_bookings_by_type_in_livewire(): void
    {
        Booking::factory()->create([
            'booking_reference' => 'FB-2026-FLT',
            'type' => 'flight',
        ]);

        Booking::factory()->create([
            'booking_reference' => 'FB-2026-HTL',
            'type' => 'hotel',
        ]);

        Livewire::test(BookingManager::class)
            ->set('type', 'flight')
            ->assertSee('FB-2026-FLT')
            ->assertDontSee('FB-2026-HTL');
    }

    public function test_can_open_detail_modal(): void
    {
        $booking = Booking::factory()->create([
            'customer_email' => 'details.pax@flightdesk.pk',
        ]);

        Livewire::test(BookingManager::class)
            ->call('openDetailModal', $booking->id)
            ->assertSet('showDetailModal', true)
            ->assertSee('details.pax@flightdesk.pk')
            ->call('closeDetailModal')
            ->assertSet('showDetailModal', false);
    }

    public function test_can_issue_ticket_via_livewire(): void
    {
        $booking = Booking::factory()->create([
            'status' => 'Confirmed',
            'ticket_ref' => null,
        ]);

        Livewire::test(BookingManager::class)
            ->call('openTicketModal', $booking->id)
            ->assertSet('showTicketModal', true)
            ->set('ticketRef', '214-7766554433')
            ->call('issueTicket')
            ->assertSet('showTicketModal', false)
            ->assertSee('successfully issued');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Ticketed',
            'ticket_ref' => '214-7766554433',
        ]);
    }

    public function test_can_update_status_via_livewire(): void
    {
        $booking = Booking::factory()->create([
            'status' => 'Pending',
        ]);

        Livewire::test(BookingManager::class)
            ->call('openStatusModal', $booking->id)
            ->assertSet('showStatusModal', true)
            ->set('newStatus', 'Confirmed')
            ->set('statusRemarks', 'Payment verified via bank transfer')
            ->call('updateStatus')
            ->assertSet('showStatusModal', false)
            ->assertSee('status updated');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Confirmed',
            'remarks' => 'Payment verified via bank transfer',
        ]);
    }

    public function test_can_trigger_csv_export(): void
    {
        Booking::factory()->create();

        Livewire::test(BookingManager::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
