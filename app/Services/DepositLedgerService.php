<?php

namespace App\Services;

use App\Models\AgencyNode;
use App\Models\DepositTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DepositLedgerService
{
    /**
     * List paginated transactions with search and filter conditions.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listTransactions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(DepositTransaction::query(), $filters)
            ->latest('transaction_date')
            ->paginate($perPage);
    }

    /**
     * Apply filter criteria to deposit transactions query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $sub) use ($search): void {
                $sub->where('reference', 'like', "%{$search}%")
                    ->orWhere('agency_code', 'like', "%{$search}%")
                    ->orWhere('agency_name', 'like', "%{$search}%")
                    ->orWhere('bank', 'like', "%{$search}%")
                    ->orWhere('verified_by', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['agency_node_id'])) {
            $query->where('agency_node_id', $filters['agency_node_id']);
        }

        if (! empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', Carbon::parse($filters['date_from'])->toDateString());
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', Carbon::parse($filters['date_to'])->toDateString());
        }

        return $query;
    }

    /**
     * Retrieve all agencies with their balances and credit limits.
     *
     * @return Collection<int, AgencyNode>
     */
    public function listAgencies(): Collection
    {
        return AgencyNode::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * Record a bank deposit slip top-up receipt.
     *
     * @param  array<string, mixed>  $data
     */
    public function recordTopUp(array $data): DepositTransaction
    {
        return DB::transaction(function () use ($data): DepositTransaction {
            $agency = AgencyNode::findOrFail($data['agency_node_id']);

            $amount = (float) $data['amount'];
            $isVerified = ($data['status'] ?? 'Pending') === 'Verified';

            $newBalance = (float) $agency->balance;
            if ($isVerified) {
                $newBalance += $amount;
                $agency->update(['balance' => $newBalance]);
            }

            return DepositTransaction::create([
                'agency_node_id' => $agency->id,
                'agency_code' => $agency->code,
                'agency_name' => $agency->name,
                'bank' => $data['bank'] ?? 'HBL (Habib Bank Limited)',
                'type' => $data['type'] ?? 'Bank Deposit Slip',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'status' => $isVerified ? 'Verified' : 'Pending',
                'proof_file' => $data['proof_file'] ?? null,
                'verified_by' => $isVerified ? ($data['verified_by'] ?? 'Finance Desk') : null,
                'transaction_date' => $data['transaction_date'] ?? Carbon::now(),
                'notes' => $data['notes'] ?? 'Bank deposit receipt submitted for wallet top-up.',
            ]);
        });
    }

    /**
     * Approve and verify a pending bank deposit receipt.
     */
    public function verifyTransaction(DepositTransaction $transaction, string $verifiedBy, ?string $notes = null): DepositTransaction
    {
        return DB::transaction(function () use ($transaction, $verifiedBy, $notes): DepositTransaction {
            if ($transaction->status === 'Verified') {
                return $transaction;
            }

            $agency = AgencyNode::findOrFail($transaction->agency_node_id);
            $newBalance = (float) $agency->balance + (float) $transaction->amount;

            $agency->update(['balance' => $newBalance]);

            $updateData = [
                'status' => 'Verified',
                'verified_by' => $verifiedBy,
                'balance_after' => $newBalance,
            ];

            if ($notes !== null) {
                $updateData['notes'] = $notes;
            }

            $transaction->update($updateData);

            return $transaction->fresh();
        });
    }

    /**
     * Reject a pending deposit transaction.
     */
    public function rejectTransaction(DepositTransaction $transaction, string $rejectedBy, ?string $notes = null): DepositTransaction
    {
        $updateData = [
            'status' => 'Rejected',
            'verified_by' => $rejectedBy,
        ];

        if ($notes !== null) {
            $updateData['notes'] = $notes;
        }

        $transaction->update($updateData);

        return $transaction->fresh();
    }

    /**
     * Debit an agency's wallet for ticket issuance or fees.
     */
    public function recordDebit(AgencyNode $agency, float $amount, string $reason, ?string $referenceNumber = null): DepositTransaction
    {
        return DB::transaction(function () use ($agency, $amount, $reason, $referenceNumber): DepositTransaction {
            $newBalance = (float) $agency->balance - $amount;
            $agency->update(['balance' => $newBalance]);

            return DepositTransaction::create([
                'agency_node_id' => $agency->id,
                'agency_code' => $agency->code,
                'agency_name' => $agency->name,
                'bank' => 'Wallet Balance Debit',
                'type' => 'Ticket Debit',
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'status' => 'Verified',
                'verified_by' => 'Automated Engine',
                'transaction_date' => Carbon::now(),
                'notes' => trim($reason.($referenceNumber ? " [Ref: {$referenceNumber}]" : '')),
            ]);
        });
    }

    /**
     * Credit an agency's wallet for refunds or adjustment.
     */
    public function recordRefundCredit(AgencyNode $agency, float $amount, string $reason, ?string $referenceNumber = null): DepositTransaction
    {
        return DB::transaction(function () use ($agency, $amount, $reason, $referenceNumber): DepositTransaction {
            $newBalance = (float) $agency->balance + $amount;
            $agency->update(['balance' => $newBalance]);

            return DepositTransaction::create([
                'agency_node_id' => $agency->id,
                'agency_code' => $agency->code,
                'agency_name' => $agency->name,
                'bank' => 'Wallet Balance Credit',
                'type' => 'Refund Credit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'status' => 'Verified',
                'verified_by' => 'Refund Operations',
                'transaction_date' => Carbon::now(),
                'notes' => trim($reason.($referenceNumber ? " [Ref: {$referenceNumber}]" : '')),
            ]);
        });
    }

    /**
     * Update an agency's B2B credit limit.
     */
    public function updateCreditLimit(AgencyNode $agency, float $newCreditLimit, ?string $notes = null): AgencyNode
    {
        return DB::transaction(function () use ($agency, $newCreditLimit, $notes): AgencyNode {
            $oldLimit = (float) $agency->credit_limit;
            $agency->update(['credit_limit' => $newCreditLimit]);

            DepositTransaction::create([
                'agency_node_id' => $agency->id,
                'agency_code' => $agency->code,
                'agency_name' => $agency->name,
                'bank' => 'Internal Credit Allocation',
                'type' => 'Credit Limit Allocation',
                'amount' => $newCreditLimit - $oldLimit,
                'balance_after' => (float) $agency->balance,
                'status' => 'Verified',
                'verified_by' => 'Credit Committee',
                'transaction_date' => Carbon::now(),
                'notes' => $notes ?: sprintf('Credit limit modified from PKR %s to PKR %s', number_format($oldLimit, 2), number_format($newCreditLimit, 2)),
            ]);

            return $agency->fresh();
        });
    }

    /**
     * Allocate/Transfer operating balance between agency nodes.
     *
     * @return array{source: DepositTransaction, target: DepositTransaction}
     */
    public function allocateSubAgentBalance(AgencyNode $sourceAgency, AgencyNode $targetAgency, float $amount, ?string $notes = null): array
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Allocation amount must be greater than zero.');
        }

        if ((float) $sourceAgency->balance < $amount) {
            throw new InvalidArgumentException(sprintf('Insufficient available balance on source agency (%s). Available: PKR %s', $sourceAgency->code, number_format((float) $sourceAgency->balance, 2)));
        }

        return DB::transaction(function () use ($sourceAgency, $targetAgency, $amount, $notes): array {
            $sourceNewBalance = (float) $sourceAgency->balance - $amount;
            $sourceAgency->update(['balance' => $sourceNewBalance]);

            $sourceTxn = DepositTransaction::create([
                'agency_node_id' => $sourceAgency->id,
                'agency_code' => $sourceAgency->code,
                'agency_name' => $sourceAgency->name,
                'bank' => 'Sub-Agent Allocation',
                'type' => 'Sub-Agent Transfer Out',
                'amount' => -$amount,
                'balance_after' => $sourceNewBalance,
                'status' => 'Verified',
                'verified_by' => 'Agency Administrator',
                'transaction_date' => Carbon::now(),
                'notes' => $notes ?: sprintf('Allocation transferred to %s (%s)', $targetAgency->name, $targetAgency->code),
            ]);

            $targetNewBalance = (float) $targetAgency->balance + $amount;
            $targetAgency->update(['balance' => $targetNewBalance]);

            $targetTxn = DepositTransaction::create([
                'agency_node_id' => $targetAgency->id,
                'agency_code' => $targetAgency->code,
                'agency_name' => $targetAgency->name,
                'bank' => 'Sub-Agent Allocation',
                'type' => 'Sub-Agent Transfer In',
                'amount' => $amount,
                'balance_after' => $targetNewBalance,
                'status' => 'Verified',
                'verified_by' => 'Agency Administrator',
                'transaction_date' => Carbon::now(),
                'notes' => $notes ?: sprintf('Allocation received from %s (%s)', $sourceAgency->name, $sourceAgency->code),
            ]);

            return [
                'source' => $sourceTxn,
                'target' => $targetTxn,
            ];
        });
    }

    /**
     * Compute summary statistics for deposit and credit dashboards.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        $cashBalance = (float) AgencyNode::sum('balance');
        $creditLimit = (float) AgencyNode::sum('credit_limit');

        return [
            'total_agencies' => AgencyNode::count(),
            'active_agencies' => AgencyNode::active()->count(),
            'total_cash_balance_pkr' => $cashBalance,
            'total_credit_limit_pkr' => $creditLimit,
            'total_operating_capital_pkr' => $cashBalance + $creditLimit,
            'pending_topups_count' => DepositTransaction::where('status', 'Pending')->count(),
            'pending_topups_amount_pkr' => (float) DepositTransaction::where('status', 'Pending')->sum('amount'),
            'verified_today_count' => DepositTransaction::where('status', 'Verified')->whereDate('transaction_date', Carbon::today())->count(),
        ];
    }

    /**
     * Export transaction ledger records as a downloadable CSV stream.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $fileName = 'deposit-ledger-export-'.now()->format('Y-m-d-His').'.csv';

        $query = $this->applyFilters(DepositTransaction::query(), $filters)->latest('transaction_date');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Transaction Ref',
                'Agency Code',
                'Agency Name',
                'Bank / Method',
                'Type',
                'Amount (PKR)',
                'Balance After (PKR)',
                'Status',
                'Verified By',
                'Transaction Date',
                'Notes',
            ]);

            $query->chunk(100, function ($transactions) use ($handle): void {
                foreach ($transactions as $txn) {
                    fputcsv($handle, [
                        $txn->reference,
                        $txn->agency_code,
                        $txn->agency_name,
                        $txn->bank,
                        $txn->type,
                        number_format((float) $txn->amount, 2, '.', ''),
                        number_format((float) $txn->balance_after, 2, '.', ''),
                        $txn->status,
                        $txn->verified_by ?? 'N/A',
                        $txn->transaction_date ? $txn->transaction_date->format('Y-m-d H:i') : '',
                        $txn->notes ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
