<?php

namespace Tests\Feature;

use App\Livewire\Admin\Ledger\DepositLedger;
use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DepositLedgerLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_deposit_ledger_page_loads_successfully(): void
    {
        $agency = AgencyNode::factory()->create([
            'code' => 'KHI-HQ-TEST',
            'name' => 'Karachi Central Desk',
        ]);

        DepositTransaction::factory()->create([
            'agency_node_id' => $agency->id,
            'reference' => 'TXN-DEP-PAGE1',
            'amount' => 750000,
        ]);

        $response = $this->get('/admin/ledger');

        $response->assertStatus(200);
        $response->assertSee('Agency Deposit & Credit Ledger');
        $response->assertSee('Modules 17 & 18');
        $response->assertSee('KHI-HQ-TEST');
        $response->assertSee('TXN-DEP-PAGE1');
    }

    public function test_can_submit_topup_slip_via_livewire(): void
    {
        $agency = AgencyNode::factory()->create([
            'balance' => 100000,
        ]);

        Livewire::test(DepositLedger::class)
            ->call('openTopUpModal', $agency->id)
            ->assertSet('showTopUpModal', true)
            ->set('topUpAmount', 250000)
            ->set('topUpBank', 'Meezan Bank Ltd')
            ->set('topUpProof', 'SLIP-9900')
            ->set('topUpAutoVerify', true)
            ->call('submitTopUp')
            ->assertSet('showTopUpModal', false)
            ->assertSee('created successfully');

        $this->assertEquals(350000, (float) $agency->fresh()->balance);
        $this->assertDatabaseHas('deposit_transactions', [
            'agency_node_id' => $agency->id,
            'proof_file' => 'SLIP-9900',
            'status' => 'Verified',
        ]);
    }

    public function test_can_verify_pending_slip_via_livewire(): void
    {
        $agency = AgencyNode::factory()->create(['balance' => 50000]);
        $txn = DepositTransaction::factory()->create([
            'agency_node_id' => $agency->id,
            'amount' => 150000,
            'status' => 'Pending',
        ]);

        Livewire::test(DepositLedger::class)
            ->call('openVerifyModal', $txn->id)
            ->assertSet('showVerifyModal', true)
            ->set('verifierNotes', 'Bank clearance received')
            ->call('verifyTransaction')
            ->assertSet('showVerifyModal', false)
            ->assertSee('verified and PKR');

        $this->assertEquals(200000, (float) $agency->fresh()->balance);
        $this->assertDatabaseHas('deposit_transactions', [
            'id' => $txn->id,
            'status' => 'Verified',
        ]);
    }

    public function test_can_adjust_credit_limit_via_livewire(): void
    {
        $agency = AgencyNode::factory()->create([
            'credit_limit' => 200000,
        ]);

        Livewire::test(DepositLedger::class)
            ->call('openCreditModal', $agency->id)
            ->assertSet('showCreditModal', true)
            ->set('newCreditLimit', 800000)
            ->call('updateCreditLimit')
            ->assertSet('showCreditModal', false)
            ->assertSee('set to PKR 800,000.00');

        $this->assertEquals(800000, (float) $agency->fresh()->credit_limit);
    }

    public function test_can_execute_subagent_allocation_via_livewire(): void
    {
        $parent = AgencyNode::factory()->create(['balance' => 400000]);
        $sub = AgencyNode::factory()->create(['balance' => 20000]);

        Livewire::test(DepositLedger::class)
            ->call('openAllocationModal')
            ->assertSet('showAllocationModal', true)
            ->set('allocSourceId', $parent->id)
            ->set('allocTargetId', $sub->id)
            ->set('allocAmount', 100000)
            ->call('submitAllocation')
            ->assertSet('showAllocationModal', false)
            ->assertSee('successfully allocated');

        $this->assertEquals(300000, (float) $parent->fresh()->balance);
        $this->assertEquals(120000, (float) $sub->fresh()->balance);
    }

    public function test_can_trigger_ledger_csv_export(): void
    {
        DepositTransaction::factory()->create();

        Livewire::test(DepositLedger::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
