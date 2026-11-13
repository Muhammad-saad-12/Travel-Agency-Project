<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airlines = ['PIA (PK)', 'Emirates (EK)', 'Saudia (SV)', 'Airblue (PA)', 'Fly Jinnah (9P)', 'AirSial (PF)', 'Qatar Airways (QR)'];
        $airline = fake()->randomElement($airlines);
        $fare = fake()->numberBetween(65000, 350000);
        $tax = (int) ($fare * 0.15);
        $total = $fare + $tax;

        return [
            'booking_reference' => 'PK-BK-'.strtoupper(Str::random(4)),
            'pnr' => strtoupper(Str::random(6)),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '+92 '.fake()->numerify('3## #######'),
            'service' => 'Flight Ticket',
            'type' => 'flight',
            'destination' => fake()->randomElement(['Jeddah', 'Dubai', 'Baku', 'Skardu', 'Karachi']),
            'route' => 'ISB - '.fake()->randomElement(['JED', 'DXB', 'GYD', 'KDU', 'KHI']),
            'airline' => $airline,
            'flight_number' => strtoupper(Str::random(2)).' '.fake()->numberBetween(100, 999),
            'travel_date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'return_date' => fake()->dateTimeBetween('+3 months', '+4 months')->format('Y-m-d'),
            'passengers_count' => fake()->numberBetween(1, 4),
            'fare_amount' => $fare,
            'tax_amount' => $tax,
            'ancillaries_amount' => 0,
            'markup_amount' => 5000,
            'total_amount' => $total + 5000,
            'currency' => 'PKR',
            'status' => fake()->randomElement(['Confirmed', 'Pending', 'Ticketed']),
            'payment_status' => 'Paid',
            'supplier' => fake()->randomElement(['Amadeus GDS (1A)', 'Sabre (1S)', 'Galileo (1G)']),
            'ticket_ref' => '214-'.fake()->numerify('##########'),
            'time_limit' => now()->addDays(3),
            'passengers_data' => [
                ['name' => fake()->name(), 'type' => 'ADT', 'passport' => 'PK'.fake()->numerify('#######')],
            ],
            'segments_data' => [
                ['flight' => 'PK 741', 'from' => 'ISB', 'to' => 'JED', 'date' => now()->addDays(10)->format('Y-m-d')],
            ],
            'remarks' => fake()->sentence(),
        ];
    }
}
