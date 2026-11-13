<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AgencySettingSeeder::class,
            DestinationSeeder::class,
            HotelSeeder::class,
            TourPackageSeeder::class,
            FlightInquirySeeder::class,
            AgencyNodeSeeder::class,
            BookingSeeder::class,
            OfflineRequestSeeder::class,
            DepositTransactionSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
