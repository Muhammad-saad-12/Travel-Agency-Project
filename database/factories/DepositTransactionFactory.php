<?php

namespace Database\Factories;

use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DepositTransaction>
 */
class DepositTransactionFactory extends Factory
{
    protected $model = DepositTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $banks = ['Meezan Bank Limited', 'Habib Bank Limited (HBL)', 'Bank Alfalah', 'Standard Chartered', 'Faysal Bank'];
        $amt = fake()->randomElement([500000, 1000000, 2000000, 3500000]);

        return [
            'reference' => 'TXN-DEP-'.strtoupper(Str::random(4)),
            'agency_node_id' => AgencyNode::factory(),
            'agency_code' => 'CLI-20000',
            'agency_name' => 'Islamabad Head Office',
            'bank' => fake()->randomElement($banks),
            'type' => 'Bank Deposit Slip',
            'amount' => $amt,
            'balance_after' => $amt + 1000000,
            'status' => 'Verified',
            'proof_file' => 'deposits/slip_'.fake()->numberBetween(100, 999).'.pdf',
            'verified_by' => fake()->name(),
            'transaction_date' => now()->subDays(fake()->numberBetween(1, 10)),
            'notes' => 'Direct RTGS/1Link transfer verified via bank statement.',
        ];
    }
}
