<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">Modules 7 & 8</span>
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Omni-Channel Bookings</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Booking Management</h1>
            <p class="text-sm text-gray-500">Centralized back-office engine for Flight, Hotel, and Group tour itineraries.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.gds.importer') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">
                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import from GDS
            </a>
            <button wire:click="exportCsv" type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-emerald-700">
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

    <!-- KPI Statistics Grid -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-medium text-gray-500">Total Bookings</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($statistics['total_bookings']) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-medium text-blue-600">Flights</span>
            <div class="mt-1 text-2xl font-bold text-blue-700">{{ number_format($statistics['flight_count']) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-medium text-amber-600">Hotels</span>
            <div class="mt-1 text-2xl font-bold text-amber-700">{{ number_format($statistics['hotel_count']) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-medium text-purple-600">Groups / Umrah</span>
            <div class="mt-1 text-2xl font-bold text-purple-700">{{ number_format($statistics['group_count']) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-medium text-emerald-600">Ticketed</span>
            <div class="mt-1 text-2xl font-bold text-emerald-700">{{ number_format($statistics['ticketed_count']) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-emerald-50 p-4 shadow-xs">
            <span class="text-xs font-bold text-emerald-800">Confirmed Rev (PKR)</span>
            <div class="mt-1 text-base font-black text-emerald-800">
                PKR {{ number_format($statistics['total_revenue_pkr'] / 1000, 1) }}k
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-600">Search Records</label>
                <div class="relative mt-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Ref, PNR, Customer, Ticket, Route..."
                        class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Type -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Category</label>
                <select wire:model.live="type" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Types</option>
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="group">Group / Umrah</option>
                </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Status</label>
                <select wire:model.live="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Ticketed">Ticketed</option>
                    <option value="Pending">Pending</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Travel From</label>
                <input
                    type="date"
                    wire:model.live="date_from"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
            </div>

            <!-- Date To -->
            <div class="md:col-span-2 flex items-end gap-2">
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-600">Travel To</label>
                    <input
                        type="date"
                        wire:model.live="date_to"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>
                <button
                    type="button"
                    wire:click="resetFilters"
                    title="Reset filters"
                    class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
                >
                    &times;
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Reference / PNR</th>
                        <th class="px-4 py-3 font-semibold">Customer & Contacts</th>
                        <th class="px-4 py-3 font-semibold">Type & Service</th>
                        <th class="px-4 py-3 font-semibold">Sector / Carrier</th>
                        <th class="px-4 py-3 font-semibold">Travel Date</th>
                        <th class="px-4 py-3 font-semibold">Total PKR</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">E-Ticket Ref</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50/80">
                            <!-- Ref & PNR -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $booking->booking_reference }}</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="rounded bg-sky-100 px-1.5 py-0.5 font-mono text-xs font-semibold text-sky-800">
                                        {{ $booking->pnr ?? 'NO-PNR' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">{{ $booking->supplier }}</span>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $booking->customer_name }}</div>
                                <div class="text-[11px] text-gray-500">{{ $booking->customer_phone }}</div>
                                <div class="text-[10px] text-gray-400">{{ $booking->customer_email }}</div>
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $booking->type === 'flight' ? 'bg-blue-100 text-blue-800' : ($booking->type === 'hotel' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ ucfirst($booking->type) }}
                                </span>
                                <div class="mt-1 text-[11px] text-gray-600">{{ $booking->service }}</div>
                            </td>

                            <!-- Sector / Carrier -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $booking->route ?: $booking->destination }}</div>
                                <div class="text-[11px] text-gray-500">
                                    {{ $booking->airline }} {{ $booking->flight_number }}
                                </div>
                            </td>

                            <!-- Travel Date -->
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ $booking->travel_date ? $booking->travel_date->format('d M Y') : 'Open Date' }}
                                </div>
                                <div class="text-[11px] text-gray-400">
                                    {{ $booking->passengers_count }} Pax
                                </div>
                            </td>

                            <!-- Total Amount -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">
                                    PKR {{ number_format((float) $booking->total_amount, 2) }}
                                </div>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-medium {{ $booking->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : ($booking->payment_status === 'Refunded' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $booking->payment_status }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $booking->status === 'Ticketed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'Confirmed' ? 'bg-blue-100 text-blue-800' : ($booking->status === 'Cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')) }}">
                                    {{ $booking->status }}
                                </span>
                            </td>

                            <!-- Ticket Ref -->
                            <td class="px-4 py-3 font-mono text-[11px] text-gray-700">
                                {{ $booking->ticket_ref ?? '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="openDetailModal({{ $booking->id }})"
                                        class="rounded-md border border-gray-300 bg-white px-2 py-1 text-[11px] font-medium text-gray-700 hover:bg-gray-50"
                                        title="View Details"
                                    >
                                        View
                                    </button>
                                    @if ($booking->status !== 'Ticketed')
                                        <button
                                            type="button"
                                            wire:click="openTicketModal({{ $booking->id }})"
                                            class="rounded-md bg-emerald-600 px-2 py-1 text-[11px] font-medium text-white hover:bg-emerald-700"
                                            title="Issue Electronic Ticket"
                                        >
                                            Ticket
                                        </button>
                                    @endif
                                    <button
                                        type="button"
                                        wire:click="openStatusModal({{ $booking->id }})"
                                        class="rounded-md border border-gray-300 bg-gray-50 px-2 py-1 text-[11px] font-medium text-gray-600 hover:bg-gray-100"
                                        title="Change Status"
                                    >
                                        Status
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                No booking records found matching current criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-3">
            {{ $bookings->links() }}
        </div>
    </div>

    <!-- Booking Details Modal -->
    @if ($showDetailModal && $selectedBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">{{ ucfirst($selectedBooking->type) }}</span>
                            <span class="font-mono text-base font-bold text-gray-900">{{ $selectedBooking->booking_reference }}</span>
                        </div>
                        <p class="text-xs text-gray-500">PNR: <strong class="font-mono text-gray-900">{{ $selectedBooking->pnr ?? 'None' }}</strong> | Supplier: {{ $selectedBooking->supplier }}</p>
                    </div>
                    <button wire:click="closeDetailModal" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">&times;</button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <span class="text-xs font-semibold text-gray-500">Customer Details</span>
                        <div class="mt-1 font-bold text-gray-900">{{ $selectedBooking->customer_name }}</div>
                        <div class="text-xs text-gray-600">{{ $selectedBooking->customer_email }}</div>
                        <div class="text-xs text-gray-600">{{ $selectedBooking->customer_phone }}</div>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-3">
                        <span class="text-xs font-semibold text-gray-500">Itinerary & Dates</span>
                        <div class="mt-1 font-bold text-gray-900">{{ $selectedBooking->route ?: $selectedBooking->destination }}</div>
                        <div class="text-xs text-gray-600">Travel: {{ $selectedBooking->travel_date ? $selectedBooking->travel_date->format('d M Y') : 'N/A' }}</div>
                        @if ($selectedBooking->return_date)
                            <div class="text-xs text-gray-600">Return: {{ $selectedBooking->return_date->format('d M Y') }}</div>
                        @endif
                    </div>
                </div>

                <!-- PKR Financial Breakdown -->
                <div class="mt-4 rounded-lg border border-gray-200 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Financial Breakdown (PKR)</h4>
                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs sm:grid-cols-4">
                        <div>
                            <span class="text-gray-500">Base Fare:</span>
                            <div class="font-semibold text-gray-900">PKR {{ number_format((float) $selectedBooking->fare_amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Taxes:</span>
                            <div class="font-semibold text-gray-900">PKR {{ number_format((float) $selectedBooking->tax_amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Ancillaries / Markup:</span>
                            <div class="font-semibold text-gray-900">PKR {{ number_format((float) ($selectedBooking->ancillaries_amount + $selectedBooking->markup_amount), 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Total Net:</span>
                            <div class="font-bold text-emerald-700">PKR {{ number_format((float) $selectedBooking->total_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Manifest Data if present -->
                @if (!empty($selectedBooking->passengers_data))
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Passengers</h4>
                        <div class="mt-2 divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                            @foreach ($selectedBooking->passengers_data as $pax)
                                <div class="flex items-center justify-between p-2.5 text-xs">
                                    <span class="font-semibold text-gray-900">{{ $pax['name'] ?? 'Passenger' }}</span>
                                    <span class="text-gray-500 font-mono">{{ $pax['ticket_number'] ?? ($pax['passport'] ?? 'TKT Pending') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($selectedBooking->remarks)
                    <div class="mt-4 rounded-lg bg-amber-50 p-3 text-xs text-amber-900">
                        <span class="font-semibold">Remarks:</span> {{ $selectedBooking->remarks }}
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="closeDetailModal" class="rounded-lg bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Issue Ticket Modal -->
    @if ($showTicketModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Issue Electronic Ticket</h3>
                <p class="text-xs text-gray-500 mt-1">Enter the 13-digit GDS e-ticket or BSP coupon reference.</p>

                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700">E-Ticket Number / Coupon Ref</label>
                    <input
                        type="text"
                        wire:model="ticketRef"
                        placeholder="e.g. 214-5544332211"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono shadow-xs focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    />
                    @error('ticketRef') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeTicketModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="issueTicket(app('App\Services\BookingService'))" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Confirm Issuance
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Update Status Modal -->
    @if ($showStatusModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-base font-bold text-gray-900">Update Booking Status</h3>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">New Status</label>
                        <select wire:model="newStatus" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="Confirmed">Confirmed</option>
                            <option value="Ticketed">Ticketed</option>
                            <option value="Pending">Pending</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Expired">Expired</option>
                        </select>
                        @error('newStatus') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Remarks / Operational Notes</label>
                        <textarea
                            wire:model="statusRemarks"
                            rows="3"
                            placeholder="Add reason for status update..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        ></textarea>
                        @error('statusRemarks') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <button type="button" wire:click="closeStatusModal" class="rounded-lg bg-gray-100 px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="updateStatus(app('App\Services\BookingService'))" class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                        Save Status
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
