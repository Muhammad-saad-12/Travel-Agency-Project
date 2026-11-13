<?php

namespace App\Models;

use Database\Factories\HotelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    /** @use HasFactory<HotelFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'destination_id',
        'name',
        'star_rating',
        'room_types',
        'location_address',
        'nightly_rate_pkr',
        'total_rooms',
        'available_rooms',
        'is_partner',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'star_rating' => 3,
        'nightly_rate_pkr' => 0,
        'total_rooms' => 1,
        'available_rooms' => 1,
        'is_partner' => false,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'destination_id' => 'integer',
            'star_rating' => 'integer',
            'room_types' => 'array',
            'nightly_rate_pkr' => 'decimal:2',
            'total_rooms' => 'integer',
            'available_rooms' => 'integer',
            'is_partner' => 'boolean',
        ];
    }

    /**
     * Destination where this hotel is located.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Scope for official partner hotels.
     */
    public function scopePartners(Builder $query): Builder
    {
        return $query->where('is_partner', true);
    }

    /**
     * Scope for hotels with available inventory.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('available_rooms', '>', 0);
    }
}
