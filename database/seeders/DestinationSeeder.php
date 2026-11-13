<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Hunza Valley',
                'slug' => 'hunza-valley',
                'country' => 'Pakistan',
                'city' => 'Karimabad',
                'type' => 'domestic',
                'region' => 'Northern Pakistan',
                'description' => 'Majestic mountainous paradise surrounded by Rakaposhi, Ultar Sar, and historical Baltit and Altit forts.',
                'is_active' => true,
            ],
            [
                'name' => 'Skardu Valley',
                'slug' => 'skardu-valley',
                'country' => 'Pakistan',
                'city' => 'Skardu',
                'type' => 'domestic',
                'region' => 'Northern Pakistan',
                'description' => 'The gateway to K2, featuring Shangrila Lake, Katpana Cold Desert, and pristine high-altitude Deosai Plains.',
                'is_active' => true,
            ],
            [
                'name' => 'Swat Valley',
                'slug' => 'swat-valley',
                'country' => 'Pakistan',
                'city' => 'Malam Jabba & Kalam',
                'type' => 'domestic',
                'region' => 'Northern Pakistan',
                'description' => 'Switzerland of the East with lush green alpine valleys, trout streams, and ski resorts at Malam Jabba.',
                'is_active' => true,
            ],
            [
                'name' => 'Makkah Al-Mukarramah',
                'slug' => 'makkah-al-mukarramah',
                'country' => 'Saudi Arabia',
                'city' => 'Makkah',
                'type' => 'international',
                'region' => 'Middle East',
                'description' => 'The holiest city in Islam, home to Masjid al-Haram, the Holy Kaaba, and the destination for Umrah & Hajj pilgrims.',
                'is_active' => true,
            ],
            [
                'name' => 'Madinah Al-Munawwarah',
                'slug' => 'madinah-al-munawwarah',
                'country' => 'Saudi Arabia',
                'city' => 'Madinah',
                'type' => 'international',
                'region' => 'Middle East',
                'description' => 'The illuminated sanctuary of the Prophet Muhammad (PBUH) with Al-Masjid an-Nabawi and sacred Ziyarat sites.',
                'is_active' => true,
            ],
            [
                'name' => 'Baku City',
                'slug' => 'baku-city',
                'country' => 'Azerbaijan',
                'city' => 'Baku',
                'type' => 'international',
                'region' => 'Central Asia',
                'description' => 'The City of Winds blending futuristic architecture like the Flame Towers with ancient UNESCO Old City streets on the Caspian Sea.',
                'is_active' => true,
            ],
            [
                'name' => 'Dubai',
                'slug' => 'dubai',
                'country' => 'United Arab Emirates',
                'city' => 'Dubai',
                'type' => 'international',
                'region' => 'Middle East',
                'description' => 'Global commercial and leisure capital with Burj Khalifa, Desert Safari experiences, and premium shopping festivals.',
                'is_active' => true,
            ],
            [
                'name' => 'Istanbul',
                'slug' => 'istanbul',
                'country' => 'Turkey',
                'city' => 'Istanbul',
                'type' => 'international',
                'region' => 'Europe',
                'description' => 'Historic crossroad between Europe and Asia featuring the Bosphorus Strait, Hagia Sophia, and the Grand Bazaar.',
                'is_active' => true,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::query()->firstOrCreate(
                ['slug' => $destination['slug']],
                $destination
            );
        }
    }
}
