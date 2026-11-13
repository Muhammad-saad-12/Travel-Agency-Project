<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Modules 13 & 14</span>
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Multi-GDS Engine</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">GDS PNR Importer & Decoder</h1>
            <p class="text-sm text-gray-500">Universal PNR parsing for Amadeus, Sabre, Galileo/Travelport, Fly Jinnah, and AirSial.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                View Bookings
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if ($successMessage)
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">{{ $successMessage }}</p>
                        @if ($createdBookingRef)
                            <a href="{{ route('admin.bookings') }}?search={{ $createdBookingRef }}" class="text-xs font-medium text-emerald-700 underline hover:text-emerald-900">
                                Open Booking in Management Hub &rarr;
                            </a>
                        @endif
                    </div>
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

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Input & Controls Panel (5 cols) -->
        <div class="lg:col-span-5">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Fast Sample Loader</label>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <button type="button" wire:click="loadSample('amadeus')" class="rounded-md border border-sky-300 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 hover:bg-sky-100">Amadeus</button>
                        <button type="button" wire:click="loadSample('sabre')" class="rounded-md border border-rose-300 bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100">Sabre</button>
                        <button type="button" wire:click="loadSample('galileo')" class="rounded-md border border-amber-300 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100">Galileo</button>
                        <button type="button" wire:click="loadSample('fly_jinnah')" class="rounded-md border border-red-300 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 hover:bg-red-100">Fly Jinnah</button>
                        <button type="button" wire:click="loadSample('airsial')" class="rounded-md border border-emerald-300 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-100">AirSial</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">GDS / Carrier Override</label>
                    <select wire:model.live="selectedSystem" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="auto">Auto-detect System</option>
                        <option value="amadeus">Amadeus (1A)</option>
                        <option value="sabre">Sabre (1S)</option>
                        <option value="galileo">Galileo / Travelport (1G)</option>
                        <option value="fly_jinnah">Fly Jinnah (9P NDC)</option>
                        <option value="airsial">AirSial (PF API)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Raw Terminal / PNR Output</label>
                        <button type="button" wire:click="clear" class="text-xs font-medium text-gray-400 hover:text-gray-600">Clear</button>
                    </div>
                    <textarea
                        wire:model="rawPnr"
                        rows="14"
                        placeholder="Paste raw PNR text from Amadeus, Sabre, Galileo, Fly Jinnah, or AirSial terminal..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-900 font-mono text-xs leading-relaxed text-emerald-400 shadow-inner focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    ></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        wire:click="parsePnr(app('App\Services\GdsParserService'))"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Decode PNR
                    </button>
                    @if ($parsedResult)
                        <button
                            type="button"
                            wire:click="createBookingFromPnr(app('App\Services\BookingService'))"
                            class="flex-1 rounded-lg bg-emerald-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Create Booking
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Decoded Output Panel (7 cols) -->
        <div class="lg:col-span-7">
            @if ($parsedResult)
                <div class="space-y-6">
                    <!-- Key Information Bar -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <span class="text-xs font-medium text-gray-500">Record Locator / PNR</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-2xl font-black tracking-wider text-blue-600">{{ $parsedResult['pnr_code'] ?? 'N/A' }}</span>
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold uppercase text-blue-800">
                                        {{ $parsedResult['system'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-xs font-medium text-gray-500">Ticketing Time Limit (TTL)</span>
                                <div class="font-mono text-sm font-bold {{ !empty($parsedResult['time_limit_raw']) ? 'text-rose-600' : 'text-gray-700' }}">
                                    {{ $parsedResult['time_limit_raw'] ?? 'Not Specified' }}
                                </div>
                            </div>
                        </div>

                        <!-- Fare Breakdown in PKR -->
                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-lg bg-gray-50 p-3">
                                <span class="text-xs font-medium text-gray-500">Base Airfare</span>
                                <div class="mt-0.5 text-sm font-bold text-gray-900">
                                    PKR {{ number_format((float) ($parsedResult['fare_breakdown']['base_fare_pkr'] ?? 0), 2) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <span class="text-xs font-medium text-gray-500">Taxes & Fees</span>
                                <div class="mt-0.5 text-sm font-bold text-gray-900">
                                    PKR {{ number_format((float) ($parsedResult['fare_breakdown']['tax_amount_pkr'] ?? 0), 2) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-emerald-50 p-3">
                                <span class="text-xs font-semibold text-emerald-800">Total Quoted (PKR)</span>
                                <div class="mt-0.5 text-base font-black text-emerald-700">
                                    PKR {{ number_format((float) ($parsedResult['fare_breakdown']['total_amount_pkr'] ?? 0), 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger Details -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900">Passenger Manifest ({{ count($parsedResult['passengers'] ?? []) }})</h3>
                            <span class="text-xs text-gray-400">Extracted Name & Ticket/Passport Details</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 font-semibold">#</th>
                                        <th class="px-3 py-2 font-semibold">Passenger Full Name</th>
                                        <th class="px-3 py-2 font-semibold">Type</th>
                                        <th class="px-3 py-2 font-semibold">Ticket Ref / Doc</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($parsedResult['passengers'] ?? [] as $idx => $pax)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 font-mono text-gray-500">{{ $idx + 1 }}</td>
                                            <td class="px-3 py-2 font-semibold text-gray-900">{{ $pax['name'] }}</td>
                                            <td class="px-3 py-2">
                                                <span class="rounded bg-gray-100 px-2 py-0.5 text-gray-700 font-mono">{{ $pax['type'] ?? 'ADT' }}</span>
                                            </td>
                                            <td class="px-3 py-2 font-mono text-gray-600">
                                                {{ $pax['ticket_number'] ?? ($pax['passport'] ?? 'Pending Issuance') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-gray-500">No passenger data parsed.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Flight Segments -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900">Flight Itinerary Segments ({{ count($parsedResult['segments'] ?? []) }})</h3>
                            <span class="text-xs text-gray-400">Sectors, RBD & Times</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 font-semibold">Sector</th>
                                        <th class="px-3 py-2 font-semibold">Flight</th>
                                        <th class="px-3 py-2 font-semibold">Class</th>
                                        <th class="px-3 py-2 font-semibold">Departure</th>
                                        <th class="px-3 py-2 font-semibold">Arrival</th>
                                        <th class="px-3 py-2 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($parsedResult['segments'] ?? [] as $seg)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 font-bold text-gray-900">
                                                {{ $seg['origin'] }} &rarr; {{ $seg['destination'] }}
                                            </td>
                                            <td class="px-3 py-2 font-mono text-blue-600 font-semibold">
                                                {{ $seg['airline_code'] }}{{ $seg['flight_number'] }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="rounded bg-sky-100 px-1.5 py-0.5 font-bold font-mono text-sky-800">{{ $seg['booking_class'] }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-700">
                                                <div class="font-medium">{{ $seg['departure_date'] }}</div>
                                                <div class="text-gray-400 font-mono">{{ $seg['departure_time'] }}</div>
                                            </td>
                                            <td class="px-3 py-2 text-gray-700">
                                                <div class="font-medium">{{ $seg['arrival_date'] ?? $seg['departure_date'] }}</div>
                                                <div class="text-gray-400 font-mono">{{ $seg['arrival_time'] }}</div>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">
                                                    {{ $seg['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-3 py-4 text-center text-gray-500">No itinerary segments parsed.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Fare Rules & Endorsements -->
                    @if (!empty($parsedResult['fare_rules']))
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                            <h3 class="mb-2 text-sm font-bold text-gray-900">Endorsements & Fare Rules</h3>
                            <div class="space-y-1.5">
                                @foreach ($parsedResult['fare_rules'] as $rule)
                                    <div class="flex items-start gap-2 text-xs text-gray-600">
                                        <span class="mt-0.5 text-blue-500">&bull;</span>
                                        <span>{{ $rule }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex h-96 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-xs">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-3 text-sm font-semibold text-gray-900">No Decoded PNR</h3>
                    <p class="mt-1 max-w-sm text-xs text-gray-500">Paste terminal PNR text on the left or select a sample carrier to decode flight legs, passenger manifests, and PKR fare pricing.</p>
                </div>
            @endif
        </div>
    </div>
</div>
