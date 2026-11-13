<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Module 14</span>
                <span class="rounded-md bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Pakistan Payment Gateways</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Payment Management</h1>
            <p class="text-sm text-gray-500">Track and reconcile multi-channel receipts across Raast, 1Link, JazzCash, EasyPaisa, and Cards.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Record New Payment
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

    <!-- Channel KPI Cards Grid -->
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <!-- Raast SBP -->
        <div class="rounded-xl border border-emerald-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="rounded bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-800">Raast SBP</span>
                <span class="text-xs font-semibold text-gray-500">{{ $statistics['raast_count'] }} Txns</span>
            </div>
            <div class="mt-2 text-xl font-black text-gray-900">
                PKR {{ number_format($statistics['raast_volume_pkr'] / 1000, 1) }}k
            </div>
            <p class="mt-1 text-[11px] text-emerald-700">Instant 0% fee P2P/P2M transfers</p>
        </div>

        <!-- 1Link Network -->
        <div class="rounded-xl border border-blue-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="rounded bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-800">1Link 1Bill</span>
                <span class="text-xs font-semibold text-gray-500">{{ $statistics['onelink_count'] }} Txns</span>
            </div>
            <div class="mt-2 text-xl font-black text-gray-900">
                PKR {{ number_format($statistics['onelink_volume_pkr'] / 1000, 1) }}k
            </div>
            <p class="mt-1 text-[11px] text-blue-700">Interbank biller direct settlement</p>
        </div>

        <!-- Mobile Wallets -->
        <div class="rounded-xl border border-amber-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-800">JazzCash & EasyPaisa</span>
                <span class="text-xs font-semibold text-gray-500">{{ $statistics['wallet_count'] }} Txns</span>
            </div>
            <div class="mt-2 text-xl font-black text-gray-900">
                PKR {{ number_format($statistics['wallet_volume_pkr'] / 1000, 1) }}k
            </div>
            <p class="mt-1 text-[11px] text-amber-700">Digital wallet QR & mobile pay</p>
        </div>

        <!-- Cards -->
        <div class="rounded-xl border border-purple-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="rounded bg-purple-100 px-2 py-0.5 text-xs font-bold text-purple-800">Visa & Mastercard</span>
                <span class="text-xs font-semibold text-gray-500">{{ $statistics['card_count'] }} Txns</span>
            </div>
            <div class="mt-2 text-xl font-black text-gray-900">
                PKR {{ number_format($statistics['card_volume_pkr'] / 1000, 1) }}k
            </div>
            <p class="mt-1 text-[11px] text-purple-700">3D Secure debit & credit gateway</p>
        </div>
    </div>

    <!-- Secondary Volume Ribbon -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50/80 p-3 shadow-xs">
        <div class="grid grid-cols-2 gap-3 text-center sm:grid-cols-4">
            <div>
                <span class="text-[11px] text-gray-500 font-medium">Verified Net Collected:</span>
                <div class="font-bold text-emerald-800">PKR {{ number_format($statistics['total_net_volume_pkr'], 2) }}</div>
            </div>
            <div>
                <span class="text-[11px] text-gray-500 font-medium">Merchant Gateway Fees:</span>
                <div class="font-bold text-gray-700">PKR {{ number_format($statistics['total_gateway_fees_pkr'], 2) }}</div>
            </div>
            <div>
                <span class="text-[11px] text-gray-500 font-medium">Pending Clearances:</span>
                <div class="font-bold text-amber-700">PKR {{ number_format($statistics['total_pending_volume_pkr'], 2) }} ({{ $statistics['pending_count'] }})</div>
            </div>
            <div>
                <span class="text-[11px] text-gray-500 font-medium">Refunded / Reversed:</span>
                <div class="font-bold text-rose-700">{{ $statistics['refunded_count'] }} Txns</div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-600">Search Payments</label>
                <div class="relative mt-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Txn Ref, Booking, Customer, SBP Ref..."
                        class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Channel Category -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600">Channel Category</label>
                <select wire:model.live="channelCategory" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Channels</option>
                    <option value="raast">Raast SBP</option>
                    <option value="1link">1Link Bank Transfer</option>
                    <option value="wallet">JazzCash / EasyPaisa</option>
                    <option value="card">Credit / Debit Card</option>
                    <option value="bank">Direct Bank Deposit</option>
                </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Status</label>
                <select wire:model.live="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="Verified">Verified</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                    <option value="Refunded">Refunded</option>
                </select>
            </div>

            <!-- Date Filters -->
            <div class="md:col-span-3 flex items-end gap-1">
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600">Date From</label>
                    <input
                        type="date"
                        wire:model.live="date_from"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600">Date To</label>
                    <input
                        type="date"
                        wire:model.live="date_to"
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

    <!-- Payments Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Txn Reference</th>
                        <th class="px-4 py-3 font-semibold">Booking Ref</th>
                        <th class="px-4 py-3 font-semibold">Customer Details</th>
                        <th class="px-4 py-3 font-semibold">Payment Channel</th>
                        <th class="px-4 py-3 font-semibold">Gross Amount (PKR)</th>
                        <th class="px-4 py-3 font-semibold">Net Amount (PKR)</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Settled Date & By</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50/80">
                            <!-- Ref -->
                            <td class="px-4 py-3 font-mono font-bold text-gray-900">
                                {{ $payment->transaction_ref }}
                                @if ($payment->gateway_ref)
                                    <div class="text-[10px] text-gray-400 font-mono">{{ $payment->gateway_ref }}</div>
                                @endif
                            </td>

                            <!-- Booking -->
                            <td class="px-4 py-3">
                                @if ($payment->booking_id)
                                    <span class="rounded bg-sky-100 px-1.5 py-0.5 font-mono text-xs font-semibold text-sky-800">
                                        {{ $payment->booking_id }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-[11px]">Unlinked</span>
                                @endif
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $payment->customer_name }}</div>
                                <div class="text-[11px] text-gray-500">{{ $payment->customer_phone ?? '—' }}</div>
                            </td>

                            <!-- Channel -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $payment->channel_category === 'raast' ? 'bg-emerald-100 text-emerald-800' : ($payment->channel_category === '1link' ? 'bg-blue-100 text-blue-800' : ($payment->channel_category === 'wallet' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800')) }}">
                                    {{ $payment->channel }}
                                </span>
                            </td>

                            <!-- Gross Amount -->
                            <td class="px-4 py-3 font-mono font-bold text-gray-900">
                                PKR {{ number_format((float) $payment->amount, 2) }}
                                @if ($payment->fee_amount > 0)
                                    <div class="text-[10px] text-rose-600 font-normal">Fee: PKR {{ number_format((float) $payment->fee_amount, 2) }}</div>
                                @endif
                            </td>

                            <!-- Net Amount -->
                            <td class="px-4 py-3 font-mono font-bold text-emerald-700">
                                PKR {{ number_format((float) $payment->net_amount, 2) }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $payment->status === 'Verified' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'Pending' ? 'bg-amber-100 text-amber-800' : ($payment->status === 'Refunded' ? 'bg-sky-100 text-sky-800' : 'bg-rose-100 text-rose-800')) }}">
                                    {{ $payment->status }}
                                </span>
                            </td>

                            <!-- Date & Verified By -->
                            <td class="px-4 py-3 text-gray-500">
                                <div>{{ $payment->payment_date ? $payment->payment_date->format('d M Y H:i') : '' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $payment->verified_by ?? 'Pending Review' }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="openDetailModal({{ $payment->id }})"
                                        class="rounded-md border border-gray-300 bg-white px-2 py-1 text-[11px] font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        View
                                    </button>

                                    @if ($payment->status === 'Pending')
                                        <button
                                            type="button"
                                            wire:click="openVerifyModal({{ $payment->id }})"
                                            class="rounded-md bg-emerald-600 px-2 py-1 text-[11px] font-semibold text-white hover:bg-emerald-700"
                                        >
                                            Verify
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="markFailed({{ $payment->id }}, app('App\Services\PaymentService'))"
                                            class="rounded-md bg-rose-50 px-2 py-1 text-[11px] font-semibold text-rose-700 hover:bg-rose-100"
                                        >
                                            Fail
                                        </button>
                                    @endif

                                    @if ($payment->status === 'Verified')
                                        <button
                                            type="button"
                                            wire:click="openRefundModal({{ $payment->id }})"
                                            class="rounded-md border border-gray-300 bg-gray-50 px-2 py-1 text-[11px] font-medium text-gray-600 hover:bg-gray-100"
                                        >
                                            Refund
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                No payment transactions found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Record Payment Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <h3 class="text-base font-bold text-gray-900">Record Customer Payment</h3>
                <p class="text-xs text-gray-500 mt-1">Reconcile transaction across Pakistani banking rails or digital wallets.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Link to Booking Reference (Optional)</label>
                        <select wire:model.live="newBookingId" wire:change="onBookingSelect" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Direct / Standalone Payment</option>
                            @foreach ($recentBookings as $bk)
                                <option value="{{ $bk->booking_reference }}">{{ $bk->booking_reference }} - {{ $bk->customer_name }} (PKR {{ number_format((float) $bk->total_amount, 2) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Customer Full Name</label>
                            <input
                                type="text"
                                wire:model="newCustomerName"
                                placeholder="Customer Name"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            />
                            @error('newCustomerName') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700">Phone Number</label>
                            <input
                                type="text"
                                wire:model="newCustomerPhone"
                                placeholder="+92 300 0000000"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Payment Channel</label>
                        <select wire:model.live="newChannel" wire:change="onChannelChange" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="Raast P2P/P2M">Raast P2P/P2M (State Bank of Pakistan - 0% Fee)</option>
                            <option value="1Link Bank Transfer">1Link Bank Transfer (1Bill Direct)</option>
                            <option value="JazzCash Mobile Wallet">JazzCash Mobile Wallet (1.5% Fee)</option>
                            <option value="EasyPaisa Digital Wallet">EasyPaisa Digital Wallet (1.5% Fee)</option>
                            <option value="Credit/Debit Card (Visa/Mastercard)">Credit/Debit Card (Visa/Mastercard - 2.5% Fee)</option>
                            <option value="Bank Deposit Slip">Bank Deposit Slip (Direct Counter Cash)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Gross Amount (PKR)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="newAmount"
                                wire:keyup="onChannelChange"
                                placeholder="e.g. 75000.00"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            />
                            @error('newAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700">Gateway Fee (PKR)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="newFeeAmount"
                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-600 shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Gateway / Bank Reference ID</label>
                        <input
                            type="text"
                            wire:model="newGatewayRef"
                            placeholder="e.g. SBP-RAAST-88192 or 1LINK-9900"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Notes / Remarks</label>
                        <textarea
                            wire:model="newNotes"
                            rows="2"
                            placeholder="Payment reconciliation notes..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50/50 p-2 text-xs font-medium text-emerald-900 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="newAutoVerify"
                                class="rounded border-emerald-400 text-emerald-600 focus:ring-emerald-500"
                            />
                            <span>Instantly verify & mark linked booking as Paid</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeCreateModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="recordPayment(app('App\Services\PaymentService'))" class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                        Record Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Verify Payment Modal -->
    @if ($showVerifyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Verify Payment Transaction</h3>
                <p class="text-xs text-gray-500 mt-1">Confirm bank reconciliation. This will mark linked booking status as Paid.</p>

                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700">Verification Notes</label>
                    <textarea
                        wire:model="verifierNotes"
                        rows="2"
                        placeholder="Reconciled with 1Link / Raast bank statement..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    ></textarea>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeVerifyModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="verifyPayment(app('App\Services\PaymentService'))" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Confirm Verification
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Refund Modal -->
    @if ($showRefundModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Process Payment Refund</h3>
                <p class="text-xs text-gray-500 mt-1">Initiate refund for customer across original payment channel.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Refund Amount (PKR)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="refundAmount"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('refundAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Refund Reason</label>
                        <textarea
                            wire:model="refundReason"
                            rows="2"
                            placeholder="Flight cancellation, fare recalculation..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeRefundModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="processRefund(app('App\Services\PaymentService'))" class="rounded-lg bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Execute Refund
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Transaction Details</h3>
                        <span class="font-mono text-xs text-blue-600">{{ $selectedPayment->transaction_ref }}</span>
                    </div>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2 rounded-lg bg-gray-50 p-3">
                        <div>
                            <span class="text-gray-500">Customer:</span>
                            <div class="font-bold text-gray-900">{{ $selectedPayment->customer_name }}</div>
                            <div class="text-gray-600">{{ $selectedPayment->customer_phone ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Linked Booking:</span>
                            <div class="font-bold font-mono text-gray-900">{{ $selectedPayment->booking_id ?? 'None' }}</div>
                            <div class="text-gray-600">{{ $selectedPayment->channel }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 rounded-lg border border-gray-200 p-3">
                        <div>
                            <span class="text-gray-500">Gross:</span>
                            <div class="font-bold text-gray-900">PKR {{ number_format((float) $selectedPayment->amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Fee:</span>
                            <div class="font-bold text-rose-600">PKR {{ number_format((float) $selectedPayment->fee_amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Net Settled:</span>
                            <div class="font-bold text-emerald-700">PKR {{ number_format((float) $selectedPayment->net_amount, 2) }}</div>
                        </div>
                    </div>

                    @if ($selectedPayment->gateway_ref)
                        <div>
                            <span class="text-gray-500">Gateway SBP / Bank Reference:</span>
                            <div class="font-mono font-bold text-gray-900">{{ $selectedPayment->gateway_ref }}</div>
                        </div>
                    @endif

                    @if ($selectedPayment->notes)
                        <div>
                            <span class="text-gray-500">Notes & Audit Trail:</span>
                            <div class="mt-0.5 rounded bg-amber-50 p-2 text-amber-900">{{ $selectedPayment->notes }}</div>
                        </div>
                    @endif

                    <div class="border-t border-gray-100 pt-2 flex justify-between text-gray-500">
                        <span>Created: {{ $selectedPayment->payment_date ? $selectedPayment->payment_date->format('d M Y H:i') : '' }}</span>
                        <span>Approver: {{ $selectedPayment->verified_by ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="closeDetailModal" class="rounded-lg bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
