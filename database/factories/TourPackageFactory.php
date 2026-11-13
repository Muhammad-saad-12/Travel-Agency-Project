<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TourPackage>
 */
class TourPackageFactory extends Factory
{
    protected $model = TourPackage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = fake()->numberBetween(5, 12);
        $title = $days.'-Day '.fake()->city().' Highlights';

        return [
            'destination_id' => Destination::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'duration_days' => $days,
            'price_pkr' => fake()->randomElement([85000, 125000, 165000, 220000, 310000]),
            'difficulty_rating' => fake()->randomElement(['Easy', 'Moderate', 'Challenging']),
            'is_umrah' => false,
            'itinerary' => [
                ['day' => 1, 'title' => 'Arrival & Hotel Check-in', 'details' => 'Pick up from airport and private transfer.'],
                ['day' => 2, 'title' => 'City Sightseeing & Heritage Walk', 'details' => 'Full-day guided exploration with lunch.'],
                ['day' => 3, 'title' => 'Mountain Excursion & Photography', 'details' => 'Scenic viewpoints and local bazaars.'],
            ],
            'inclusions' => [
                'Return Airfare Tickets',
                'Hotel Accommodation with Breakfast',
                'Private AC Transportation',
                'Professional Licensed Tour Guide',
            ],
            'is_featured' => fake()->boolean(40),
        ];
    }

    /**
     * State for featured packages.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * State for Umrah religious tour packages.
     */
    public function umrah(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => '14-Day Premium Umrah Package',
            'is_umrah' => true,
            'duration_days' => 14,
            'difficulty_rating' => 'Easy',
            'price_pkr' => 385000,
            'inclusions' => [
                'Direct Flight Tickets (PIA / Saudia)',
                '5-Star Clock Tower Makkah Hotel',
                '5-Star Madinah Central Hotel',
                'Umrah Visa & Health Insurance',
                'VIP Haramain High Speed Train Transfer',
                'Ziyarat in Makkah & Madinah with Scholar',
            ],
        ]);
    }
}
