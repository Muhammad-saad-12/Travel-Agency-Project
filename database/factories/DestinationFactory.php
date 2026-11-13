<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->city().' Valley';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'country' => 'Pakistan',
            'city' => fake()->city(),
            'type' => 'domestic',
            'region' => 'Northern Pakistan',
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }

    /**
     * State for international destination.
     */
    public function international(string $country = 'Saudi Arabia', string $region = 'Middle East'): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
            'type' => 'international',
            'region' => $region,
        ]);
    }
}
