<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">Module 15</span>
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Financial Intelligence</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Advanced Analytics & Reporting</h1>
            <p class="text-sm text-gray-500">Revenue distribution, monthly trends, customer acquisition metrics, and route performance.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Operations Command
            </a>
            <button
                type="button"
                wire:click="exportCsv"
                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-emerald-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Executive Analytics CSV
            </button>
        </div>
    </div>

    <!-- Acquisition & Unit Economics KPIs -->
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-emerald-700">Conversion Rate</span>
            <div class="mt-1 text-2xl font-black text-gray-900">{{ $kpis['conversion_rate'] }}%</div>
            <p class="mt-1 text-[11px] text-gray-400">Inquiry to confirmed booking</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-blue-700">Average Booking Value</span>
            <div class="mt-1 text-2xl font-black text-gray-900">PKR {{ number_format($kpis['average_booking_value_pkr'], 2) }}</div>
            <p class="mt-1 text-[11px] text-gray-400">Mean basket size per itinerary</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-purple-700">Repeat Customer Rate</span>
            <div class="mt-1 text-2xl font-black text-gray-900">{{ $acquisition['repeat_customer_rate'] }}%</div>
            <p class="mt-1 text-[11px] text-gray-400">B2B client retention index</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-amber-700">Average Pax / Booking</span>
            <div class="mt-1 text-2xl font-black text-gray-900">{{ $acquisition['average_pax_per_booking'] }} Pax</div>
            <p class="mt-1 text-[11px] text-gray-400">Lead time: {{ $acquisition['average_lead_time_days'] }} days</p>
        </div>
    </div>

    <!-- Monthly Trend & Route Leaderboard -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Monthly Comparisons Table (7 cols) -->
        <div class="lg:col-span-7">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Monthly Comparisons Matrix</h3>
                        <p class="text-xs text-gray-400">Historical performance & volume tracking</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-2.5 font-semibold">Month</th>
                                <th class="px-4 py-2.5 font-semibold">Bookings</th>
                                <th class="px-4 py-2.5 font-semibold">Gross Revenue (PKR)</th>
                                <th class="px-4 py-2.5 font-semibold">Avg / Booking</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($revenueData['monthly_comparisons'] as $m)
                                @php
                                    $avg = $m['bookings_count'] > 0 ? round($m['revenue_pkr'] / $m['bookings_count'], 2) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $m['month'] }}</td>
                                    <td class="px-4 py-3 font-medium text-blue-600">{{ $m['bookings_count'] }}</td>
                                    <td class="px-4 py-3 font-mono font-bold text-gray-900">PKR {{ number_format($m['revenue_pkr'], 2) }}</td>
                                    <td class="px-4 py-3 font-mono text-gray-600">PKR {{ number_format($avg, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Performing Routes (5 cols) -->
        <div class="lg:col-span-5">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Top Sectors Leaderboard</h3>
                        <p class="text-xs text-gray-400">Highest grossing domestic & international routes</p>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 text-xs">
                    @forelse ($acquisition['top_routes'] as $idx => $route)
                        <div class="flex items-center justify-between py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-800">{{ $idx + 1 }}</span>
                                <div>
                                    <span class="font-bold text-gray-900">{{ $route->sector }}</span>
                                    <div class="text-[10px] text-gray-400">{{ $route->bookings_count }} completed itineraries</div>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-emerald-700">
                                PKR {{ number_format((float) $route->total_revenue_pkr, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-gray-400">No route statistics available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Distribution Grids -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Service Distribution Card -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
            <h3 class="text-sm font-bold text-gray-900 mb-2">Service Revenue Contribution</h3>
            <p class="text-xs text-gray-400 mb-4">Gross share by vertical product line</p>

            <div class="space-y-4 text-xs">
                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-blue-700">Flight Ticketing</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['service_distribution']['flights']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['flights']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-blue-600" style="width: {{ $revenueData['service_distribution']['flights']['percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-amber-700">Hotels & Accommodation</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['service_distribution']['hotels']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['hotels']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-amber-500" style="width: {{ $revenueData['service_distribution']['hotels']['percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-purple-700">Group Tours & Umrah Operations</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['service_distribution']['groups_umrah']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['groups_umrah']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-purple-600" style="width: {{ $revenueData['service_distribution']['groups_umrah']['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Channel Distribution Card -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
            <h3 class="text-sm font-bold text-gray-900 mb-2">Pakistani Payment Channels Adoption</h3>
            <p class="text-xs text-gray-400 mb-4">Volume distribution across clearing rails</p>

            <div class="space-y-4 text-xs">
                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-emerald-700">Raast SBP P2P / P2M</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['channel_distribution']['raast']['amount_pkr'], 2) }} ({{ $revenueData['channel_distribution']['raast']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-emerald-600" style="width: {{ $revenueData['channel_distribution']['raast']['percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-blue-700">1Link 1Bill & IBFT</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['channel_distribution']['onelink']['amount_pkr'], 2) }} ({{ $revenueData['channel_distribution']['onelink']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-blue-600" style="width: {{ $revenueData['channel_distribution']['onelink']['percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-amber-700">Mobile Wallets (JazzCash & EasyPaisa)</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['channel_distribution']['wallets']['amount_pkr'], 2) }} ({{ $revenueData['channel_distribution']['wallets']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-amber-500" style="width: {{ $revenueData['channel_distribution']['wallets']['percentage'] }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-purple-700">Cards (Visa / Mastercard)</span>
                        <span class="text-gray-900">PKR {{ number_format($revenueData['channel_distribution']['cards']['amount_pkr'], 2) }} ({{ $revenueData['channel_distribution']['cards']['percentage'] }}%)</span>
                    </div>
                    <div class="mt-1.5 h-3 w-full rounded-full bg-gray-100">
                        <div class="h-3 rounded-full bg-purple-600" style="width: {{ $revenueData['channel_distribution']['cards']['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
