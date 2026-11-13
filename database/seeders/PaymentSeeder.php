<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = Booking::all();

        $realisticPayments = [
            [
                'transaction_ref' => 'TXN-RAAST-98214',
                'booking_id' => $bookings->get(0)?->booking_reference ?? 'PK-BK-8901',
                'customer_name' => 'Tariq Mehmood',
                'customer_phone' => '+92 300 1234567',
                'customer_email' => 'tariq.mehmood@example.pk',
                'channel' => 'Raast P2P/P2M',
                'channel_category' => 'raast',
                'amount' => 72000.00,
                'fee_amount' => 0.00,
                'net_amount' => 72000.00,
                'currency' => 'PKR',
                'status' => 'Verified',
                'gateway_ref' => 'RAAST-SBP-889911',
                'proof_file' => 'raast_slip_98214.png',
                'payment_date' => now()->subDays(6),
                'verified_at' => now()->subDays(6),
                'verified_by' => 'Auto Raast Hook',
                'notes' => 'Settled instantly via State Bank Raast portal for flight PK-785.',
            ],
            [
                'transaction_ref' => 'TXN-1LINK-48910',
                'booking_id' => $bookings->get(1)?->booking_reference ?? 'PK-BK-8902',
                'customer_name' => 'Zainab Fatima',
                'customer_phone' => '+92 321 9876543',
                'customer_email' => 'zainab.f@example.pk',
                'channel' => '1Link Bank Transfer',
                'channel_category' => '1link',
                'amount' => 148500.00,
                'fee_amount' => 0.00,
                'net_amount' => 148500.00,
                'currency' => 'PKR',
                'status' => 'Verified',
                'gateway_ref' => '1LINK-BILL-443322',
                'proof_file' => '1link_receipt_48910.pdf',
                'payment_date' => now()->subDays(5),
                'verified_at' => now()->subDays(5),
                'verified_by' => 'HBL Integration',
                'notes' => 'Confirmed via HBL online banking 1Link Bill payment integration.',
            ],
            [
                'transaction_ref' => 'TXN-JCASH-19283',
                'booking_id' => $bookings->get(2)?->booking_reference ?? 'PK-BK-8903',
                'customer_name' => 'Kamran Akmal',
                'customer_phone' => '+92 301 5551234',
                'customer_email' => 'kamran.akmal@example.pk',
                'channel' => 'JazzCash Mobile Wallet',
                'channel_category' => 'wallet',
                'amount' => 45000.00,
                'fee_amount' => 675.00,
                'net_amount' => 44325.00,
                'currency' => 'PKR',
                'status' => 'Verified',
                'gateway_ref' => 'JC-MW-990011',
                'proof_file' => 'jazzcash_screen_19283.jpg',
                'payment_date' => now()->subDays(4),
                'verified_at' => now()->subDays(4),
                'verified_by' => 'Mobile Gateway IPN',
                'notes' => 'JazzCash merchant API direct payment confirmation.',
            ],
            [
                'transaction_ref' => 'TXN-EP-88271',
                'booking_id' => $bookings->get(3)?->booking_reference ?? 'PK-BK-8904',
                'customer_name' => 'Sara Bilal',
                'customer_phone' => '+92 345 3334444',
                'customer_email' => 'sara.bilal@example.pk',
                'channel' => 'EasyPaisa Digital Wallet',
                'channel_category' => 'wallet',
                'amount' => 38000.00,
                'fee_amount' => 570.00,
                'net_amount' => 37430.00,
                'currency' => 'PKR',
                'status' => 'Verified',
                'gateway_ref' => 'EP-TXN-112233',
                'proof_file' => 'easypaisa_receipt_88271.jpg',
                'payment_date' => now()->subDays(3),
                'verified_at' => now()->subDays(3),
                'verified_by' => 'Finance Desk',
                'notes' => 'EasyPaisa wallet QR code payment.',
            ],
            [
                'transaction_ref' => 'TXN-CARD-55102',
                'booking_id' => $bookings->get(4)?->booking_reference ?? 'PK-BK-8905',
                'customer_name' => 'Usman Farooq',
                'customer_phone' => '+92 333 4445555',
                'customer_email' => 'usman.f@example.pk',
                'channel' => 'Credit/Debit Card (Visa/Mastercard)',
                'channel_category' => 'card',
                'amount' => 225000.00,
                'fee_amount' => 5625.00,
                'net_amount' => 219375.00,
                'currency' => 'PKR',
                'status' => 'Verified',
                'gateway_ref' => 'HBL-CYBER-887711',
                'proof_file' => null,
                'payment_date' => now()->subDays(2),
                'verified_at' => now()->subDays(2),
                'verified_by' => 'CyberSource 3D Secure',
                'notes' => 'Meezan Bank Visa Platinum Card - 3DS OTP authenticated.',
            ],
            [
                'transaction_ref' => 'TXN-RAAST-77192',
                'booking_id' => $bookings->get(0)?->booking_reference ?? 'PK-BK-8901',
                'customer_name' => 'Hamza Javed',
                'customer_phone' => '+92 312 9988776',
                'customer_email' => 'hamza.j@example.pk',
                'channel' => 'Raast P2P/P2M',
                'channel_category' => 'raast',
                'amount' => 64000.00,
                'fee_amount' => 0.00,
                'net_amount' => 64000.00,
                'currency' => 'PKR',
                'status' => 'Pending',
                'gateway_ref' => 'RAAST-PEND-443311',
                'proof_file' => 'raast_slip_pending.jpg',
                'payment_date' => now()->subHours(5),
                'verified_at' => null,
                'verified_by' => null,
                'notes' => 'Awaiting manual clearing match with bank settlement statement.',
            ],
        ];

        foreach ($realisticPayments as $data) {
            Payment::updateOrCreate(
                ['transaction_ref' => $data['transaction_ref']],
                $data
            );
        }

        // Generate additional random payments
        Payment::factory()->count(15)->create();
    }
}
