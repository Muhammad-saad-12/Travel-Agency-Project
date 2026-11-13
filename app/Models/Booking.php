<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_reference',
        'pnr',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service',
        'type',
        'destination',
        'route',
        'airline',
        'flight_number',
        'travel_date',
        'return_date',
        'passengers_count',
        'fare_amount',
        'tax_amount',
        'ancillaries_amount',
        'markup_amount',
        'total_amount',
        'currency',
        'status',
        'payment_status',
        'supplier',
        'ticket_ref',
        'time_limit',
        'passengers_data',
        'segments_data',
        'ancillaries_data',
        'remarks',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'service' => 'Flight Ticket',
        'type' => 'flight',
        'passengers_count' => 1,
        'fare_amount' => 0,
        'tax_amount' => 0,
        'ancillaries_amount' => 0,
        'markup_amount' => 0,
        'total_amount' => 0,
        'currency' => 'PKR',
        'status' => 'Confirmed',
        'payment_status' => 'Paid',
        'supplier' => 'Amadeus GDS (1A)',
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
            'return_date' => 'date',
            'time_limit' => 'datetime',
            'passengers_count' => 'integer',
            'fare_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'ancillaries_amount' => 'decimal:2',
            'markup_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'passengers_data' => 'array',
            'segments_data' => 'array',
            'ancillaries_data' => 'array',
        ];
    }

    /**
     * Auto-generate booking reference on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateReference();
            }
        });
    }

    /**
     * Generate reference e.g. PK-BK-8901.
     */
    public static function generateReference(): string
    {
        do {
            $ref = 'PK-BK-'.strtoupper(Str::random(4));
        } while (self::query()->where('booking_reference', $ref)->exists());

        return $ref;
    }

    /**
     * Type Scopes.
     */
    public function scopeFlight(Builder $query): Builder
    {
        return $query->where('type', 'flight');
    }

    public function scopeFlights(Builder $query): Builder
    {
        return $query->where('type', 'flight');
    }

    public function scopeHotel(Builder $query): Builder
    {
        return $query->where('type', 'hotel');
    }

    public function scopeHotels(Builder $query): Builder
    {
        return $query->where('type', 'hotel');
    }

    public function scopeUmrah(Builder $query): Builder
    {
        return $query->where('type', 'umrah');
    }

    public function scopePackages(Builder $query): Builder
    {
        return $query->where('type', 'package');
    }

    public function scopeGroup(Builder $query): Builder
    {
        return $query->where('type', 'group');
    }
}
