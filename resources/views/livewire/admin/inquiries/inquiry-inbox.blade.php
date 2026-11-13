<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Flight Inquiry Inbox</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                    Module 5
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                    GDS Ticketing Desk
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Review incoming passenger flight inquiries, calculate lowest GDS airfares in PKR, send customer quotes, and track bookings.
            </p>
        </div>
    </div>

    <!-- Flash message -->
    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        @foreach (['All', 'Pending', 'Contacted', 'Quoted', 'Booked'] as $status)
            <button
                type="button"
                wire:click="setStatusFilter('{{ $status }}')"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer flex items-center space-x-2 {{ $statusFilter === $status ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
            >
                <span>{{ $status }}</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $statusFilter === $status ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $counts[$status] ?? 0 }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <label for="inq_search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search Inquiries</label>
                <input
                    type="text"
                    id="inq_search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by Reference (e.g. INQ-2026-XXXX), customer name, phone, or route..."
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                />
            </div>

            <div>
                <label for="inq_type_filter" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Trip Type</label>
                <select
                    id="inq_type_filter"
                    wire:model.live="tripTypeFilter"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Trip Types</option>
                    <option value="round_trip">Round Trip</option>
                    <option value="one_way">One Way</option>
                    <option value="multi_city">Multi-City</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                <thead class="bg-slate-50/80 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Reference</th>
                        <th class="px-6 py-3.5">Passenger / Client</th>
                        <th class="px-6 py-3.5">Flight Route</th>
                        <th class="px-6 py-3.5">Dates & Passengers</th>
                        <th class="px-6 py-3.5">Quoted Fare (PKR)</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Reference -->
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-indigo-700 text-xs bg-indigo-50 px-2 py-1 rounded-md border border-indigo-200">
                                    {{ $inquiry->reference_no }}
                                </span>
                                <div class="text-[11px] text-slate-400 mt-1">
                                    {{ $inquiry->created_at?->diffForHumans() }}
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $inquiry->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $inquiry->customer_phone }}</div>
                                <div class="text-xs text-slate-400">{{ $inquiry->customer_email }}</div>
                            </td>

                            <!-- Route -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 flex items-center space-x-1.5">
                                    <span>{{ $inquiry->origin }}</span>
                                    <span class="text-indigo-500">&rarr;</span>
                                    <span>{{ $inquiry->destination }}</span>
                                </div>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 mt-1">
                                    {{ ucwords(str_replace('_', ' ', $inquiry->trip_type)) }}
                                </span>
                            </td>

                            <!-- Dates & Passengers -->
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-800">
                                    <strong>Dep:</strong> {{ $inquiry->departure_date?->format('M d, Y') }}
                                </div>
                                @if ($inquiry->return_date)
                                    <div class="text-xs text-slate-500">
                                        <strong>Ret:</strong> {{ $inquiry->return_date?->format('M d, Y') }}
                                    </div>
                                @endif
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $inquiry->adults }} Adults{{ $inquiry->children ? ", {$inquiry->children} Ch" : '' }}{{ $inquiry->infants ? ", {$inquiry->infants} Inf" : '' }} &bull; {{ $inquiry->cabin_class }}
                                </div>
                            </td>

                            <!-- Quote (PKR) -->
                            <td class="px-6 py-4">
                                @if ($inquiry->quoted_price_pkr)
                                    <div class="font-bold text-slate-900 text-sm">
                                        PKR {{ number_format($inquiry->quoted_price_pkr) }}
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="openQuoteModal({{ $inquiry->id }})"
                                        class="text-[11px] text-indigo-600 hover:text-indigo-800 font-semibold cursor-pointer"
                                    >
                                        Edit Quote
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="openQuoteModal({{ $inquiry->id }})"
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-300 hover:bg-amber-100 transition cursor-pointer"
                                    >
                                        + Add PKR Quote
                                    </button>
                                @endif
                            </td>

                            <!-- Status Dropdown -->
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'Pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'Contacted' => 'bg-sky-100 text-sky-800 border-sky-200',
                                        'Quoted' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'Booked' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    ];
                                @endphp
                                <select
                                    wire:change="updateQuickStatus({{ $inquiry->id }}, $event.target.value)"
                                    class="text-xs font-bold rounded-lg px-2 py-1 border {{ $statusClasses[$inquiry->status] ?? 'bg-slate-100 text-slate-700' }} cursor-pointer"
                                >
                                    <option value="Pending" {{ $inquiry->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Contacted" {{ $inquiry->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="Quoted" {{ $inquiry->status === 'Quoted' ? 'selected' : '' }}>Quoted</option>
                                    <option value="Booked" {{ $inquiry->status === 'Booked' ? 'selected' : '' }}>Booked</option>
                                </select>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <button
                                    type="button"
                                    wire:click="openDetailModal({{ $inquiry->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold px-2 py-1 rounded hover:bg-indigo-50 transition cursor-pointer"
                                >
                                    Details
                                </button>
                                <button
                                    type="button"
                                    wire:click="deleteInquiry({{ $inquiry->id }})"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold px-2 py-1 rounded hover:bg-rose-50 transition cursor-pointer"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm">No flight inquiries found under selected filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $inquiries->links() }}
        </div>
    </div>

    <!-- Quick Quote Modal -->
    @if ($showQuoteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Update Flight Quote</h3>
                    <button type="button" wire:click="$set('showQuoteModal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveQuote" class="space-y-4">
                    <div>
                        <label for="quote_amt" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Quoted Price (PKR) <span class="text-rose-500">*</span></label>
                        <input
                            type="number"
                            id="quote_amt"
                            wire:model="quotePrice"
                            placeholder="e.g. 185000"
                            min="0"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300 font-bold text-slate-900"
                        />
                        @error('quotePrice') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="quote_st" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Update Status</label>
                        <select id="quote_st" wire:model="quoteStatus" class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300">
                            <option value="Quoted">Quoted</option>
                            <option value="Contacted">Contacted</option>
                            <option value="Booked">Booked</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="$set('showQuoteModal', false)"
                            class="px-4 py-2 rounded-xl border border-slate-300 text-sm font-medium text-slate-700"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-xs"
                        >
                            Save Quotation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedInquiry)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-2xl border border-slate-100 my-8">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold text-slate-900">Inquiry Details</span>
                        <span class="font-mono font-bold text-indigo-700 text-xs bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                            {{ $selectedInquiry->reference_no }}
                        </span>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-sm text-slate-700">
                    <!-- Customer Box -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <h4 class="text-xs font-bold uppercase text-slate-500">Passenger Contact Details</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div><strong>Name:</strong> {{ $selectedInquiry->customer_name }}</div>
                            <div><strong>Phone:</strong> {{ $selectedInquiry->customer_phone }}</div>
                            <div><strong>Email:</strong> {{ $selectedInquiry->customer_email }}</div>
                        </div>
                    </div>

                    <!-- Flight Itinerary -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <h4 class="text-xs font-bold uppercase text-slate-500">Requested Flight Itinerary</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div><strong>Trip Type:</strong> {{ ucwords(str_replace('_', ' ', $selectedInquiry->trip_type)) }}</div>
                            <div><strong>Cabin Class:</strong> {{ $selectedInquiry->cabin_class }}</div>
                            <div><strong>Origin:</strong> {{ $selectedInquiry->origin }}</div>
                            <div><strong>Destination:</strong> {{ $selectedInquiry->destination }}</div>
                            <div><strong>Departure:</strong> {{ $selectedInquiry->departure_date?->format('F d, Y') }}</div>
                            <div><strong>Return:</strong> {{ $selectedInquiry->return_date ? $selectedInquiry->return_date->format('F d, Y') : 'N/A' }}</div>
                        </div>

                        @if ($selectedInquiry->trip_type === 'multi_city' && !empty($selectedInquiry->multi_city_segments))
                            <div class="mt-3 pt-3 border-t border-slate-200 space-y-1">
                                <span class="text-xs font-bold text-slate-600">Multi-City Segments:</span>
                                @foreach ($selectedInquiry->multi_city_segments as $sIdx => $seg)
                                    <div class="text-xs text-slate-600 pl-2">
                                        Leg {{ $sIdx + 1 }}: <strong>{{ $seg['origin'] ?? '' }}</strong> &rarr; <strong>{{ $seg['destination'] ?? '' }}</strong> on {{ $seg['date'] ?? '' }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Passenger Counts & Quote -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                            <span class="text-xs font-bold uppercase text-slate-500 block">Passengers Breakdown</span>
                            <div class="text-xs space-y-1">
                                <div>Adults (12+): <strong>{{ $selectedInquiry->adults }}</strong></div>
                                <div>Children (2-11): <strong>{{ $selectedInquiry->children }}</strong></div>
                                <div>Infants (&lt;2): <strong>{{ $selectedInquiry->infants }}</strong></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100 space-y-1">
                            <span class="text-xs font-bold uppercase text-indigo-600 block">Financial Quote</span>
                            <div class="text-xl font-bold text-indigo-900">
                                {{ $selectedInquiry->quoted_price_pkr ? 'PKR ' . number_format($selectedInquiry->quoted_price_pkr) : 'Awaiting Quote' }}
                            </div>
                            <span class="text-xs text-indigo-700">Status: <strong>{{ $selectedInquiry->status }}</strong></span>
                        </div>
                    </div>

                    @if ($selectedInquiry->notes)
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900">
                            <strong>Special Customer Notes:</strong> {{ $selectedInquiry->notes }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-3 border-t border-slate-100">
                    <button
                        type="button"
                        wire:click="closeDetailModal"
                        class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
