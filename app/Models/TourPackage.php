<?php

namespace App\Models;

use Database\Factories\TourPackageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TourPackage extends Model
{
    /** @use HasFactory<TourPackageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'destination_id',
        'title',
        'slug',
        'duration_days',
        'price_pkr',
        'difficulty_rating',
        'is_umrah',
        'itinerary',
        'inclusions',
        'is_featured',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'duration_days' => 1,
        'price_pkr' => 0,
        'difficulty_rating' => 'Easy',
        'is_umrah' => false,
        'is_featured' => false,
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
            'duration_days' => 'integer',
            'price_pkr' => 'decimal:2',
            'is_umrah' => 'boolean',
            'itinerary' => 'array',
            'inclusions' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Auto-generate slug on boot if not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (TourPackage $package): void {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->title);
            }
        });
    }

    /**
     * Destination associated with this package.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Scope for Umrah religious packages.
     */
    public function scopeUmrah(Builder $query): Builder
    {
        return $query->where('is_umrah', true);
    }

    /**
     * Scope for featured packages.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
