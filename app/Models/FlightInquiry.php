<?php

namespace App\Models;

use Database\Factories\FlightInquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FlightInquiry extends Model
{
    /** @use HasFactory<FlightInquiryFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference_no',
        'trip_type',
        'origin',
        'destination',
        'departure_date',
        'return_date',
        'multi_city_segments',
        'adults',
        'children',
        'infants',
        'cabin_class',
        'customer_name',
        'customer_phone',
        'customer_email',
        'notes',
        'quoted_price_pkr',
        'status',
    ];

    /**
     * Default model attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'trip_type' => 'round_trip',
        'adults' => 1,
        'children' => 0,
        'infants' => 0,
        'cabin_class' => 'Economy',
        'status' => 'Pending',
    ];

    /**
     * Get attribute casting definitions.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'multi_city_segments' => 'array',
            'adults' => 'integer',
            'children' => 'integer',
            'infants' => 'integer',
            'quoted_price_pkr' => 'decimal:2',
        ];
    }

    /**
     * Auto-generate reference number if not set.
     */
    protected static function booted(): void
    {
        static::creating(function (FlightInquiry $inquiry): void {
            if (empty($inquiry->reference_no)) {
                $inquiry->reference_no = self::generateReferenceNo();
            }
        });
    }

    /**
     * Generate unique reference number like INQ-2026-XXXX.
     */
    public static function generateReferenceNo(): string
    {
        $year = now()->format('Y');

        do {
            $reference = sprintf('INQ-%s-%s', $year, strtoupper(Str::random(4)));
        } while (self::query()->where('reference_no', $reference)->exists());

        return $reference;
    }

    /**
     * Accessor for total passenger count.
     */
    public function getTotalPassengersAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }

    /**
     * Scopes for status filtering.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'Pending');
    }

    public function scopeContacted(Builder $query): Builder
    {
        return $query->where('status', 'Contacted');
    }

    public function scopeQuoted(Builder $query): Builder
    {
        return $query->where('status', 'Quoted');
    }

    public function scopeBooked(Builder $query): Builder
    {
        return $query->where('status', 'Booked');
    }
}
