<div>
    <!-- Executive Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">Module 3</span>
                <span class="rounded-md bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Command Center</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Admin Operations Dashboard</h1>
            <p class="text-sm text-gray-500">Live flight desk metrics, pending back-office queues, supplier health, and departures.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.analytics') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50">
                <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Advanced Analytics (Mod 15)
            </a>
            <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Manage Bookings
            </a>
        </div>
    </div>

    <!-- Quick Action / Attention Required Ribbon -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <div class="mb-2 flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Operations Action Required</span>
            <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Live System Queues
            </span>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <!-- Pending Inquiries -->
            <a href="{{ route('admin.inquiries') }}" class="group flex items-center justify-between rounded-lg border border-amber-200 bg-amber-50/50 p-3 transition hover:bg-amber-100/60">
                <div>
                    <span class="text-[11px] font-semibold text-amber-800">Inquiry Leads</span>
                    <div class="text-xl font-bold text-amber-900">{{ $kpis['pending_inquiries_count'] }}</div>
                </div>
                <span class="rounded bg-amber-200/60 px-2 py-0.5 text-xs font-bold text-amber-800 group-hover:bg-amber-300/60">&rarr;</span>
            </a>

            <!-- Urgent Ticketing -->
            <a href="{{ route('admin.ticketing.queue') }}?urgentOnly=1" class="group flex items-center justify-between rounded-lg border border-rose-200 bg-rose-50/50 p-3 transition hover:bg-rose-100/60">
                <div>
                    <span class="text-[11px] font-semibold text-rose-800">Urgent Tickets</span>
                    <div class="text-xl font-bold text-rose-900">{{ $kpis['urgent_ticketing_count'] }}</div>
                </div>
                <span class="rounded bg-rose-200/60 px-2 py-0.5 text-xs font-bold text-rose-800 group-hover:bg-rose-300/60">&rarr;</span>
            </a>

            <!-- Pending Deposit Slips -->
            <a href="{{ route('admin.ledger') }}?status=Pending" class="group flex items-center justify-between rounded-lg border border-blue-200 bg-blue-50/50 p-3 transition hover:bg-blue-100/60">
                <div>
                    <span class="text-[11px] font-semibold text-blue-800">Deposit Slips</span>
                    <div class="text-xl font-bold text-blue-900">{{ $kpis['pending_topups_count'] }}</div>
                </div>
                <span class="rounded bg-blue-200/60 px-2 py-0.5 text-xs font-bold text-blue-800 group-hover:bg-blue-300/60">&rarr;</span>
            </a>

            <!-- Pending Payments -->
            <a href="{{ route('admin.payments') }}?status=Pending" class="group flex items-center justify-between rounded-lg border border-purple-200 bg-purple-50/50 p-3 transition hover:bg-purple-100/60">
                <div>
                    <span class="text-[11px] font-semibold text-purple-800">Customer Payments</span>
                    <div class="text-xl font-bold text-purple-900">{{ $kpis['pending_payments_count'] }}</div>
                </div>
                <span class="rounded bg-purple-200/60 px-2 py-0.5 text-xs font-bold text-purple-800 group-hover:bg-purple-300/60">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Executive KPI Summary Cards -->
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <!-- Total Revenue -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-emerald-700">Total Confirmed Revenue</span>
            <div class="mt-1 text-2xl font-black text-gray-900">
                PKR {{ number_format($kpis['total_revenue_pkr'] / 1000, 1) }}k
            </div>
            <p class="mt-1 text-[11px] text-gray-400">Net confirmed & ticketed volume</p>
        </div>

        <!-- Total Bookings -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-blue-700">Total Bookings</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($kpis['total_bookings']) }}
            </div>
            <p class="mt-1 text-[11px] text-gray-400">{{ $kpis['ticketed_rate'] }}% E-Tickets issued</p>
        </div>

        <!-- Average Booking Value -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-purple-700">Average Booking Value (ABV)</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                PKR {{ number_format($kpis['average_booking_value_pkr'], 2) }}
            </div>
            <p class="mt-1 text-[11px] text-gray-400">Mean revenue per completed itinerary</p>
        </div>

        <!-- Conversion Rate -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
            <span class="text-xs font-semibold text-amber-700">Inquiry Conversion Rate</span>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                {{ $kpis['conversion_rate'] }}%
            </div>
            <p class="mt-1 text-[11px] text-gray-400">{{ $kpis['total_inquiries'] }} total customer leads</p>
        </div>
    </div>

    <!-- Middle Split: Monthly Revenue Trend (7 cols) + Service Revenue Mix (5 cols) -->
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Monthly Revenue Trend (7 cols) -->
        <div class="lg:col-span-7">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs h-full">
                <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Monthly Revenue Comparison</h3>
                        <p class="text-xs text-gray-400">Last 6 Months Booking Performance</p>
                    </div>
                    <span class="rounded bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700">PKR Currency</span>
                </div>

                <div class="space-y-3">
                    @php
                        $maxRev = max(1, ...array_map(fn($m) => $m['revenue_pkr'], $revenueData['monthly_comparisons']));
                    @endphp
                    @foreach ($revenueData['monthly_comparisons'] as $m)
                        @php
                            $pct = min(100, round(($m['revenue_pkr'] / $maxRev) * 100));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-gray-700">{{ $m['month'] }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-400">{{ $m['bookings_count'] }} Bookings</span>
                                    <span class="font-mono font-bold text-gray-900">PKR {{ number_format($m['revenue_pkr'], 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-1 h-2.5 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2.5 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ max(4, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Service Mix & Channels (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Service Category Mix -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Revenue by Category</h3>
                <div class="space-y-2.5 text-xs">
                    <div>
                        <div class="flex justify-between font-medium">
                            <span class="text-blue-700">Flights</span>
                            <span class="font-bold text-gray-900">PKR {{ number_format($revenueData['service_distribution']['flights']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['flights']['percentage'] }}%)</span>
                        </div>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-blue-600" style="width: {{ $revenueData['service_distribution']['flights']['percentage'] }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium">
                            <span class="text-amber-700">Hotels & Resorts</span>
                            <span class="font-bold text-gray-900">PKR {{ number_format($revenueData['service_distribution']['hotels']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['hotels']['percentage'] }}%)</span>
                        </div>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-amber-500" style="width: {{ $revenueData['service_distribution']['hotels']['percentage'] }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-medium">
                            <span class="text-purple-700">Umrah & Tour Packages</span>
                            <span class="font-bold text-gray-900">PKR {{ number_format($revenueData['service_distribution']['groups_umrah']['amount_pkr'], 2) }} ({{ $revenueData['service_distribution']['groups_umrah']['percentage'] }}%)</span>
                        </div>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-purple-600" style="width: {{ $revenueData['service_distribution']['groups_umrah']['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pakistani Payment Channels Share -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Payment Channel Mix</h3>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-lg bg-emerald-50 p-2.5">
                        <span class="text-emerald-800 font-semibold">Raast SBP</span>
                        <div class="mt-0.5 font-bold text-emerald-900">{{ $revenueData['channel_distribution']['raast']['percentage'] }}%</div>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-2.5">
                        <span class="text-blue-800 font-semibold">1Link 1Bill</span>
                        <div class="mt-0.5 font-bold text-blue-900">{{ $revenueData['channel_distribution']['onelink']['percentage'] }}%</div>
                    </div>
                    <div class="rounded-lg bg-amber-50 p-2.5">
                        <span class="text-amber-800 font-semibold">JazzCash/EP</span>
                        <div class="mt-0.5 font-bold text-amber-900">{{ $revenueData['channel_distribution']['wallets']['percentage'] }}%</div>
                    </div>
                    <div class="rounded-lg bg-purple-50 p-2.5">
                        <span class="text-purple-800 font-semibold">Visa/Master</span>
                        <div class="mt-0.5 font-bold text-purple-900">{{ $revenueData['channel_distribution']['cards']['percentage'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Upcoming Departures (7 cols) + GDS Health & Alerts (5 cols) -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Upcoming Departures (7 cols) -->
        <div class="lg:col-span-7">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Upcoming Departures</h3>
                        <p class="text-xs text-gray-400">Manifest of scheduled flights & trips</p>
                    </div>
                    <a href="{{ route('admin.bookings') }}" class="text-xs font-semibold text-blue-600 hover:underline">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Sector / Carrier</th>
                                <th class="px-3 py-2 font-semibold">Passenger</th>
                                <th class="px-3 py-2 font-semibold">Date</th>
                                <th class="px-3 py-2 font-semibold">Total PKR</th>
                                <th class="px-3 py-2 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($upcomingDepartures as $dep)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2.5">
                                        <div class="font-bold text-gray-900">{{ $dep->route ?: $dep->destination }}</div>
                                        <div class="text-[11px] text-blue-600 font-mono">{{ $dep->airline }} {{ $dep->flight_number }}</div>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-gray-900">{{ $dep->customer_name }}</div>
                                        <div class="font-mono text-[10px] text-gray-400">{{ $dep->pnr ?? 'NO-PNR' }}</div>
                                    </td>
                                    <td class="px-3 py-2.5 text-gray-600 font-medium">
                                        {{ $dep->travel_date ? $dep->travel_date->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-3 py-2.5 font-mono font-bold text-gray-900">
                                        PKR {{ number_format((float) $dep->total_amount, 2) }}
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $dep->status === 'Ticketed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $dep->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-gray-400">No upcoming departures scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- GDS Monitoring & Airline Announcements (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- GDS Supplier Health Status -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">GDS & NDC Supplier Health</h3>
                        <p class="text-xs text-gray-400">Real-time gateway connectivity</p>
                    </div>
                    <span class="rounded bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-800">All Connected</span>
                </div>

                <div class="divide-y divide-gray-100 text-xs">
                    @foreach ($gdsMonitoring as $gds)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <span class="font-bold text-gray-900">{{ $gds['provider'] }}</span>
                                <div class="text-[10px] text-gray-400">{{ $gds['protocol'] }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-gray-500 text-[11px]">{{ $gds['latency_ms'] }}ms</span>
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">
                                    {{ $gds['status'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Airline Industry Announcements -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Airline & Industry Notices</h3>
                <div class="space-y-2.5 text-xs">
                    @foreach ($announcements as $ann)
                        <div class="rounded-lg border border-gray-100 bg-gray-50/70 p-2.5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-blue-700">{{ $ann['carrier'] }}</span>
                                <span class="rounded px-1.5 py-0.2 text-[10px] font-semibold {{ $ann['priority'] === 'Critical' ? 'bg-rose-100 text-rose-800' : 'bg-gray-200 text-gray-700' }}">{{ $ann['priority'] }}</span>
                            </div>
                            <p class="mt-1 font-medium text-gray-900">{{ $ann['headline'] }}</p>
                            <p class="mt-0.5 text-gray-600 text-[11px] leading-relaxed">{{ $ann['detail'] }}</p>
                            <span class="mt-1 block text-[10px] text-gray-400">{{ $ann['date'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
