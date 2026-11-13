<?php

namespace Database\Factories;

use App\Models\FlightInquiry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FlightInquiry>
 */
class FlightInquiryFactory extends Factory
{
    protected $model = FlightInquiry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origins = ['Islamabad (ISB)', 'Lahore (LHE)', 'Karachi (KHI)', 'Peshawar (PEW)', 'Multan (MUX)'];
        $destinations = ['Jeddah (JED)', 'Madinah (MED)', 'Dubai (DXB)', 'Baku (GYD)', 'Istanbul (IST)', 'Skardu (KDU)', 'Gilgit (GIL)'];

        $origin = fake()->randomElement($origins);
        $dest = fake()->randomElement($destinations);

        $depDate = fake()->dateTimeBetween('+3 days', '+2 months');
        $retDate = (clone $depDate)->modify('+7 days');

        return [
            'reference_no' => 'INQ-2026-'.strtoupper(Str::random(4)),
            'trip_type' => fake()->randomElement(['one_way', 'round_trip']),
            'origin' => $origin,
            'destination' => $dest,
            'departure_date' => $depDate->format('Y-m-d'),
            'return_date' => $retDate->format('Y-m-d'),
            'multi_city_segments' => null,
            'adults' => fake()->numberBetween(1, 4),
            'children' => fake()->numberBetween(0, 2),
            'infants' => fake()->numberBetween(0, 1),
            'cabin_class' => fake()->randomElement(['Economy', 'Premium Economy', 'Business']),
            'customer_name' => fake()->name(),
            'customer_phone' => '+92 '.fake()->numerify('3## #######'),
            'customer_email' => fake()->safeEmail(),
            'notes' => fake()->optional()->sentence(),
            'quoted_price_pkr' => fake()->optional(0.6)->randomElement([145000, 210000, 320000, 480000]),
            'status' => fake()->randomElement(['Pending', 'Contacted', 'Quoted', 'Booked']),
        ];
    }

    /**
     * State for multi-city inquiry.
     */
    public function multiCity(): static
    {
        return $this->state(fn (array $attributes) => [
            'trip_type' => 'multi_city',
            'return_date' => null,
            'multi_city_segments' => [
                ['origin' => 'Islamabad (ISB)', 'destination' => 'Dubai (DXB)', 'date' => now()->addDays(5)->format('Y-m-d')],
                ['origin' => 'Dubai (DXB)', 'destination' => 'Baku (GYD)', 'date' => now()->addDays(10)->format('Y-m-d')],
                ['origin' => 'Baku (GYD)', 'destination' => 'Islamabad (ISB)', 'date' => now()->addDays(16)->format('Y-m-d')],
            ],
        ]);
    }

    /**
     * State for pending status.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
            'quoted_price_pkr' => null,
        ]);
    }
}
