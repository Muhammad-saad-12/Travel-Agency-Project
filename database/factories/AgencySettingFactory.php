<?php

namespace Database\Factories;

use App\Models\AgencySetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgencySetting>
 */
class AgencySettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<AgencySetting>
     */
    protected $model = AgencySetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => fake()->company().' Travel & Tours',
            'dts_license' => 'DTS-'.fake()->numerify('####'),
            'ntn' => fake()->numerify('#######-#'),
            'strn' => fake()->numerify('#############'),
            'phone' => '+92 '.fake()->numerify('3## #######'),
            'email' => fake()->companyEmail(),
            'address' => fake()->streetAddress().', '.fake()->city().', Pakistan',
            'whatsapp_number' => '+92 '.fake()->numerify('3## #######'),
            'default_currency' => 'PKR',
        ];
    }
}
