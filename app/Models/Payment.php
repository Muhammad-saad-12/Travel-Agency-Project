<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_ref',
        'booking_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'channel',
        'channel_category',
        'amount',
        'fee_amount',
        'net_amount',
        'currency',
        'status',
        'gateway_ref',
        'proof_file',
        'payment_date',
        'verified_at',
        'verified_by',
        'notes',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'currency' => 'PKR',
        'status' => 'Pending',
        'channel' => 'Raast P2P/P2M',
        'channel_category' => 'raast',
        'amount' => 0,
        'fee_amount' => 0,
        'net_amount' => 0,
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Auto-generate reference on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Payment $payment): void {
            if (empty($payment->transaction_ref)) {
                $payment->transaction_ref = self::generateReference($payment->channel_category);
            }

            if (empty($payment->net_amount) || (float) $payment->net_amount <= 0) {
                $payment->net_amount = max(0, (float) $payment->amount - (float) $payment->fee_amount);
            }

            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
        });
    }

    /**
     * Generate reference e.g. TXN-RAAST-9821.
     */
    public static function generateReference(string $category = 'pay'): string
    {
        $prefix = match (strtolower($category)) {
            'raast' => 'TXN-RAAST-',
            '1link' => 'TXN-1LINK-',
            'wallet', 'mobile_wallet' => 'TXN-WAL-',
            'card' => 'TXN-CARD-',
            default => 'TXN-PAY-',
        };

        do {
            $ref = $prefix.strtoupper(Str::random(5));
        } while (self::query()->where('transaction_ref', $ref)->exists());

        return $ref;
    }

    /**
     * Associated booking record.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_reference');
    }

    /**
     * Scopes.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('status', 'Verified');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'Pending');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'Failed');
    }

    public function scopeRefunded(Builder $query): Builder
    {
        return $query->where('status', 'Refunded');
    }

    public function scopeRaast(Builder $query): Builder
    {
        return $query->where('channel_category', 'raast');
    }

    public function scopeOneLink(Builder $query): Builder
    {
        return $query->where('channel_category', '1link');
    }

    public function scopeWallets(Builder $query): Builder
    {
        return $query->whereIn('channel_category', ['wallet', 'mobile_wallet']);
    }

    public function scopeCards(Builder $query): Builder
    {
        return $query->where('channel_category', 'card');
    }
}
