<?php

namespace Tests\Feature;

use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use App\Services\DepositLedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositLedgerServiceTest extends TestCase
{
    use RefreshDatabase;

    private DepositLedgerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DepositLedgerService::class);
    }

    public function test_can_record_pending_deposit_slip_without_immediate_balance_increase(): void
    {
        $agency = AgencyNode::factory()->create([
            'balance' => 100000,
        ]);

        $txn = $this->service->recordTopUp([
            'agency_node_id' => $agency->id,
            'bank' => 'Meezan Bank Ltd',
            'amount' => 500000,
            'status' => 'Pending',
            'proof_file' => 'MEEZAN-SLIP-101.jpg',
        ]);

        $this->assertEquals('Pending', $txn->status);
        $this->assertEquals(500000, (float) $txn->amount);

        // Agency balance should remain untouched while pending
        $this->assertEquals(100000, (float) $agency->fresh()->balance);
    }

    public function test_can_verify_pending_deposit_and_credit_agency_balance(): void
    {
        $agency = AgencyNode::factory()->create([
            'balance' => 50000,
        ]);

        $txn = $this->service->recordTopUp([
            'agency_node_id' => $agency->id,
            'bank' => 'HBL (Habib Bank Limited)',
            'amount' => 200000,
            'status' => 'Pending',
        ]);

        $verified = $this->service->verifyTransaction($txn, 'Finance Auditor');

        $this->assertEquals('Verified', $verified->status);
        $this->assertEquals('Finance Auditor', $verified->verified_by);
        $this->assertEquals(250000, (float) $verified->balance_after);

        // Agency balance should now be updated
        $this->assertEquals(250000, (float) $agency->fresh()->balance);
    }

    public function test_can_reject_pending_deposit(): void
    {
        $agency = AgencyNode::factory()->create(['balance' => 50000]);

        $txn = $this->service->recordTopUp([
            'agency_node_id' => $agency->id,
            'amount' => 100000,
            'status' => 'Pending',
        ]);

        $rejected = $this->service->rejectTransaction($txn, 'Operations Lead', 'Invalid deposit slip image');

        $this->assertEquals('Rejected', $rejected->status);
        $this->assertEquals(50000, (float) $agency->fresh()->balance);
    }

    public function test_can_record_ticketing_wallet_debit(): void
    {
        $agency = AgencyNode::factory()->create(['balance' => 300000]);

        $txn = $this->service->recordDebit($agency, 80000, 'Ticket issuance PK-785', 'FB-2026-9901');

        $this->assertEquals('Ticket Debit', $txn->type);
        $this->assertEquals(-80000, (float) $txn->amount);
        $this->assertEquals(220000, (float) $txn->balance_after);
        $this->assertEquals(220000, (float) $agency->fresh()->balance);
    }

    public function test_can_record_refund_credit(): void
    {
        $agency = AgencyNode::factory()->create(['balance' => 220000]);

        $txn = $this->service->recordRefundCredit($agency, 60000, 'Ticket refund processed', 'FB-2026-9901');

        $this->assertEquals('Refund Credit', $txn->type);
        $this->assertEquals(60000, (float) $txn->amount);
        $this->assertEquals(280000, (float) $txn->balance_after);
        $this->assertEquals(280000, (float) $agency->fresh()->balance);
    }

    public function test_can_update_b2b_credit_limit(): void
    {
        $agency = AgencyNode::factory()->create([
            'credit_limit' => 500000,
            'balance' => 100000,
        ]);

        $updatedAgency = $this->service->updateCreditLimit($agency, 1500000, 'Security deposit approved');

        $this->assertEquals(1500000, (float) $updatedAgency->credit_limit);
        $this->assertDatabaseHas('deposit_transactions', [
            'agency_node_id' => $agency->id,
            'type' => 'Credit Limit Allocation',
        ]);
    }

    public function test_can_allocate_balance_between_sub_agents(): void
    {
        $parent = AgencyNode::factory()->create(['balance' => 500000]);
        $subAgent = AgencyNode::factory()->create(['balance' => 50000]);

        $result = $this->service->allocateSubAgentBalance($parent, $subAgent, 150000, 'Weekly quota allocation');

        $this->assertEquals(350000, (float) $parent->fresh()->balance);
        $this->assertEquals(200000, (float) $subAgent->fresh()->balance);
        $this->assertEquals(-150000, (float) $result['source']->amount);
        $this->assertEquals(150000, (float) $result['target']->amount);
    }

    public function test_cannot_allocate_more_than_available_balance(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $parent = AgencyNode::factory()->create(['balance' => 10000]);
        $subAgent = AgencyNode::factory()->create(['balance' => 0]);

        $this->service->allocateSubAgentBalance($parent, $subAgent, 50000);
    }

    public function test_can_export_ledger_to_csv(): void
    {
        DepositTransaction::factory()->create([
            'reference' => 'TXN-DEP-CSV1',
        ]);

        $response = $this->service->exportCsv();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
