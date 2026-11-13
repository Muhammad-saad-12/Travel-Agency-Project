<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentService
{
    /**
     * Retrieve paginated payments with search and filtering.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listPayments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(Payment::query(), $filters)
            ->latest('payment_date')
            ->paginate($perPage);
    }

    /**
     * Apply filter criteria to payment query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $sub) use ($search): void {
                $sub->where('transaction_ref', 'like', "%{$search}%")
                    ->orWhere('booking_id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('gateway_ref', 'like', "%{$search}%")
                    ->orWhere('channel', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['channel_category']) && $filters['channel_category'] !== 'all') {
            $query->where('channel_category', $filters['channel_category']);
        }

        if (! empty($filters['channel']) && $filters['channel'] !== 'all') {
            $query->where('channel', $filters['channel']);
        }

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['booking_id'])) {
            $query->where('booking_id', $filters['booking_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('payment_date', '>=', Carbon::parse($filters['date_from'])->toDateString());
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('payment_date', '<=', Carbon::parse($filters['date_to'])->toDateString());
        }

        return $query;
    }

    /**
     * Create and record a new payment transaction.
     *
     * @param  array<string, mixed>  $data
     */
    public function createPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data): Payment {
            $amount = (float) ($data['amount'] ?? 0);
            $fee = (float) ($data['fee_amount'] ?? 0);
            if (! isset($data['net_amount']) || (float) $data['net_amount'] <= 0) {
                $data['net_amount'] = max(0, $amount - $fee);
            }

            if (empty($data['payment_date'])) {
                $data['payment_date'] = Carbon::now();
            }

            $isVerified = ($data['status'] ?? 'Pending') === 'Verified';
            if ($isVerified) {
                $data['verified_at'] = Carbon::now();
                if (empty($data['verified_by'])) {
                    $data['verified_by'] = 'Auto System Verification';
                }
            }

            $payment = Payment::create($data);

            // Sync corresponding booking payment status if verified
            if ($isVerified && ! empty($payment->booking_id)) {
                $this->syncBookingPaymentStatus($payment->booking_id, 'Paid');
            }

            return $payment;
        });
    }

    /**
     * Verify a pending payment and update booking payment status.
     */
    public function verifyPayment(Payment $payment, string $verifiedBy, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($payment, $verifiedBy, $notes): Payment {
            $updateData = [
                'status' => 'Verified',
                'verified_at' => Carbon::now(),
                'verified_by' => $verifiedBy,
            ];

            if ($notes !== null) {
                $updateData['notes'] = $notes;
            }

            $payment->update($updateData);

            if (! empty($payment->booking_id)) {
                $this->syncBookingPaymentStatus($payment->booking_id, 'Paid');
            }

            return $payment->fresh();
        });
    }

    /**
     * Mark a payment as Failed.
     */
    public function markFailed(Payment $payment, string $reason): Payment
    {
        $payment->update([
            'status' => 'Failed',
            'notes' => trim(($payment->notes ?? '')." [Failed: {$reason}]"),
        ]);

        return $payment->fresh();
    }

    /**
     * Process a refund on a payment.
     */
    public function refundPayment(Payment $payment, float $refundAmount, string $refundedBy, ?string $reason = null): Payment
    {
        return DB::transaction(function () use ($payment, $refundAmount, $refundedBy, $reason): Payment {
            $payment->update([
                'status' => 'Refunded',
                'verified_by' => $refundedBy,
                'notes' => trim(($payment->notes ?? '')." [Refunded PKR {$refundAmount}: {$reason}]"),
            ]);

            if (! empty($payment->booking_id)) {
                $this->syncBookingPaymentStatus($payment->booking_id, 'Refunded');
            }

            return $payment->fresh();
        });
    }

    /**
     * Sync booking payment status by reference.
     */
    private function syncBookingPaymentStatus(string $bookingRef, string $paymentStatus): void
    {
        Booking::query()
            ->where('booking_reference', $bookingRef)
            ->update(['payment_status' => $paymentStatus]);
    }

    /**
     * Compute payment statistics grouped by Pakistani payment channels and statuses.
     *
     * @return array<string, mixed>
     */
    public function getChannelStatistics(): array
    {
        return [
            'total_transactions' => Payment::count(),
            'verified_count' => Payment::verified()->count(),
            'pending_count' => Payment::pending()->count(),
            'failed_count' => Payment::failed()->count(),
            'refunded_count' => Payment::refunded()->count(),

            // PKR Volumes
            'total_verified_volume_pkr' => (float) Payment::verified()->sum('amount'),
            'total_pending_volume_pkr' => (float) Payment::pending()->sum('amount'),
            'total_net_volume_pkr' => (float) Payment::verified()->sum('net_amount'),
            'total_gateway_fees_pkr' => (float) Payment::verified()->sum('fee_amount'),

            // Channel breakdowns
            'raast_volume_pkr' => (float) Payment::raast()->verified()->sum('amount'),
            'raast_count' => Payment::raast()->count(),

            'onelink_volume_pkr' => (float) Payment::oneLink()->verified()->sum('amount'),
            'onelink_count' => Payment::oneLink()->count(),

            'wallet_volume_pkr' => (float) Payment::wallets()->verified()->sum('amount'),
            'wallet_count' => Payment::wallets()->count(),

            'card_volume_pkr' => (float) Payment::cards()->verified()->sum('amount'),
            'card_count' => Payment::cards()->count(),
        ];
    }

    /**
     * Export filtered payments as a downloadable CSV stream.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $fileName = 'payments-ledger-'.now()->format('Y-m-d-His').'.csv';

        $query = $this->applyFilters(Payment::query(), $filters)->latest('payment_date');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Transaction Ref',
                'Booking Ref',
                'Customer Name',
                'Customer Phone',
                'Customer Email',
                'Payment Channel',
                'Channel Category',
                'Gross Amount (PKR)',
                'Gateway Fee (PKR)',
                'Net Amount (PKR)',
                'Status',
                'Gateway / SBP Ref',
                'Payment Date',
                'Verified By',
                'Notes',
            ]);

            $query->chunk(100, function ($payments) use ($handle): void {
                foreach ($payments as $payment) {
                    fputcsv($handle, [
                        $payment->transaction_ref,
                        $payment->booking_id ?? 'Unlinked',
                        $payment->customer_name,
                        $payment->customer_phone ?? '',
                        $payment->customer_email ?? '',
                        $payment->channel,
                        ucfirst($payment->channel_category),
                        number_format((float) $payment->amount, 2, '.', ''),
                        number_format((float) $payment->fee_amount, 2, '.', ''),
                        number_format((float) $payment->net_amount, 2, '.', ''),
                        $payment->status,
                        $payment->gateway_ref ?? '',
                        $payment->payment_date ? $payment->payment_date->format('Y-m-d H:i') : '',
                        $payment->verified_by ?? 'N/A',
                        $payment->notes ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
