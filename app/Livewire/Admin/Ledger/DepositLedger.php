<?php

namespace App\Livewire\Admin\Ledger;

use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use App\Services\DepositLedgerService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Agency Deposit & Credit Ledger (Modules 17 & 18)')]
class DepositLedger extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $agencyId = '';

    #[Url]
    public string $type = 'all';

    #[Url]
    public string $status = 'all';

    public string $date_from = '';

    public string $date_to = '';

    // Modals
    public bool $showTopUpModal = false;

    public bool $showVerifyModal = false;

    public bool $showAllocationModal = false;

    public bool $showCreditModal = false;

    public ?int $selectedTransactionId = null;

    public ?int $selectedAgencyId = null;

    // Top-up receipt form
    public ?int $topUpAgencyId = null;

    public string $topUpBank = 'HBL (Habib Bank Limited)';

    public float|int|string $topUpAmount = '';

    public string $topUpProof = '';

    public string $topUpNotes = '';

    public bool $topUpAutoVerify = false;

    // Allocation form
    public ?int $allocSourceId = null;

    public ?int $allocTargetId = null;

    public float|int|string $allocAmount = '';

    public string $allocNotes = '';

    // Credit limit form
    public float|int|string $newCreditLimit = '';

    public string $creditNotes = '';

    // Verification form
    public string $verifierNotes = '';

    // Feedback
    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAgencyId(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->agencyId = '';
        $this->type = 'all';
        $this->status = 'all';
        $this->date_from = '';
        $this->date_to = '';
        $this->resetPage();
    }

    // Top-up modal actions
    public function openTopUpModal(?int $agencyId = null): void
    {
        $this->topUpAgencyId = $agencyId ?: AgencyNode::first()?->id;
        $this->topUpBank = 'HBL (Habib Bank Limited)';
        $this->topUpAmount = '';
        $this->topUpProof = '';
        $this->topUpNotes = '';
        $this->topUpAutoVerify = false;
        $this->showTopUpModal = true;
    }

    public function closeTopUpModal(): void
    {
        $this->showTopUpModal = false;
        $this->topUpAgencyId = null;
        $this->topUpAmount = '';
    }

    public function submitTopUp(DepositLedgerService $service): void
    {
        $this->validate([
            'topUpAgencyId' => ['required', 'exists:agency_nodes,id'],
            'topUpBank' => ['required', 'string', 'max:100'],
            'topUpAmount' => ['required', 'numeric', 'min:1'],
            'topUpProof' => ['nullable', 'string', 'max:255'],
            'topUpNotes' => ['nullable', 'string', 'max:500'],
        ]);

        $data = [
            'agency_node_id' => $this->topUpAgencyId,
            'bank' => $this->topUpBank,
            'amount' => (float) $this->topUpAmount,
            'type' => 'Bank Deposit Slip',
            'proof_file' => $this->topUpProof ?: null,
            'status' => $this->topUpAutoVerify ? 'Verified' : 'Pending',
            'verified_by' => $this->topUpAutoVerify ? (auth()->user()->name ?? 'Finance Officer') : null,
            'notes' => $this->topUpNotes ?: 'Bank deposit slip submitted for account top-up.',
        ];

        $txn = $service->recordTopUp($data);

        $this->successMessage = "Deposit receipt #{$txn->reference} for PKR ".number_format((float) $this->topUpAmount, 2).' created successfully.';
        $this->closeTopUpModal();
    }

    // Verify modal actions
    public function openVerifyModal(int $transactionId): void
    {
        $txn = DepositTransaction::findOrFail($transactionId);
        $this->selectedTransactionId = $txn->id;
        $this->verifierNotes = $txn->notes ?? '';
        $this->showVerifyModal = true;
    }

    public function closeVerifyModal(): void
    {
        $this->showVerifyModal = false;
        $this->selectedTransactionId = null;
        $this->verifierNotes = '';
    }

    public function verifyTransaction(DepositLedgerService $service): void
    {
        if (! $this->selectedTransactionId) {
            return;
        }

        $txn = DepositTransaction::findOrFail($this->selectedTransactionId);
        $service->verifyTransaction($txn, auth()->user()->name ?? 'Chief Cashier', $this->verifierNotes);

        $this->successMessage = "Transaction #{$txn->reference} verified and PKR ".number_format((float) $txn->amount, 2).' credited to agency wallet.';
        $this->closeVerifyModal();
    }

    public function rejectTransaction(DepositLedgerService $service): void
    {
        if (! $this->selectedTransactionId) {
            return;
        }

        $txn = DepositTransaction::findOrFail($this->selectedTransactionId);
        $service->rejectTransaction($txn, auth()->user()->name ?? 'Chief Cashier', $this->verifierNotes);

        $this->errorMessage = "Transaction #{$txn->reference} rejected.";
        $this->closeVerifyModal();
    }

    // Allocation modal actions
    public function openAllocationModal(): void
    {
        $agencies = AgencyNode::orderBy('name')->get();
        $this->allocSourceId = $agencies->first()?->id;
        $this->allocTargetId = $agencies->skip(1)->first()?->id ?? $this->allocSourceId;
        $this->allocAmount = '';
        $this->allocNotes = '';
        $this->showAllocationModal = true;
    }

    public function closeAllocationModal(): void
    {
        $this->showAllocationModal = false;
        $this->allocSourceId = null;
        $this->allocTargetId = null;
        $this->allocAmount = '';
    }

    public function submitAllocation(DepositLedgerService $service): void
    {
        $this->validate([
            'allocSourceId' => ['required', 'exists:agency_nodes,id', 'different:allocTargetId'],
            'allocTargetId' => ['required', 'exists:agency_nodes,id'],
            'allocAmount' => ['required', 'numeric', 'min:1'],
            'allocNotes' => ['nullable', 'string', 'max:500'],
        ]);

        $source = AgencyNode::findOrFail($this->allocSourceId);
        $target = AgencyNode::findOrFail($this->allocTargetId);

        try {
            $service->allocateSubAgentBalance($source, $target, (float) $this->allocAmount, $this->allocNotes);
            $this->successMessage = 'PKR '.number_format((float) $this->allocAmount, 2)." successfully allocated from {$source->code} to {$target->code}!";
            $this->closeAllocationModal();
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    // Credit limit modal
    public function openCreditModal(int $agencyId): void
    {
        $agency = AgencyNode::findOrFail($agencyId);
        $this->selectedAgencyId = $agency->id;
        $this->newCreditLimit = (float) $agency->credit_limit;
        $this->creditNotes = '';
        $this->showCreditModal = true;
    }

    public function closeCreditModal(): void
    {
        $this->showCreditModal = false;
        $this->selectedAgencyId = null;
        $this->newCreditLimit = '';
    }

    public function updateCreditLimit(DepositLedgerService $service): void
    {
        $this->validate([
            'newCreditLimit' => ['required', 'numeric', 'min:0'],
            'creditNotes' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->selectedAgencyId) {
            return;
        }

        $agency = AgencyNode::findOrFail($this->selectedAgencyId);
        $service->updateCreditLimit($agency, (float) $this->newCreditLimit, $this->creditNotes);

        $this->successMessage = "Credit limit for {$agency->name} ({$agency->code}) set to PKR ".number_format((float) $this->newCreditLimit, 2);
        $this->closeCreditModal();
    }

    public function exportCsv(DepositLedgerService $service): StreamedResponse
    {
        $filters = [
            'search' => $this->search,
            'agency_node_id' => $this->agencyId,
            'type' => $this->type,
            'status' => $this->status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        return $service->exportCsv($filters);
    }

    public function render(DepositLedgerService $service)
    {
        $filters = [
            'search' => $this->search,
            'agency_node_id' => $this->agencyId,
            'type' => $this->type,
            'status' => $this->status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        $transactions = $service->listTransactions($filters, 12);
        $agencies = $service->listAgencies();
        $statistics = $service->getStatistics();

        return view('livewire.admin.ledger.deposit-ledger', [
            'transactions' => $transactions,
            'agencies' => $agencies,
            'statistics' => $statistics,
        ]);
    }
}
