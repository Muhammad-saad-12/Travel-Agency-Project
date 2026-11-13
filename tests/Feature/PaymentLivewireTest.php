<?php

namespace Tests\Feature;

use App\Livewire\Admin\Payments\PaymentManager;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_payments_page_loads_successfully(): void
    {
        Payment::factory()->create([
            'transaction_ref' => 'TXN-RAAST-PAGE',
            'customer_name' => 'Kashif Mehmood',
            'amount' => 88000,
        ]);

        $response = $this->get('/admin/payments');

        $response->assertStatus(200);
        $response->assertSee('Payment Management');
        $response->assertSee('Module 14');
        $response->assertSee('TXN-RAAST-PAGE');
        $response->assertSee('Kashif Mehmood');
    }

    public function test_can_filter_payments_by_category_in_livewire(): void
    {
        Payment::factory()->create([
            'transaction_ref' => 'TXN-RAAST-FLT',
            'channel_category' => 'raast',
        ]);

        Payment::factory()->create([
            'transaction_ref' => 'TXN-CARD-FLT',
            'channel_category' => 'card',
        ]);

        Livewire::test(PaymentManager::class)
            ->set('channelCategory', 'raast')
            ->assertSee('TXN-RAAST-FLT')
            ->assertDontSee('TXN-CARD-FLT');
    }

    public function test_can_record_new_payment_via_livewire(): void
    {
        $booking = Booking::factory()->create([
            'booking_reference' => 'PK-BK-PAY1',
            'total_amount' => 65000,
            'payment_status' => 'Unpaid',
        ]);

        Livewire::test(PaymentManager::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('newBookingId', $booking->booking_reference)
            ->call('onBookingSelect')
            ->set('newCustomerName', 'Taimur Khan')
            ->set('newChannel', 'Raast P2P/P2M')
            ->set('newAmount', 65000)
            ->set('newAutoVerify', true)
            ->call('recordPayment')
            ->assertSet('showCreateModal', false)
            ->assertSee('recorded successfully');

        $this->assertDatabaseHas('payments', [
            'booking_id' => 'PK-BK-PAY1',
            'customer_name' => 'Taimur Khan',
            'amount' => 65000,
            'status' => 'Verified',
        ]);

        $this->assertEquals('Paid', $booking->fresh()->payment_status);
    }

    public function test_can_verify_pending_payment_via_livewire(): void
    {
        $payment = Payment::factory()->create([
            'status' => 'Pending',
        ]);

        Livewire::test(PaymentManager::class)
            ->call('openVerifyModal', $payment->id)
            ->assertSet('showVerifyModal', true)
            ->set('verifierNotes', 'Raast settlement verified via State Bank portal')
            ->call('verifyPayment')
            ->assertSet('showVerifyModal', false)
            ->assertSee('has been verified');

        $this->assertEquals('Verified', $payment->fresh()->status);
    }

    public function test_can_refund_payment_via_livewire(): void
    {
        $payment = Payment::factory()->create([
            'status' => 'Verified',
            'amount' => 75000,
        ]);

        Livewire::test(PaymentManager::class)
            ->call('openRefundModal', $payment->id)
            ->assertSet('showRefundModal', true)
            ->set('refundAmount', 75000)
            ->set('refundReason', 'Customer requested tour cancellation')
            ->call('processRefund')
            ->assertSet('showRefundModal', false)
            ->assertSee('refunded for PKR 75,000.00');

        $this->assertEquals('Refunded', $payment->fresh()->status);
    }

    public function test_can_trigger_payments_csv_export(): void
    {
        Payment::factory()->create();

        Livewire::test(PaymentManager::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
