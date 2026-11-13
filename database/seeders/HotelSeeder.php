<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hunza = Destination::query()->where('slug', 'hunza-valley')->first();
        $skardu = Destination::query()->where('slug', 'skardu-valley')->first();
        $swat = Destination::query()->where('slug', 'swat-valley')->first();
        $makkah = Destination::query()->where('slug', 'makkah-al-mukarramah')->first();
        $madinah = Destination::query()->where('slug', 'madinah-al-munawwarah')->first();
        $baku = Destination::query()->where('slug', 'baku-city')->first();
        $dubai = Destination::query()->where('slug', 'dubai')->first();

        $hotels = [
            [
                'destination_id' => $hunza?->id,
                'name' => 'Hunza Serena Inn & Hotel',
                'star_rating' => 5,
                'room_types' => ['Deluxe Garden View', 'Executive Valley Suite', 'Standard Mountain View'],
                'location_address' => 'Zero Point, Karimabad, Hunza, Gilgit-Baltistan',
                'nightly_rate_pkr' => 48000,
                'total_rooms' => 45,
                'available_rooms' => 18,
                'is_partner' => true,
            ],
            [
                'destination_id' => $hunza?->id,
                'name' => 'Luxus Hunza Resort',
                'star_rating' => 4,
                'room_types' => ['Attabad Lake Front Villa', 'Deluxe King', 'Panorama Suite'],
                'location_address' => 'Attabad Lakefront, Gojal, Upper Hunza',
                'nightly_rate_pkr' => 38000,
                'total_rooms' => 32,
                'available_rooms' => 12,
                'is_partner' => true,
            ],
            [
                'destination_id' => $skardu?->id,
                'name' => 'Shangrila Resort Hotel',
                'star_rating' => 4,
                'room_types' => ['Lakefront Swiss Cottage', 'VIP Executive Chalet', 'Standard Twin'],
                'location_address' => 'Lower Kachura Lake, Skardu, Gilgit-Baltistan',
                'nightly_rate_pkr' => 42000,
                'total_rooms' => 60,
                'available_rooms' => 24,
                'is_partner' => true,
            ],
            [
                'destination_id' => $swat?->id,
                'name' => 'Serena Swat Hotel',
                'star_rating' => 4,
                'room_types' => ['Standard Garden View', 'Deluxe Double', 'Heritage Presidential Suite'],
                'location_address' => 'Saidu Sharif Road, Swat Valley, Khyber Pakhtunkhwa',
                'nightly_rate_pkr' => 28000,
                'total_rooms' => 50,
                'available_rooms' => 31,
                'is_partner' => true,
            ],
            [
                'destination_id' => $makkah?->id,
                'name' => 'Makkah Clock Royal Tower - Fairmont',
                'star_rating' => 5,
                'room_types' => ['Kaaba View Deluxe', 'Haram View Signature Suite', 'Junior Suite City View'],
                'location_address' => 'King Abdul Aziz Endowment, Abraj Al Bait Complex, Makkah',
                'nightly_rate_pkr' => 110000,
                'total_rooms' => 200,
                'available_rooms' => 45,
                'is_partner' => true,
            ],
            [
                'destination_id' => $makkah?->id,
                'name' => 'Swissôtel Al Maqam Makkah',
                'star_rating' => 5,
                'room_types' => ['Classic Twin', 'Haram View King', 'Family Quad Suite'],
                'location_address' => 'Ibrahim Al Khalil St, Abraj Al Bait, Makkah',
                'nightly_rate_pkr' => 85000,
                'total_rooms' => 180,
                'available_rooms' => 52,
                'is_partner' => true,
            ],
            [
                'destination_id' => $madinah?->id,
                'name' => 'Pullman Zamzam Madina Hotel',
                'star_rating' => 5,
                'room_types' => ['Prophet Mosque Courtyard View', 'Superior Twin', 'Executive Suite'],
                'location_address' => 'Amr Bin Al Aas Street, Central Area, Madinah',
                'nightly_rate_pkr' => 72000,
                'total_rooms' => 150,
                'available_rooms' => 38,
                'is_partner' => true,
            ],
            [
                'destination_id' => $baku?->id,
                'name' => 'Four Seasons Hotel Baku',
                'star_rating' => 5,
                'room_types' => ['Caspian Sea Promenade King', 'City View Deluxe', 'Premier Suite'],
                'location_address' => '1 Neftchilar Ave, Baku, Azerbaijan',
                'nightly_rate_pkr' => 62000,
                'total_rooms' => 90,
                'available_rooms' => 29,
                'is_partner' => true,
            ],
            [
                'destination_id' => $dubai?->id,
                'name' => 'Atlantis The Palm Dubai',
                'star_rating' => 5,
                'room_types' => ['Ocean King Room', 'Palm View Deluxe', 'Imperial Club Suite'],
                'location_address' => 'Crescent Rd, The Palm Jumeirah, Dubai, UAE',
                'nightly_rate_pkr' => 140000,
                'total_rooms' => 120,
                'available_rooms' => 20,
                'is_partner' => false,
            ],
        ];

        foreach ($hotels as $hotelData) {
            if (! empty($hotelData['destination_id'])) {
                Hotel::query()->firstOrCreate(
                    [
                        'destination_id' => $hotelData['destination_id'],
                        'name' => $hotelData['name'],
                    ],
                    $hotelData
                );
            }
        }
    }
}
