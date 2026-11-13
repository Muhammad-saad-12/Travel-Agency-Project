<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Modules 15 & 16</span>
                <span class="rounded-md bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Offline Back-Office</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Offline Ticketing & Refund Queue</h1>
            <p class="text-sm text-gray-500">Manage manual ticket issuance, urgent queues, refunds, voids, and schedule changes across airlines.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">
                <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                Urgent Priority Items: {{ $statistics['urgent_count'] }}
            </span>
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

    <!-- Queue Tabs & KPIs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap gap-4 text-xs font-semibold">
            <button
                type="button"
                wire:click="setRequestType('all')"
                class="border-b-2 py-3 px-1 {{ $requestType === 'all' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
            >
                All Queues ({{ $statistics['total_requests'] }})
            </button>
            <button
                type="button"
                wire:click="setRequestType('ticketing')"
                class="border-b-2 py-3 px-1 flex items-center gap-1.5 {{ $requestType === 'ticketing' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
            >
                Ticketing Issuance
                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] text-blue-700">{{ $statistics['ticketing_pending'] }}</span>
            </button>
            <button
                type="button"
                wire:click="setRequestType('refund')"
                class="border-b-2 py-3 px-1 flex items-center gap-1.5 {{ $requestType === 'refund' ? 'border-amber-600 text-amber-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
            >
                Refund Requests
                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] text-amber-700">{{ $statistics['refund_pending'] }}</span>
            </button>
            <button
                type="button"
                wire:click="setRequestType('void')"
                class="border-b-2 py-3 px-1 flex items-center gap-1.5 {{ $requestType === 'void' ? 'border-rose-600 text-rose-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
            >
                Ticket Voids
                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] text-rose-700">{{ $statistics['void_pending'] }}</span>
            </button>
            <button
                type="button"
                wire:click="setRequestType('schedule_change')"
                class="border-b-2 py-3 px-1 flex items-center gap-1.5 {{ $requestType === 'schedule_change' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
            >
                Schedule Changes
                <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] text-purple-700">{{ $statistics['schedule_pending'] }}</span>
            </button>
        </nav>
    </div>

    <!-- Filters Toolbar -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-600">Search Queue</label>
                <div class="relative mt-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="PNR, Ref, Pax Name, Ticket, Route..."
                        class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Airline Filter -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600">Airline Carrier</label>
                <select wire:model.live="airline" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Airlines</option>
                    @foreach ($airlines as $al)
                        <option value="{{ $al }}">{{ $al }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600">Processing Status</label>
                <select wire:model.live="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="Applied">Applied (Pending)</option>
                    <option value="Processing">Processing</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <!-- Urgent Toggle -->
            <div class="md:col-span-2 flex items-end">
                <label class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-700 cursor-pointer w-full hover:bg-gray-100">
                    <input
                        type="checkbox"
                        wire:model.live="urgentOnly"
                        value="1"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    />
                    <span>Urgent Only</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Priority & Type</th>
                        <th class="px-4 py-3 font-semibold">PNR / Booking Ref</th>
                        <th class="px-4 py-3 font-semibold">Airline & Sector</th>
                        <th class="px-4 py-3 font-semibold">Passenger / Ticket</th>
                        <th class="px-4 py-3 font-semibold">Financials (PKR)</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Assigned Agent</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($requests as $req)
                        <tr class="hover:bg-gray-50/80 {{ $req->is_urgent ? 'bg-rose-50/30' : '' }}">
                            <!-- Priority & Type -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="toggleUrgent({{ $req->id }}, app('App\Services\OfflineTicketingService'))"
                                        title="Click to toggle priority"
                                        class="text-sm {{ $req->is_urgent ? 'text-rose-600' : 'text-gray-300 hover:text-gray-500' }}"
                                    >
                                        &#9733;
                                    </button>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $req->request_type === 'ticketing' ? 'bg-blue-100 text-blue-800' : ($req->request_type === 'refund' ? 'bg-amber-100 text-amber-800' : ($req->request_type === 'void' ? 'bg-rose-100 text-rose-800' : 'bg-purple-100 text-purple-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $req->request_type)) }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ $req->requested_at ? $req->requested_at->format('d M H:i') : '' }}
                                </div>
                            </td>

                            <!-- PNR / Booking Ref -->
                            <td class="px-4 py-3">
                                <div class="font-mono text-sm font-bold text-gray-900">{{ $req->pnr }}</div>
                                <div class="text-[11px] text-gray-500">{{ $req->booking_ref }}</div>
                            </td>

                            <!-- Airline & Sector -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $req->airline }}</div>
                                <div class="text-[11px] text-gray-600 font-mono">{{ $req->route }}</div>
                                <div class="text-[10px] text-gray-400">{{ $req->travel_date ? $req->travel_date->format('d M Y') : '' }}</div>
                            </td>

                            <!-- Passenger / Ticket -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $req->passenger_name }}</div>
                                <div class="font-mono text-[11px] text-gray-600">
                                    {{ $req->ticket_number ?? 'Pending issuance' }}
                                </div>
                            </td>

                            <!-- Financials PKR -->
                            <td class="px-4 py-3">
                                @if ($req->refund_amount > 0)
                                    <div class="text-xs font-bold text-emerald-700">Refund: PKR {{ number_format((float) $req->refund_amount, 2) }}</div>
                                @endif
                                @if ($req->penalty_amount > 0)
                                    <div class="text-[11px] text-rose-600">Fee: PKR {{ number_format((float) $req->penalty_amount, 2) }}</div>
                                @endif
                                @if ($req->refund_amount <= 0 && $req->penalty_amount <= 0)
                                    <span class="text-gray-400 text-[11px]">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $req->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : ($req->status === 'Processing' ? 'bg-sky-100 text-sky-800' : ($req->status === 'Rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')) }}">
                                    {{ $req->status }}
                                </span>
                            </td>

                            <!-- Assigned Agent -->
                            <td class="px-4 py-3">
                                <button
                                    type="button"
                                    wire:click="openAssignModal({{ $req->id }})"
                                    class="text-xs font-medium text-gray-700 hover:text-blue-600 underline"
                                >
                                    {{ $req->assigned_user ?? 'Unassigned' }}
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($req->request_type === 'ticketing' && $req->status !== 'Completed')
                                        <button
                                            type="button"
                                            wire:click="openTicketingModal({{ $req->id }})"
                                            class="rounded bg-emerald-600 px-2 py-1 text-[11px] font-semibold text-white hover:bg-emerald-700"
                                        >
                                            Issue Ticket
                                        </button>
                                    @endif

                                    @if ($req->request_type === 'refund' && $req->status !== 'Completed')
                                        <button
                                            type="button"
                                            wire:click="openRefundModal({{ $req->id }})"
                                            class="rounded bg-amber-600 px-2 py-1 text-[11px] font-semibold text-white hover:bg-amber-700"
                                        >
                                            Process Refund
                                        </button>
                                    @endif

                                    @if ($req->status === 'Applied')
                                        <button
                                            type="button"
                                            wire:click="updateStatus({{ $req->id }}, 'Processing', app('App\Services\OfflineTicketingService'))"
                                            class="rounded border border-gray-300 bg-white px-2 py-1 text-[11px] font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Start
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                No offline requests found in queue.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- Issue Ticket Modal -->
    @if ($showTicketingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Issue E-Ticket for Offline Request</h3>
                <p class="text-xs text-gray-500 mt-1">Submit the issued ticket coupon and assign handling desk officer.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">E-Ticket Number</label>
                        <input
                            type="text"
                            wire:model="ticketNumber"
                            placeholder="e.g. 214-2200119933"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('ticketNumber') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Assigned Ticketing Agent</label>
                        <input
                            type="text"
                            wire:model="assignedUser"
                            placeholder="Desk Officer Name"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('assignedUser') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Remarks / GDS Terminal Notes</label>
                        <textarea
                            wire:model="ticketingRemarks"
                            rows="2"
                            placeholder="Optional notes or PNR endorsement details..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeTicketingModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="processTicketing(app('App\Services\OfflineTicketingService'))" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Mark Ticketed & Completed
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Process Refund Modal -->
    @if ($showRefundModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Process Refund & Cancellation</h3>
                <p class="text-xs text-gray-500 mt-1">Calculate airline penalty fee and customer refund amount in PKR.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Refund Amount (PKR)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="refundAmount"
                            placeholder="e.g. 85000.00"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                        @error('refundAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Cancellation Penalty / Airline Fee (PKR)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="penaltyAmount"
                            placeholder="e.g. 15000.00"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                        @error('penaltyAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Refund Calculation Notes</label>
                        <textarea
                            wire:model="refundRemarks"
                            rows="2"
                            placeholder="Airline policy deductions, waiver codes, etc."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeRefundModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="processRefund(app('App\Services\OfflineTicketingService'))" class="rounded-lg bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                        Approve & Process Refund
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Assign Modal -->
    @if ($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Assign Operations Officer</h3>

                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700">Officer Name</label>
                    <input
                        type="text"
                        wire:model="assigneeName"
                        placeholder="e.g. Tariq Mehmood"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    @error('assigneeName') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeAssignModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="assignRequest(app('App\Services\OfflineTicketingService'))" class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                        Assign Request
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
