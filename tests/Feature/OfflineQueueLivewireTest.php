<?php

namespace Tests\Feature;

use App\Livewire\Admin\Ticketing\OfflineQueue;
use App\Models\OfflineRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OfflineQueueLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_offline_queue_page_loads_successfully(): void
    {
        OfflineRequest::factory()->create([
            'pnr' => 'TEST99',
            'passenger_name' => 'Farooq Sattar',
            'airline' => 'AirSial (PF)',
        ]);

        $response = $this->get('/admin/ticketing/queue');

        $response->assertStatus(200);
        $response->assertSee('Offline Ticketing & Refund Queue');
        $response->assertSee('Modules 15 & 16');
        $response->assertSee('TEST99');
        $response->assertSee('Farooq Sattar');
    }

    public function test_can_switch_queue_tabs_in_livewire(): void
    {
        OfflineRequest::factory()->create([
            'pnr' => 'TKT111',
            'request_type' => 'ticketing',
        ]);

        OfflineRequest::factory()->create([
            'pnr' => 'REF222',
            'request_type' => 'refund',
        ]);

        Livewire::test(OfflineQueue::class)
            ->call('setRequestType', 'ticketing')
            ->assertSee('TKT111')
            ->assertDontSee('REF222')
            ->call('setRequestType', 'refund')
            ->assertSee('REF222')
            ->assertDontSee('TKT111');
    }

    public function test_can_toggle_urgent_in_livewire(): void
    {
        $req = OfflineRequest::factory()->create(['is_urgent' => false]);

        Livewire::test(OfflineQueue::class)
            ->call('toggleUrgent', $req->id)
            ->assertSee('urgency toggled: Marked URGENT');

        $this->assertDatabaseHas('offline_requests', [
            'id' => $req->id,
            'is_urgent' => true,
        ]);
    }

    public function test_can_process_ticketing_via_livewire(): void
    {
        $req = OfflineRequest::factory()->create([
            'request_type' => 'ticketing',
            'status' => 'Applied',
            'ticket_number' => null,
        ]);

        Livewire::test(OfflineQueue::class)
            ->call('openTicketingModal', $req->id)
            ->assertSet('showTicketingModal', true)
            ->set('ticketNumber', '214-4433221100')
            ->set('assignedUser', 'Desk Officer A')
            ->call('processTicketing')
            ->assertSet('showTicketingModal', false)
            ->assertSee('completed with E-Ticket');

        $this->assertDatabaseHas('offline_requests', [
            'id' => $req->id,
            'status' => 'Completed',
            'ticket_number' => '214-4433221100',
        ]);
    }

    public function test_can_process_refund_via_livewire(): void
    {
        $req = OfflineRequest::factory()->create([
            'request_type' => 'refund',
            'status' => 'Applied',
        ]);

        Livewire::test(OfflineQueue::class)
            ->call('openRefundModal', $req->id)
            ->assertSet('showRefundModal', true)
            ->set('refundAmount', 55000)
            ->set('penaltyAmount', 10000)
            ->call('processRefund')
            ->assertSet('showRefundModal', false)
            ->assertSee('Refund: PKR 55,000.00');

        $this->assertDatabaseHas('offline_requests', [
            'id' => $req->id,
            'status' => 'Completed',
            'refund_amount' => 55000,
            'penalty_amount' => 10000,
        ]);
    }

    public function test_can_assign_officer_via_livewire(): void
    {
        $req = OfflineRequest::factory()->create([
            'assigned_user' => null,
        ]);

        Livewire::test(OfflineQueue::class)
            ->call('openAssignModal', $req->id)
            ->assertSet('showAssignModal', true)
            ->set('assigneeName', 'Kamran Akmal')
            ->call('assignRequest')
            ->assertSet('showAssignModal', false)
            ->assertSee('Request assigned to Kamran Akmal');

        $this->assertDatabaseHas('offline_requests', [
            'id' => $req->id,
            'assigned_user' => 'Kamran Akmal',
        ]);
    }
}
