<?php

namespace App\Models;

use Database\Factories\OfflineRequestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineRequest extends Model
{
    /** @use HasFactory<OfflineRequestFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_ref',
        'pnr',
        'airline',
        'route',
        'passenger_name',
        'ticket_number',
        'travel_date',
        'request_type',
        'status',
        'is_urgent',
        'assigned_user',
        'penalty_amount',
        'refund_amount',
        'remarks',
        'requested_at',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'request_type' => 'ticketing',
        'status' => 'Applied',
        'is_urgent' => false,
        'penalty_amount' => 0,
        'refund_amount' => 0,
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'requested_at' => 'datetime',
            'is_urgent' => 'boolean',
            'penalty_amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
        ];
    }

    /**
     * Scopes.
     */
    public function scopeTicketing(Builder $query): Builder
    {
        return $query->where('request_type', 'ticketing');
    }

    public function scopeRefunds(Builder $query): Builder
    {
        return $query->where('request_type', 'refund');
    }

    public function scopeVoids(Builder $query): Builder
    {
        return $query->where('request_type', 'void');
    }

    public function scopeScheduleChanges(Builder $query): Builder
    {
        return $query->where('request_type', 'schedule_change');
    }

    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('is_urgent', true);
    }
}
