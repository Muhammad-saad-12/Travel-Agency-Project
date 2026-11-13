<?php

namespace Database\Factories;

use App\Models\OfflineRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<OfflineRequest>
 */
class OfflineRequestFactory extends Factory
{
    protected $model = OfflineRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airlines = ['PIA (PK)', 'Emirates (EK)', 'Saudia (SV)', 'Airblue (PA)', 'Fly Jinnah (9P)', 'AirSial (PF)'];

        return [
            'booking_ref' => 'BOS-RQ-'.strtoupper(Str::random(4)),
            'pnr' => strtoupper(Str::random(6)),
            'airline' => fake()->randomElement($airlines),
            'route' => 'ISB - '.fake()->randomElement(['JED', 'DXB', 'KHI', 'LHE']),
            'passenger_name' => fake()->name(),
            'ticket_number' => '214-'.fake()->numerify('##########'),
            'travel_date' => fake()->dateTimeBetween('+2 days', '+1 month')->format('Y-m-d'),
            'request_type' => fake()->randomElement(['ticketing', 'refund', 'void', 'schedule_change']),
            'status' => fake()->randomElement(['Applied', 'In Progress', 'Processed']),
            'is_urgent' => fake()->boolean(25),
            'assigned_user' => fake()->name(),
            'penalty_amount' => 5000,
            'refund_amount' => 85000,
            'remarks' => fake()->sentence(),
            'requested_at' => now()->subHours(fake()->numberBetween(1, 48)),
        ];
    }
}
