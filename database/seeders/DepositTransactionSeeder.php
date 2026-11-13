<?php

namespace Database\Seeders;

use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use Illuminate\Database\Seeder;

class DepositTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hq = AgencyNode::query()->where('code', 'CLI-20000')->first();
        $lahore = AgencyNode::query()->where('code', 'CLI-20001')->first();

        $txns = [
            [
                'reference' => 'TXN-DEP-9912',
                'agency_node_id' => $hq?->id,
                'agency_code' => 'CLI-20000',
                'agency_name' => 'Head Office Islamabad (HQ)',
                'bank' => 'Meezan Bank Limited',
                'type' => 'Bank Deposit Slip',
                'amount' => 2000000.00,
                'balance_after' => 4850000.00,
                'status' => 'Verified',
                'proof_file' => 'slips/meezan_deposit_9912.pdf',
                'verified_by' => 'Finance Director',
                'transaction_date' => now()->subDays(2),
                'notes' => 'Cash deposit in Blue Area branch for GDS ticket issuance limit.',
            ],
            [
                'reference' => 'TXN-DEP-9911',
                'agency_node_id' => $lahore?->id,
                'agency_code' => 'CLI-20001',
                'agency_name' => 'Lahore Branch (Gulberg)',
                'bank' => 'Habib Bank Limited (HBL)',
                'type' => 'Online Transfer',
                'amount' => 1500000.00,
                'balance_after' => 2100000.00,
                'status' => 'Verified',
                'proof_file' => 'slips/hbl_online_9911.pdf',
                'verified_by' => 'Audit Manager',
                'transaction_date' => now()->subDays(4),
                'notes' => 'Raast P2M top-up for corporate client group ticketing.',
            ],
            [
                'reference' => 'TXN-DEP-9910',
                'agency_node_id' => $hq?->id,
                'agency_code' => 'CLI-20000',
                'agency_name' => 'Head Office Islamabad (HQ)',
                'bank' => 'Bank Alfalah',
                'type' => 'Ticket Debit',
                'amount' => -348500.00,
                'balance_after' => 2850000.00,
                'status' => 'Verified',
                'proof_file' => null,
                'verified_by' => 'Ticketing System',
                'transaction_date' => now()->subDays(1),
                'notes' => 'Automated ticket issuance debit for booking PK-BK-8902.',
            ],
            [
                'reference' => 'TXN-DEP-9913',
                'agency_node_id' => $lahore?->id,
                'agency_code' => 'CLI-20001',
                'agency_name' => 'Lahore Branch (Gulberg)',
                'bank' => 'Faysal Bank',
                'type' => 'Bank Deposit Slip',
                'amount' => 500000.00,
                'balance_after' => 2600000.00,
                'status' => 'Pending Approval',
                'proof_file' => 'slips/faysal_deposit_9913.pdf',
                'verified_by' => null,
                'transaction_date' => now()->subHours(3),
                'notes' => 'Cheque clearing pending at Faysal Bank Gulberg.',
            ],
        ];

        foreach ($txns as $t) {
            DepositTransaction::query()->firstOrCreate(['reference' => $t['reference']], $t);
        }
    }
}
