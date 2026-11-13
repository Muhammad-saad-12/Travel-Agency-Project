<?php

namespace App\Models;

use Database\Factories\AgencyNodeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgencyNode extends Model
{
    /** @use HasFactory<AgencyNodeFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'city',
        'country',
        'credit_limit',
        'balance',
        'currency',
        'contact_person',
        'phone',
        'email',
        'status',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'Branch',
        'country' => 'PK',
        'credit_limit' => 0,
        'balance' => 0,
        'currency' => 'PKR',
        'status' => 'Active',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    /**
     * Deposit transactions for this agency node.
     */
    public function depositTransactions(): HasMany
    {
        return $this->hasMany(DepositTransaction::class);
    }

    /**
     * Scope for active agency nodes.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    /**
     * Calculate total available operating capital (credit limit + cash balance).
     */
    public function getAvailableLimitAttribute(): float
    {
        return (float) $this->credit_limit + (float) $this->balance;
    }
}
