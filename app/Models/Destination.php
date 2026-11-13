<?php

namespace App\Models;

use Database\Factories\DestinationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Destination extends Model
{
    /** @use HasFactory<DestinationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'country',
        'city',
        'type',
        'region',
        'description',
        'is_active',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'domestic',
        'is_active' => true,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Auto-generate slug on boot if not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (Destination $destination): void {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    /**
     * Hotels located in this destination.
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Tour packages available for this destination.
     */
    public function tourPackages(): HasMany
    {
        return $this->hasMany(TourPackage::class);
    }

    /**
     * Scope for active destinations.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for domestic destinations.
     */
    public function scopeDomestic(Builder $query): Builder
    {
        return $query->where('type', 'domestic');
    }

    /**
     * Scope for international destinations.
     */
    public function scopeInternational(Builder $query): Builder
    {
        return $query->where('type', 'international');
    }
}
