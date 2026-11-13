<?php

namespace Database\Seeders;

use App\Models\AgencySetting;
use Illuminate\Database\Seeder;

class AgencySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgencySetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'business_name' => 'FlyAero Travels & Tours',
                'dts_license' => 'DTS-4521',
                'ntn' => '7823419-4',
                'strn' => '3277876123456',
                'phone' => '+92 51 2345678',
                'email' => 'operations@flyaero.pk',
                'address' => 'Suite 402, Blue Area, Jinnah Avenue, Islamabad, Pakistan',
                'whatsapp_number' => '+92 300 1234567',
                'default_currency' => 'PKR',
            ]
        );
    }
}
