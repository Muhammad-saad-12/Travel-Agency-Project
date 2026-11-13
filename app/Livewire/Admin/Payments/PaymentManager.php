<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Payment Management (Module 14)')]
class PaymentManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $channelCategory = 'all';

    #[Url]
    public string $status = 'all';

    public string $date_from = '';

    public string $date_to = '';

    // Modals
    public bool $showCreateModal = false;

    public bool $showVerifyModal = false;

    public bool $showRefundModal = false;

    public bool $showDetailModal = false;

    public ?int $selectedPaymentId = null;

    // Create form fields
    public string $newBookingId = '';

    public string $newCustomerName = '';

    public string $newCustomerPhone = '';

    public string $newCustomerEmail = '';

    public string $newChannel = 'Raast P2P/P2M';

    public string $newChannelCategory = 'raast';

    public float|int|string $newAmount = '';

    public float|int|string $newFeeAmount = 0;

    public string $newGatewayRef = '';

    public string $newProofFile = '';

    public string $newNotes = '';

    public bool $newAutoVerify = true;

    // Verify form fields
    public string $verifierNotes = '';

    // Refund form fields
    public float|int|string $refundAmount = '';

    public string $refundReason = '';

    // Feedback
    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingChannelCategory(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->channelCategory = 'all';
        $this->status = 'all';
        $this->date_from = '';
        $this->date_to = '';
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->newBookingId = '';
        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->newCustomerEmail = '';
        $this->newChannel = 'Raast P2P/P2M';
        $this->newChannelCategory = 'raast';
        $this->newAmount = '';
        $this->newFeeAmount = 0;
        $this->newGatewayRef = '';
        $this->newProofFile = '';
        $this->newNotes = '';
        $this->newAutoVerify = true;
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->newAmount = '';
    }

    public function onChannelChange(): void
    {
        $map = [
            'Raast P2P/P2M' => ['cat' => 'raast', 'fee' => 0],
            '1Link Bank Transfer' => ['cat' => '1link', 'fee' => 0],
            'JazzCash Mobile Wallet' => ['cat' => 'wallet', 'fee' => 0.015],
            'EasyPaisa Digital Wallet' => ['cat' => 'wallet', 'fee' => 0.015],
            'Credit/Debit Card (Visa/Mastercard)' => ['cat' => 'card', 'fee' => 0.025],
            'Bank Deposit Slip' => ['cat' => 'bank', 'fee' => 0],
        ];

        if (isset($map[$this->newChannel])) {
            $this->newChannelCategory = $map[$this->newChannel]['cat'];
            if (! empty($this->newAmount) && is_numeric($this->newAmount)) {
                $this->newFeeAmount = round((float) $this->newAmount * $map[$this->newChannel]['fee'], 2);
            }
        }
    }

    public function onBookingSelect(): void
    {
        if (! empty($this->newBookingId)) {
            $booking = Booking::where('booking_reference', $this->newBookingId)->first();
            if ($booking) {
                $this->newCustomerName = $booking->customer_name;
                $this->newCustomerPhone = $booking->customer_phone ?? '';
                $this->newCustomerEmail = $booking->customer_email ?? '';
                $this->newAmount = (float) $booking->total_amount;
                $this->onChannelChange();
            }
        }
    }

    public function recordPayment(PaymentService $service): void
    {
        $this->validate([
            'newCustomerName' => ['required', 'string', 'min:3', 'max:100'],
            'newChannel' => ['required', 'string'],
            'newAmount' => ['required', 'numeric', 'min:1'],
            'newFeeAmount' => ['nullable', 'numeric', 'min:0'],
            'newBookingId' => ['nullable', 'string', 'max:50'],
            'newGatewayRef' => ['nullable', 'string', 'max:100'],
            'newNotes' => ['nullable', 'string', 'max:500'],
        ]);

        $data = [
            'booking_id' => $this->newBookingId ?: null,
            'customer_name' => $this->newCustomerName,
            'customer_phone' => $this->newCustomerPhone ?: null,
            'customer_email' => $this->newCustomerEmail ?: null,
            'channel' => $this->newChannel,
            'channel_category' => $this->newChannelCategory,
            'amount' => (float) $this->newAmount,
            'fee_amount' => (float) $this->newFeeAmount,
            'net_amount' => max(0, (float) $this->newAmount - (float) $this->newFeeAmount),
            'currency' => 'PKR',
            'status' => $this->newAutoVerify ? 'Verified' : 'Pending',
            'gateway_ref' => $this->newGatewayRef ?: null,
            'proof_file' => $this->newProofFile ?: null,
            'verified_by' => $this->newAutoVerify ? (auth()->user()->name ?? 'Settlement Desk') : null,
            'notes' => $this->newNotes ?: 'Payment received via '.$this->newChannel,
        ];

        $payment = $service->createPayment($data);

        $this->successMessage = "Payment #{$payment->transaction_ref} recorded successfully for PKR ".number_format((float) $payment->amount, 2).($this->newAutoVerify ? ' (Verified & Booking updated)' : ' (Pending verification)').'!';
        $this->closeCreateModal();
    }

    public function openVerifyModal(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);
        $this->selectedPaymentId = $payment->id;
        $this->verifierNotes = $payment->notes ?? '';
        $this->showVerifyModal = true;
    }

    public function closeVerifyModal(): void
    {
        $this->showVerifyModal = false;
        $this->selectedPaymentId = null;
        $this->verifierNotes = '';
    }

    public function verifyPayment(PaymentService $service): void
    {
        if (! $this->selectedPaymentId) {
            return;
        }

        $payment = Payment::findOrFail($this->selectedPaymentId);
        $service->verifyPayment($payment, auth()->user()->name ?? 'Accounts Officer', $this->verifierNotes);

        $this->successMessage = "Payment #{$payment->transaction_ref} has been verified and settled!";
        $this->closeVerifyModal();
    }

    public function markFailed(int $paymentId, PaymentService $service): void
    {
        $payment = Payment::findOrFail($paymentId);
        $service->markFailed($payment, 'Transaction declined by issuer or invalid settlement proof');

        $this->errorMessage = "Payment #{$payment->transaction_ref} marked as Failed.";
    }

    public function openRefundModal(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);
        $this->selectedPaymentId = $payment->id;
        $this->refundAmount = (float) $payment->amount;
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->selectedPaymentId = null;
        $this->refundAmount = '';
        $this->refundReason = '';
    }

    public function processRefund(PaymentService $service): void
    {
        $this->validate([
            'refundAmount' => ['required', 'numeric', 'min:1'],
            'refundReason' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->selectedPaymentId) {
            return;
        }

        $payment = Payment::findOrFail($this->selectedPaymentId);
        $service->refundPayment($payment, (float) $this->refundAmount, auth()->user()->name ?? 'Refund Desk', $this->refundReason);

        $this->successMessage = "Payment #{$payment->transaction_ref} refunded for PKR ".number_format((float) $this->refundAmount, 2);
        $this->closeRefundModal();
    }

    public function openDetailModal(int $paymentId): void
    {
        $this->selectedPaymentId = $paymentId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedPaymentId = null;
    }

    public function exportCsv(PaymentService $service): StreamedResponse
    {
        $filters = [
            'search' => $this->search,
            'channel_category' => $this->channelCategory,
            'status' => $this->status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        return $service->exportCsv($filters);
    }

    public function render(PaymentService $service)
    {
        $filters = [
            'search' => $this->search,
            'channel_category' => $this->channelCategory,
            'status' => $this->status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        $payments = $service->listPayments($filters, 12);
        $statistics = $service->getChannelStatistics();
        $recentBookings = Booking::latest()->take(10)->get();
        $selectedPayment = $this->selectedPaymentId ? Payment::find($this->selectedPaymentId) : null;

        return view('livewire.admin.payments.payment-manager', [
            'payments' => $payments,
            'statistics' => $statistics,
            'recentBookings' => $recentBookings,
            'selectedPayment' => $selectedPayment,
        ]);
    }
}
