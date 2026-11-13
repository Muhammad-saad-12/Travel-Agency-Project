<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Modules 17 & 18</span>
                <span class="rounded-md bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">B2B Financial Engine</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Agency Deposit & Credit Ledger</h1>
            <p class="text-sm text-gray-500">Track B2B credit limits, bank deposit receipts, double-entry transaction ledgers, and sub-agent fund allocations.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button
                type="button"
                wire:click="openAllocationModal"
                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50"
            >
                <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Allocate to Sub-Agent
            </button>
            <button
                type="button"
                wire:click="openTopUpModal"
                class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Record Bank Deposit Slip
            </button>
            <button
                type="button"
                wire:click="exportCsv"
                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-emerald-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if ($successMessage)
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-semibold text-emerald-900">{{ $successMessage }}</p>
                </div>
                <button wire:click="$set('successMessage', null)" class="text-emerald-600 hover:text-emerald-800">&times;</button>
            </div>
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium text-rose-800">{{ $errorMessage }}</p>
                </div>
                <button wire:click="$set('errorMessage', null)" class="text-rose-600 hover:text-rose-800">&times;</button>
            </div>
        </div>
    @endif

    <!-- High-Level Financial KPI Bar -->
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-emerald-50/50 p-4 shadow-xs">
            <span class="text-xs font-semibold text-emerald-800">Total Operating Capital (PKR)</span>
            <div class="mt-1 text-2xl font-black text-emerald-900">
                PKR {{ number_format($statistics['total_operating_capital_pkr'] / 1000000, 2) }}M
            </div>
            <p class="mt-0.5 text-[11px] text-emerald-700">Combined credit lines & cash reserves</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-blue-700">Cash Wallet Balances</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                PKR {{ number_format($statistics['total_cash_balance_pkr'] / 1000000, 2) }}M
            </div>
            <p class="mt-0.5 text-[11px] text-gray-400">Available bank deposits on record</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-purple-700">B2B Credit Limits</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                PKR {{ number_format($statistics['total_credit_limit_pkr'] / 1000000, 2) }}M
            </div>
            <p class="mt-0.5 text-[11px] text-gray-400">Total credit lines granted to partners</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-amber-700">Pending Slip Verification</span>
            <div class="mt-1 text-2xl font-bold text-amber-800">
                {{ $statistics['pending_topups_count'] }} <span class="text-xs font-normal text-gray-500">(PKR {{ number_format($statistics['pending_topups_amount_pkr'] / 1000, 1) }}k)</span>
            </div>
            <p class="mt-0.5 text-[11px] text-gray-400">Awaiting finance desk review</p>
        </div>
    </div>

    <!-- Agency Operating Positions Card Grid -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Agency Node Balances & Limits</h3>
            <span class="text-xs text-gray-400">{{ $agencies->count() }} Registered Branches & Sub-Agents</span>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($agencies as $agency)
                <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-mono text-xs font-bold text-blue-700">{{ $agency->code }}</span>
                            <span class="ml-1 rounded px-1.5 py-0.2 text-[10px] font-semibold {{ $agency->type === 'Branch' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">{{ $agency->type }}</span>
                        </div>
                        <span class="text-[11px] text-gray-500">{{ $agency->city }}, {{ $agency->country }}</span>
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $agency->name }}</div>

                    <div class="mt-2 grid grid-cols-2 gap-1 text-[11px]">
                        <div>
                            <span class="text-gray-500">Cash Balance:</span>
                            <div class="font-bold {{ $agency->balance < 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                                PKR {{ number_format((float) $agency->balance, 2) }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500">Credit Limit:</span>
                            <div class="font-bold text-purple-700">
                                PKR {{ number_format((float) $agency->credit_limit, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 flex items-center justify-between border-t border-gray-200 pt-2 text-[11px]">
                        <span class="text-gray-500">Available: <strong class="text-gray-900">PKR {{ number_format($agency->available_capital, 2) }}</strong></span>
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                wire:click="openCreditModal({{ $agency->id }})"
                                class="text-[10px] font-medium text-blue-600 hover:underline"
                            >
                                Limit
                            </button>
                            <span class="text-gray-300">|</span>
                            <button
                                type="button"
                                wire:click="openTopUpModal({{ $agency->id }})"
                                class="text-[10px] font-medium text-emerald-600 hover:underline"
                            >
                                Top-Up
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <!-- Search -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600">Search Ledger</label>
                <div class="relative mt-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Ref, Agency, Bank, Notes..."
                        class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Agency Filter -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600">Filter Agency</label>
                <select wire:model.live="agencyId" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">All Agencies & Branches</option>
                    @foreach ($agencies as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->code }} - {{ $ag->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Transaction Type</label>
                <select wire:model.live="type" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Types</option>
                    <option value="Bank Deposit Slip">Bank Deposit Slip</option>
                    <option value="Cash Deposit">Cash Deposit</option>
                    <option value="Online Transfer">Online Transfer</option>
                    <option value="Ticket Debit">Ticket Debit</option>
                    <option value="Refund Credit">Refund Credit</option>
                    <option value="Credit Limit Allocation">Credit Allocation</option>
                    <option value="Sub-Agent Transfer Out">Sub-Agent Transfer Out</option>
                    <option value="Sub-Agent Transfer In">Sub-Agent Transfer In</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Verification</label>
                <select wire:model.live="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="Verified">Verified</option>
                    <option value="Pending">Pending</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div class="md:col-span-2 flex items-end gap-1">
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600">From Date</label>
                    <input
                        type="date"
                        wire:model.live="date_from"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>
                <button
                    type="button"
                    wire:click="resetFilters"
                    title="Reset filters"
                    class="rounded-lg border border-gray-300 bg-gray-50 px-2.5 py-2 text-sm text-gray-600 hover:bg-gray-100"
                >
                    &times;
                </button>
            </div>
        </div>
    </div>

    <!-- Transactions Ledger Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Txn Reference</th>
                        <th class="px-4 py-3 font-semibold">Agency Node</th>
                        <th class="px-4 py-3 font-semibold">Bank / Method</th>
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">Amount (PKR)</th>
                        <th class="px-4 py-3 font-semibold">Running Balance</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Date & Approver</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($transactions as $txn)
                        <tr class="hover:bg-gray-50/80">
                            <!-- Ref -->
                            <td class="px-4 py-3 font-mono font-bold text-gray-900">
                                {{ $txn->reference }}
                            </td>

                            <!-- Agency -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $txn->agency_name }}</div>
                                <div class="font-mono text-[11px] text-blue-600">{{ $txn->agency_code }}</div>
                            </td>

                            <!-- Bank -->
                            <td class="px-4 py-3 text-gray-700">
                                <div class="font-medium">{{ $txn->bank }}</div>
                                @if ($txn->proof_file)
                                    <span class="font-mono text-[10px] text-gray-400">Slip: {{ $txn->proof_file }}</span>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3">
                                <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $txn->amount >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $txn->type }}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td class="px-4 py-3 font-mono font-bold {{ $txn->amount >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $txn->amount >= 0 ? '+' : '' }}PKR {{ number_format((float) $txn->amount, 2) }}
                            </td>

                            <!-- Balance After -->
                            <td class="px-4 py-3 font-mono text-gray-900 font-semibold">
                                PKR {{ number_format((float) $txn->balance_after, 2) }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $txn->status === 'Verified' ? 'bg-emerald-100 text-emerald-800' : ($txn->status === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $txn->status }}
                                </span>
                            </td>

                            <!-- Date & Verified By -->
                            <td class="px-4 py-3 text-gray-500">
                                <div>{{ $txn->transaction_date ? $txn->transaction_date->format('d M Y H:i') : '' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $txn->verified_by ?? 'Pending' }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                @if ($txn->status === 'Pending')
                                    <button
                                        type="button"
                                        wire:click="openVerifyModal({{ $txn->id }})"
                                        class="rounded bg-emerald-600 px-2 py-1 text-[11px] font-semibold text-white hover:bg-emerald-700"
                                    >
                                        Verify Slip
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">&#10003;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                No ledger transactions found matching filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Record Bank Deposit Top-up Modal -->
    @if ($showTopUpModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Record Bank Deposit Slip Top-Up</h3>
                <p class="text-xs text-gray-500 mt-1">Upload and record cash or bank deposit receipt for an agency operating wallet.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Target Agency Node</label>
                        <select wire:model="topUpAgencyId" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach ($agencies as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->code }} - {{ $ag->name }}</option>
                            @endforeach
                        </select>
                        @error('topUpAgencyId') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Bank / Channel</label>
                            <select wire:model="topUpBank" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="HBL (Habib Bank Limited)">HBL (Habib Bank Limited)</option>
                                <option value="Meezan Bank Ltd">Meezan Bank Ltd</option>
                                <option value="MCB Bank Limited">MCB Bank Limited</option>
                                <option value="UBL (United Bank Limited)">UBL (United Bank Limited)</option>
                                <option value="Bank Alfalah">Bank Alfalah</option>
                                <option value="Raast Instant Pay">Raast Instant Pay</option>
                                <option value="Cash Counter Deposit">Cash Counter Deposit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700">Deposit Amount (PKR)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="topUpAmount"
                                placeholder="e.g. 500000.00"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            />
                            @error('topUpAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Deposit Slip Number / Ref</label>
                        <input
                            type="text"
                            wire:model="topUpProof"
                            placeholder="e.g. HBL-SLIP-778844"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Notes / Remarks</label>
                        <textarea
                            wire:model="topUpNotes"
                            rows="2"
                            placeholder="Branch deposit details, teller reference, etc."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50/50 p-2 text-xs font-medium text-emerald-900 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="topUpAutoVerify"
                                class="rounded border-emerald-400 text-emerald-600 focus:ring-emerald-500"
                            />
                            <span>Instantly verify and credit balance now (Chief Cashier privilege)</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeTopUpModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="submitTopUp(app('App\Services\DepositLedgerService'))" class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                        Submit Deposit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Verify Deposit Modal -->
    @if ($showVerifyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Verify Deposit Slip Receipt</h3>
                <p class="text-xs text-gray-500 mt-1">Approve bank confirmation to credit agency wallet balance immediately.</p>

                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700">Verification Remarks</label>
                    <textarea
                        wire:model="verifierNotes"
                        rows="2"
                        placeholder="Bank statement confirmation reference..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    ></textarea>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <button type="button" wire:click="rejectTransaction(app('App\Services\DepositLedgerService'))" class="rounded-lg bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                        Reject Slip
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="closeVerifyModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="button" wire:click="verifyTransaction(app('App\Services\DepositLedgerService'))" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                            Approve & Credit Balance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Sub-Agent Balance Allocation Modal -->
    @if ($showAllocationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Allocate Funds to Sub-Agent</h3>
                <p class="text-xs text-gray-500 mt-1">Transfer operating balance between parent agency and sub-agent node.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Source Agency (Debit From)</label>
                        <select wire:model="allocSourceId" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach ($agencies as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->code }} - {{ $ag->name }} (Avail: PKR {{ number_format((float) $ag->balance, 2) }})</option>
                            @endforeach
                        </select>
                        @error('allocSourceId') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Target Sub-Agent (Credit To)</label>
                        <select wire:model="allocTargetId" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach ($agencies as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->code }} - {{ $ag->name }}</option>
                            @endforeach
                        </select>
                        @error('allocTargetId') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Allocation Amount (PKR)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="allocAmount"
                            placeholder="e.g. 100000.00"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('allocAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Allocation Notes</label>
                        <textarea
                            wire:model="allocNotes"
                            rows="2"
                            placeholder="Purpose of allocation..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeAllocationModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="submitAllocation(app('App\Services\DepositLedgerService'))" class="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-700">
                        Execute Allocation
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Update Credit Limit Modal -->
    @if ($showCreditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Adjust B2B Credit Limit</h3>
                <p class="text-xs text-gray-500 mt-1">Set maximum authorized overdraft credit for this agency.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">New Credit Limit (PKR)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="newCreditLimit"
                            placeholder="e.g. 1500000.00"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('newCreditLimit') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Credit Committee Authorization Notes</label>
                        <textarea
                            wire:model="creditNotes"
                            rows="2"
                            placeholder="Bank guarantee, security deposit, or committee approval ref..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeCreditModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="updateCreditLimit(app('App\Services\DepositLedgerService'))" class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                        Update Limit
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
