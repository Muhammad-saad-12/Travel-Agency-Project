<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hotel>
 */
class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalRooms = fake()->numberBetween(20, 150);

        return [
            'destination_id' => Destination::factory(),
            'name' => fake()->company().' Grand Hotel',
            'star_rating' => fake()->numberBetween(3, 5),
            'room_types' => ['Standard Room', 'Deluxe Double', 'Executive Suite'],
            'location_address' => fake()->streetAddress().', '.fake()->city(),
            'nightly_rate_pkr' => fake()->randomElement([15000, 22000, 35000, 50000, 75000]),
            'total_rooms' => $totalRooms,
            'available_rooms' => fake()->numberBetween(5, $totalRooms),
            'is_partner' => fake()->boolean(60),
        ];
    }
}
