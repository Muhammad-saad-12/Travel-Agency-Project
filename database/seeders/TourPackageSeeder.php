<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Database\Seeder;

class TourPackageSeeder extends Seeder
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
        $baku = Destination::query()->where('slug', 'baku-city')->first();
        $dubai = Destination::query()->where('slug', 'dubai')->first();

        $packages = [
            [
                'destination_id' => $makkah?->id,
                'title' => '14-Day Executive Umrah Pilgrimage Package',
                'slug' => '14-day-executive-umrah-pilgrimage',
                'duration_days' => 14,
                'price_pkr' => 425000,
                'difficulty_rating' => 'Easy',
                'is_umrah' => true,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Departure from Islamabad / Lahore to Jeddah', 'details' => 'VIP transfer to Makkah Fairmont Clock Tower & first Umrah.'],
                    ['day' => 2, 'title' => 'Tahajjud & Prayers at Masjid Al-Haram', 'details' => 'Personal religious devotion and guidance.'],
                    ['day' => 4, 'title' => 'Comprehensive Makkah Historical Ziyarat', 'details' => 'Ghar-e-Hira (Jabal Al-Noor), Ghar-e-Saur, Mina, and Arafat.'],
                    ['day' => 8, 'title' => 'High-Speed Haramain Train to Madinah', 'details' => 'Check-in at Pullman Zamzam Madinah directly facing Courtyard.'],
                    ['day' => 10, 'title' => 'Madinah Munawwarah Holy Ziyarat', 'details' => 'Masjid Quba, Masjid Qiblatain, and Mount Uhud Martyrs cemetery.'],
                    ['day' => 14, 'title' => 'Farewell Ziyarat & Flight to Pakistan', 'details' => 'Departure from Prince Mohammad Bin Abdulaziz Airport Madinah.'],
                ],
                'inclusions' => [
                    'Return Airline Tickets (PIA / Saudia Airlines)',
                    '7 Nights 5-Star Makkah Clock Tower Hotel (Breakfast included)',
                    '6 Nights 5-Star Central Madinah Hotel (Breakfast included)',
                    'Umrah Visa Processing & Mandatory Health Insurance',
                    'First Class Haramain High Speed Bullet Train Ticket',
                    'Private AC GMC/Coaster Ziyarat in Makkah & Madinah',
                    '24/7 Dedicated Pakistani Mutawwif / Guide',
                ],
                'is_featured' => true,
            ],
            [
                'destination_id' => $makkah?->id,
                'title' => '7-Day Economy Umrah Express Special',
                'slug' => '7-day-economy-umrah-express',
                'duration_days' => 7,
                'price_pkr' => 245000,
                'difficulty_rating' => 'Easy',
                'is_umrah' => true,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrival at Jeddah Airport', 'details' => 'Shuttle transfer to Makkah 4-Star hotel & perform Umrah.'],
                    ['day' => 4, 'title' => 'Transfer to Madinah Al-Munawwarah', 'details' => 'Visit Al-Masjid an-Nabawi and salam at Roza-e-Rasool (PBUH).'],
                    ['day' => 7, 'title' => 'Return Journey to Pakistan', 'details' => 'Transfer to airport for return flight to Pakistan.'],
                ],
                'inclusions' => [
                    'Return Direct / 1-Stop Economy Flights',
                    '4 Nights Makkah 4-Star Hotel (300m Shuttle)',
                    '3 Nights Madinah 4-Star Central Hotel',
                    'Umrah Visa & Saudi Medical Insurance',
                    'AC Bus Transfers between Airport & Holy Cities',
                ],
                'is_featured' => false,
            ],
            [
                'destination_id' => $hunza?->id,
                'title' => '8-Day Autumn Colors Hunza & Khunjerab China Border',
                'slug' => '8-day-autumn-hunza-khunjerab-pass',
                'duration_days' => 8,
                'price_pkr' => 125000,
                'difficulty_rating' => 'Moderate',
                'is_umrah' => false,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Islamabad to Naran or Chilas via KKH', 'details' => 'Drive along scenic Karakoram Highway over Babusar Pass.'],
                    ['day' => 2, 'title' => 'Arrival in Central Hunza Karimabad', 'details' => 'Rakaposhi viewpoint lunch & sunset at Eagle\'s Nest Duikar.'],
                    ['day' => 3, 'title' => 'Heritage Forts & Local Culture', 'details' => 'Guided tour of 800-year-old Baltit & Altit Forts.'],
                    ['day' => 4, 'title' => 'Attabad Lake & Khunjerab National Park', 'details' => 'Boating on turquoise Attabad Lake & drive to Pakistan-China Border (4,693m).'],
                    ['day' => 6, 'title' => 'Hussaini Suspension Bridge & Passu Cones', 'details' => 'Trek to Passu Glacier and suspension bridge photography.'],
                    ['day' => 8, 'title' => 'Return Drive to Islamabad', 'details' => 'Arrival in Islamabad and tour conclusion.'],
                ],
                'inclusions' => [
                    'Luxury AC Coaster / 4x4 Prado Transport',
                    '7 Nights Premium Hotel Accommodations with Daily Breakfast',
                    'All Tolls, Fuel, Driver Allowance, and Road Permits',
                    'Attabad Lake Boating & Khunjerab National Park Tickets',
                    'Professional Tour Leader and First Aid Support',
                ],
                'is_featured' => true,
            ],
            [
                'destination_id' => $skardu?->id,
                'title' => '6-Day Majestic Skardu & Deosai Plains Expedition',
                'slug' => '6-day-majestic-skardu-deosai',
                'duration_days' => 6,
                'price_pkr' => 140000,
                'difficulty_rating' => 'Moderate',
                'is_umrah' => false,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Direct Flight Islamabad to Skardu', 'details' => 'Spectacular mountain flight past Nanga Parbat; Shangrila resort check-in.'],
                    ['day' => 2, 'title' => 'Upper Kachura Lake & Katpana Cold Desert', 'details' => 'Glacial lake boating and sunset on sand dunes of Cold Desert.'],
                    ['day' => 3, 'title' => '4x4 Safari to Deosai National Park', 'details' => 'Land of Giants (4,114m), Sheosar Lake, and Himalayan flora.'],
                    ['day' => 4, 'title' => 'Shigar Fort & Cold Desert Organic Village', 'details' => 'Amacha Dynasty wooden palace and apple orchards.'],
                    ['day' => 6, 'title' => 'Flight back to Islamabad', 'details' => 'Airport drop off and onward flight.'],
                ],
                'inclusions' => [
                    'Islamabad-Skardu Return Air Tickets (PIA)',
                    '5 Nights Luxury Resort Stay (Shangrila & Shigar)',
                    'Private 4x4 Land Cruiser for Deosai Plains Safari',
                    'Daily Breakfast & Dinners',
                    'Local Gilgit-Baltistan Licensed Guide',
                ],
                'is_featured' => true,
            ],
            [
                'destination_id' => $swat?->id,
                'title' => '5-Day Swat Emerald Valley & Kalam Adventure',
                'slug' => '5-day-swat-emerald-valley-kalam',
                'duration_days' => 5,
                'price_pkr' => 85000,
                'difficulty_rating' => 'Easy',
                'is_umrah' => false,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Islamabad to Mingora & Saidu Sharif', 'details' => 'Motorway drive via Swat Expressway to White Palace Marghazar.'],
                    ['day' => 2, 'title' => 'Malam Jabba Ski Resort & Chairlift', 'details' => 'Alpine mountain views and zipline adventure.'],
                    ['day' => 3, 'title' => 'Drive to Upper Swat Kalam & Ushu Forest', 'details' => 'Riverside drive along Swat river to ancient deodar pine forests.'],
                    ['day' => 4, 'title' => '4x4 Jeep Trek to Mahodand Glacial Lake', 'details' => 'Waterfalls, trout fishing spots, and boating at Mahodand.'],
                    ['day' => 5, 'title' => 'Return Drive to Islamabad', 'details' => 'Visit Swat Archaeological Museum & journey back.'],
                ],
                'inclusions' => [
                    'Dedicated AC Transport & Fuel',
                    '4 Nights 4-Star Hotel Stay with Breakfast',
                    '4x4 Jeep for Mahodand Lake Excursion',
                    'Malam Jabba Resort Entry and Toll Taxes',
                ],
                'is_featured' => false,
            ],
            [
                'destination_id' => $baku?->id,
                'title' => '6-Day Baku Caspian Pearl & Old Town Tour',
                'slug' => '6-day-baku-caspian-pearl',
                'duration_days' => 6,
                'price_pkr' => 210000,
                'difficulty_rating' => 'Easy',
                'is_umrah' => false,
                'itinerary' => [
                    ['day' => 1, 'title' => 'Direct Flight to Baku Heydar Aliyev Airport', 'details' => 'VIP transfer to hotel & Nizami Street night walk.'],
                    ['day' => 2, 'title' => 'UNESCO Old City Icherisheher & Maiden Tower', 'details' => 'Palace of the Shirvanshahs and local culinary lunch.'],
                    ['day' => 3, 'title' => 'Gobustan Rock Art & Mud Volcanoes Safari', 'details' => 'Prehistoric petroglyphs and bubbling mud volcano exploration.'],
                    ['day' => 4, 'title' => 'Ateshgah Fire Temple & Yanardag Burning Mountain', 'details' => 'Ancient Zoroastrian fire rituals and eternal natural gas flames.'],
                    ['day' => 6, 'title' => 'Baku Boulevard Shopping & Departure', 'details' => 'Heydar Aliyev Center photos and airport transfer.'],
                ],
                'inclusions' => [
                    'Return Airfare (AZAL / Fly Jinnah direct flight)',
                    'Azerbaijan ASAN E-Visa Processing',
                    '5 Nights 5-Star Hotel with Breakfast',
                    'Private Mercedes Sprinter with English/Urdu Guide',
                    'All Museum and Fire Temple Entry Passes',
                ],
                'is_featured' => true,
            ],
        ];

        foreach ($packages as $pkgData) {
            if (! empty($pkgData['destination_id'])) {
                TourPackage::query()->firstOrCreate(
                    ['slug' => $pkgData['slug']],
                    $pkgData
                );
            }
        }
    }
}
