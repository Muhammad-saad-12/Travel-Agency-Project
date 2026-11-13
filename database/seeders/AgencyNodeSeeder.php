<?php

namespace Database\Seeders;

use App\Models\AgencyNode;
use Illuminate\Database\Seeder;

class AgencyNodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nodes = [
            [
                'code' => 'CLI-20000',
                'name' => 'Head Office Islamabad (HQ)',
                'type' => 'HQ',
                'city' => 'Islamabad',
                'country' => 'PK',
                'credit_limit' => 50000000.00,
                'balance' => 4850000.00,
                'currency' => 'PKR',
                'contact_person' => 'Tariq Mehmood',
                'phone' => '+92 51 2345678',
                'email' => 'hq@flyaero.pk',
                'status' => 'Active',
            ],
            [
                'code' => 'CLI-20001',
                'name' => 'Lahore Branch (Gulberg)',
                'type' => 'Branch',
                'city' => 'Lahore',
                'country' => 'PK',
                'credit_limit' => 25000000.00,
                'balance' => 2100000.00,
                'currency' => 'PKR',
                'contact_person' => 'Kamran Akmal',
                'phone' => '+92 42 3578901',
                'email' => 'lahore@flyaero.pk',
                'status' => 'Active',
            ],
            [
                'code' => 'CLI-20002',
                'name' => 'Karachi Hub (Shahrah-e-Faisal)',
                'type' => 'Branch',
                'city' => 'Karachi',
                'country' => 'PK',
                'credit_limit' => 30000000.00,
                'balance' => 3450000.00,
                'currency' => 'PKR',
                'contact_person' => 'Farhan Qureshi',
                'phone' => '+92 21 3432100',
                'email' => 'karachi@flyaero.pk',
                'status' => 'Active',
            ],
            [
                'code' => 'SUB-9140',
                'name' => 'Northern Express Peshawar',
                'type' => 'Sub-Agent',
                'city' => 'Peshawar',
                'country' => 'PK',
                'credit_limit' => 5000000.00,
                'balance' => 850000.00,
                'currency' => 'PKR',
                'contact_person' => 'Sohail Shinwari',
                'phone' => '+92 91 5841234',
                'email' => 'peshawar@northernexpress.pk',
                'status' => 'Active',
            ],
        ];

        foreach ($nodes as $node) {
            AgencyNode::query()->firstOrCreate(['code' => $node['code']], $node);
        }
    }
}
