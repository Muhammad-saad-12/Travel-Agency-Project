<?php

namespace App\Models;

use Database\Factories\DepositTransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DepositTransaction extends Model
{
    /** @use HasFactory<DepositTransactionFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference',
        'agency_node_id',
        'agency_code',
        'agency_name',
        'bank',
        'type',
        'amount',
        'balance_after',
        'status',
        'proof_file',
        'verified_by',
        'transaction_date',
        'notes',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'Bank Deposit Slip',
        'status' => 'Verified',
        'amount' => 0,
        'balance_after' => 0,
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
            'balance_after' => 'decimal:2',
            'transaction_date' => 'datetime',
        ];
    }

    /**
     * Auto-generate reference on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (DepositTransaction $txn): void {
            if (empty($txn->reference)) {
                $txn->reference = self::generateReference();
            }
        });
    }

    /**
     * Generate reference e.g. TXN-DEP-9912.
     */
    public static function generateReference(): string
    {
        do {
            $ref = 'TXN-DEP-'.strtoupper(Str::random(4));
        } while (self::query()->where('reference', $ref)->exists());

        return $ref;
    }

    /**
     * Related agency node.
     */
    public function agencyNode(): BelongsTo
    {
        return $this->belongsTo(AgencyNode::class);
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
        return $query->where('status', 'Pending Approval');
    }
}
