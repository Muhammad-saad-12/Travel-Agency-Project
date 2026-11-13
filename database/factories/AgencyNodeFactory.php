<?php

namespace Database\Factories;

use App\Models\AgencyNode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgencyNode>
 */
class AgencyNodeFactory extends Factory
{
    protected $model = AgencyNode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = fake()->randomElement(['Islamabad', 'Lahore', 'Karachi', 'Peshawar', 'Rawalpindi']);

        return [
            'code' => 'CLI-'.fake()->unique()->numberBetween(20000, 29999),
            'name' => $city.' '.fake()->randomElement(['Head Office', 'Branch', 'Sub-Agency', 'Aviation Hub']),
            'type' => fake()->randomElement(['Branch', 'Sub-Agent']),
            'city' => $city,
            'country' => 'PK',
            'credit_limit' => fake()->randomElement([5000000, 10000000, 25000000]),
            'balance' => fake()->randomElement([500000, 1500000, 3200000]),
            'currency' => 'PKR',
            'contact_person' => fake()->name(),
            'phone' => '+92 '.fake()->numerify('3## #######'),
            'email' => fake()->safeEmail(),
            'status' => 'Active',
        ];
    }
}
