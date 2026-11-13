<?php

namespace Database\Seeders;

use App\Models\FlightInquiry;
use Illuminate\Database\Seeder;

class FlightInquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inquiries = [
            [
                'reference_no' => 'INQ-2026-UM01',
                'trip_type' => 'round_trip',
                'origin' => 'Islamabad (ISB)',
                'destination' => 'Jeddah (JED)',
                'departure_date' => now()->addDays(12)->format('Y-m-d'),
                'return_date' => now()->addDays(26)->format('Y-m-d'),
                'adults' => 2,
                'children' => 1,
                'infants' => 0,
                'cabin_class' => 'Economy',
                'customer_name' => 'Muhammad Bilal Tariq',
                'customer_phone' => '+92 300 8521479',
                'customer_email' => 'bilal.tariq@example.pk',
                'notes' => 'Umrah family trip. Prefer direct flights on Saudia or PIA with Haramain train luggage allowance.',
                'quoted_price_pkr' => 385000,
                'status' => 'Quoted',
            ],
            [
                'reference_no' => 'INQ-2026-DXB2',
                'trip_type' => 'round_trip',
                'origin' => 'Lahore (LHE)',
                'destination' => 'Dubai (DXB)',
                'departure_date' => now()->addDays(7)->format('Y-m-d'),
                'return_date' => now()->addDays(14)->format('Y-m-d'),
                'adults' => 2,
                'children' => 0,
                'infants' => 0,
                'cabin_class' => 'Business',
                'customer_name' => 'Syeda Fatima Zahra',
                'customer_phone' => '+92 321 4455667',
                'customer_email' => 'fatima.zahra@business.com',
                'notes' => 'Emirates Business Class preferred for Gitex Technology exhibition.',
                'quoted_price_pkr' => 640000,
                'status' => 'Booked',
            ],
            [
                'reference_no' => 'INQ-2026-SKD3',
                'trip_type' => 'round_trip',
                'origin' => 'Islamabad (ISB)',
                'destination' => 'Skardu (KDU)',
                'departure_date' => now()->addDays(20)->format('Y-m-d'),
                'return_date' => now()->addDays(26)->format('Y-m-d'),
                'adults' => 4,
                'children' => 0,
                'infants' => 0,
                'cabin_class' => 'Economy',
                'customer_name' => 'Hamza Ali Abbasi',
                'customer_phone' => '+92 333 9988776',
                'customer_email' => 'hamza.abbasi@gmail.com',
                'notes' => 'Direct PIA flight to Skardu for Deosai photography trip.',
                'quoted_price_pkr' => null,
                'status' => 'Pending',
            ],
            [
                'reference_no' => 'INQ-2026-BAK4',
                'trip_type' => 'one_way',
                'origin' => 'Karachi (KHI)',
                'destination' => 'Baku (GYD)',
                'departure_date' => now()->addDays(15)->format('Y-m-d'),
                'return_date' => null,
                'adults' => 1,
                'children' => 0,
                'infants' => 0,
                'cabin_class' => 'Economy',
                'customer_name' => 'Usman Ghani',
                'customer_phone' => '+92 345 1122334',
                'customer_email' => 'usman.ghani@yahoo.com',
                'notes' => 'Fly Jinnah direct flight preferred.',
                'quoted_price_pkr' => 78000,
                'status' => 'Contacted',
            ],
            [
                'reference_no' => 'INQ-2026-MLT5',
                'trip_type' => 'multi_city',
                'origin' => 'Islamabad (ISB)',
                'destination' => 'Istanbul (IST)',
                'departure_date' => now()->addDays(25)->format('Y-m-d'),
                'return_date' => null,
                'multi_city_segments' => [
                    ['origin' => 'Islamabad (ISB)', 'destination' => 'Baku (GYD)', 'date' => now()->addDays(25)->format('Y-m-d')],
                    ['origin' => 'Baku (GYD)', 'destination' => 'Istanbul (IST)', 'date' => now()->addDays(30)->format('Y-m-d')],
                    ['origin' => 'Istanbul (IST)', 'destination' => 'Islamabad (ISB)', 'date' => now()->addDays(36)->format('Y-m-d')],
                ],
                'adults' => 2,
                'children' => 2,
                'infants' => 0,
                'cabin_class' => 'Economy',
                'customer_name' => 'Dr. Ayesha Malik',
                'customer_phone' => '+92 301 5566778',
                'customer_email' => 'ayesha.malik@hospital.org.pk',
                'notes' => 'Family vacation covering Azerbaijan and Turkey.',
                'quoted_price_pkr' => null,
                'status' => 'Pending',
            ],
        ];

        foreach ($inquiries as $inquiry) {
            FlightInquiry::query()->firstOrCreate(
                ['reference_no' => $inquiry['reference_no']],
                $inquiry
            );
        }
    }
}
