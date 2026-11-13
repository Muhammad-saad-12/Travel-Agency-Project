<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $channels = [
            ['name' => 'Raast P2P/P2M', 'category' => 'raast', 'fee_rate' => 0.0],
            ['name' => '1Link Bank Transfer', 'category' => '1link', 'fee_rate' => 0.0],
            ['name' => 'JazzCash Mobile Wallet', 'category' => 'wallet', 'fee_rate' => 0.015],
            ['name' => 'EasyPaisa Digital Wallet', 'category' => 'wallet', 'fee_rate' => 0.015],
            ['name' => 'Credit/Debit Card (Visa/Mastercard)', 'category' => 'card', 'fee_rate' => 0.025],
            ['name' => 'Meezan Bank Wire', 'category' => 'bank', 'fee_rate' => 0.0],
            ['name' => 'HBL Corporate Deposit', 'category' => 'bank', 'fee_rate' => 0.0],
        ];

        $selected = fake()->randomElement($channels);
        $amount = fake()->randomElement([35000, 48000, 75000, 95000, 145000, 220000, 310000]);
        $fee = round($amount * $selected['fee_rate'], 2);
        $net = $amount - $fee;
        $booking = Booking::inRandomOrder()->first();

        return [
            'transaction_ref' => Payment::generateReference($selected['category']),
            'booking_id' => $booking?->booking_reference ?? ('PK-BK-'.strtoupper(Str::random(4))),
            'customer_name' => fake()->name(),
            'customer_phone' => '+92 3'.fake()->numerify('## #######'),
            'customer_email' => fake()->safeEmail(),
            'channel' => $selected['name'],
            'channel_category' => $selected['category'],
            'amount' => $amount,
            'fee_amount' => $fee,
            'net_amount' => $net,
            'currency' => 'PKR',
            'status' => fake()->randomElement(['Verified', 'Verified', 'Verified', 'Pending', 'Failed']),
            'gateway_ref' => strtoupper(fake()->bothify('GW-##?#?###')),
            'proof_file' => fake()->boolean(60) ? 'receipt_'.fake()->numerify('#####').'.jpg' : null,
            'payment_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'verified_at' => now(),
            'verified_by' => fake()->randomElement(['Finance Desk', 'Auto Raast Hook', 'Chief Cashier', 'Accounts Team']),
            'notes' => fake()->sentence(),
        ];
    }

    /**
     * State for verified payments.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Verified',
            'verified_at' => now(),
            'verified_by' => 'Audit Officer',
        ]);
    }

    /**
     * State for pending payments.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
            'verified_at' => null,
            'verified_by' => null,
        ]);
    }
}
