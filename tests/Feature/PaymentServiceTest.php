<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PaymentService::class);
    }

    public function test_can_create_payment_and_calculate_net_amount(): void
    {
        $booking = Booking::factory()->create([
            'booking_reference' => 'PK-BK-TEST',
            'payment_status' => 'Unpaid',
        ]);

        $payment = $this->service->createPayment([
            'booking_id' => $booking->booking_reference,
            'customer_name' => 'Adnan Siddiqui',
            'customer_phone' => '+92 300 7766554',
            'channel' => 'JazzCash Mobile Wallet',
            'channel_category' => 'wallet',
            'amount' => 50000,
            'fee_amount' => 750,
            'status' => 'Verified',
            'gateway_ref' => 'JC-REF-1122',
        ]);

        $this->assertNotEmpty($payment->transaction_ref);
        $this->assertStringStartsWith('TXN-WAL-', $payment->transaction_ref);
        $this->assertEquals(49250, (float) $payment->net_amount);
        $this->assertEquals('Verified', $payment->status);

        // Booking payment status should be synced to Paid
        $this->assertEquals('Paid', $booking->fresh()->payment_status);
    }

    public function test_can_filter_payments_by_channel_and_status(): void
    {
        Payment::factory()->create([
            'transaction_ref' => 'TXN-RAAST-001',
            'channel_category' => 'raast',
            'status' => 'Verified',
            'customer_name' => 'Raast User',
        ]);

        Payment::factory()->create([
            'transaction_ref' => 'TXN-CARD-002',
            'channel_category' => 'card',
            'status' => 'Pending',
            'customer_name' => 'Card User',
        ]);

        $raast = $this->service->listPayments(['channel_category' => 'raast']);
        $this->assertEquals(1, $raast->total());
        $this->assertEquals('TXN-RAAST-001', $raast->items()[0]->transaction_ref);

        $pending = $this->service->listPayments(['status' => 'Pending']);
        $this->assertEquals(1, $pending->total());
        $this->assertEquals('TXN-CARD-002', $pending->items()[0]->transaction_ref);

        $search = $this->service->listPayments(['search' => 'Raast User']);
        $this->assertEquals(1, $search->total());
    }

    public function test_can_verify_pending_payment_and_sync_booking(): void
    {
        $booking = Booking::factory()->create([
            'booking_reference' => 'PK-BK-SYNC',
            'payment_status' => 'Unpaid',
        ]);

        $payment = Payment::factory()->create([
            'booking_id' => $booking->booking_reference,
            'status' => 'Pending',
            'verified_at' => null,
        ]);

        $verified = $this->service->verifyPayment($payment, 'Chief Cashier', 'Reconciled with bank statement');

        $this->assertEquals('Verified', $verified->status);
        $this->assertEquals('Chief Cashier', $verified->verified_by);
        $this->assertNotNull($verified->verified_at);

        $this->assertEquals('Paid', $booking->fresh()->payment_status);
    }

    public function test_can_mark_payment_failed(): void
    {
        $payment = Payment::factory()->create(['status' => 'Pending']);

        $failed = $this->service->markFailed($payment, 'Card declined by issuing bank (Insufficient funds)');

        $this->assertEquals('Failed', $failed->status);
        $this->assertStringContainsString('Card declined', $failed->notes);
    }

    public function test_can_refund_payment_and_sync_booking(): void
    {
        $booking = Booking::factory()->create([
            'booking_reference' => 'PK-BK-RFND',
            'payment_status' => 'Paid',
        ]);

        $payment = Payment::factory()->create([
            'booking_id' => $booking->booking_reference,
            'amount' => 80000,
            'status' => 'Verified',
        ]);

        $refunded = $this->service->refundPayment($payment, 80000, 'Customer Care Desk', 'Flight sector cancelled by airline');

        $this->assertEquals('Refunded', $refunded->status);
        $this->assertEquals('Refunded', $booking->fresh()->payment_status);
    }

    public function test_can_compute_channel_statistics(): void
    {
        Payment::factory()->create(['channel_category' => 'raast', 'status' => 'Verified', 'amount' => 70000]);
        Payment::factory()->create(['channel_category' => '1link', 'status' => 'Verified', 'amount' => 120000]);
        Payment::factory()->create(['channel_category' => 'wallet', 'status' => 'Pending', 'amount' => 30000]);

        $stats = $this->service->getChannelStatistics();

        $this->assertEquals(3, $stats['total_transactions']);
        $this->assertEquals(2, $stats['verified_count']);
        $this->assertEquals(1, $stats['pending_count']);
        $this->assertEquals(70000, $stats['raast_volume_pkr']);
        $this->assertEquals(120000, $stats['onelink_volume_pkr']);
        $this->assertEquals(30000, $stats['total_pending_volume_pkr']);
    }

    public function test_can_export_payments_to_csv(): void
    {
        Payment::factory()->create([
            'transaction_ref' => 'TXN-CSV-001',
            'customer_name' => 'CSV Payer',
        ]);

        $response = $this->service->exportCsv();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
