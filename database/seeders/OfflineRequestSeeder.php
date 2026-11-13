<?php

namespace Database\Seeders;

use App\Models\OfflineRequest;
use Illuminate\Database\Seeder;

class OfflineRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = [
            [
                'booking_ref' => 'BOS-RQ-9921',
                'pnr' => 'EK98AL',
                'airline' => 'Emirates (EK)',
                'route' => 'LHE - DXB',
                'passenger_name' => 'Zainab Fatima',
                'ticket_number' => '176-9812456789',
                'travel_date' => now()->addDays(7)->format('Y-m-d'),
                'request_type' => 'ticketing',
                'status' => 'Applied',
                'is_urgent' => true,
                'assigned_user' => 'Farhan Qureshi',
                'penalty_amount' => 0.00,
                'refund_amount' => 0.00,
                'remarks' => 'Urgent: Issue before midnight GDS void window.',
                'requested_at' => now()->subHours(2),
            ],
            [
                'booking_ref' => 'BOS-RQ-9922',
                'pnr' => 'PIA741',
                'airline' => 'PIA (PK)',
                'route' => 'ISB - JED',
                'passenger_name' => 'Muhammad Bilal Tariq',
                'ticket_number' => '214-4981234567',
                'travel_date' => now()->addDays(12)->format('Y-m-d'),
                'request_type' => 'ticketing',
                'status' => 'In Progress',
                'is_urgent' => false,
                'assigned_user' => 'Kamran Akmal',
                'penalty_amount' => 0.00,
                'refund_amount' => 0.00,
                'remarks' => 'Umrah group booking offline ticket issuance.',
                'requested_at' => now()->subHours(5),
            ],
            [
                'booking_ref' => 'BOS-RQ-9923',
                'pnr' => 'SV8891',
                'airline' => 'Saudia (SV)',
                'route' => 'ISB - MED',
                'passenger_name' => 'Rashid Mehmood',
                'ticket_number' => '065-7812903456',
                'travel_date' => now()->addDays(4)->format('Y-m-d'),
                'request_type' => 'refund',
                'status' => 'Applied',
                'is_urgent' => true,
                'assigned_user' => null,
                'penalty_amount' => 15000.00,
                'refund_amount' => 95000.00,
                'remarks' => 'Medical cancellation refund claim submitted with hospital certificate.',
                'requested_at' => now()->subHours(1),
            ],
            [
                'booking_ref' => 'BOS-RQ-9924',
                'pnr' => 'QR9910',
                'airline' => 'Qatar Airways (QR)',
                'route' => 'ISB - LHR',
                'passenger_name' => 'Amina Begum',
                'ticket_number' => '157-6712390812',
                'travel_date' => now()->addDays(8)->format('Y-m-d'),
                'request_type' => 'void',
                'status' => 'In Progress',
                'is_urgent' => true,
                'assigned_user' => 'Tariq Mehmood',
                'penalty_amount' => 0.00,
                'refund_amount' => 125000.00,
                'remarks' => 'Same-day void requested before 23:59 GMT.',
                'requested_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($requests as $r) {
            OfflineRequest::query()->firstOrCreate(['booking_ref' => $r['booking_ref']], $r);
        }
    }
}
