<div class="space-y-20">
    <!-- Hero & Search Tabs Section -->
    <section class="relative pt-8 pb-16 bg-gradient-to-b from-indigo-950 via-slate-900 to-slate-900 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:24px_24px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">
            <!-- Hero Headings -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold border border-indigo-400/30">
                    <span>✈️ Pakistan's Premier Travel Desk</span>
                    <span>&bull;</span>
                    <span>PKR Guaranteed Best Rates</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Fly Smarter. Explore Further. <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-300">Perform Umrah with Peace.</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto">
                    Search flights across domestic and international airlines, book contracted 5-star hotels, or reserve guided tours to Northern Pakistan and Holy Ziyarat.
                </p>
            </div>

            <!-- Tabbed Search Card Container -->
            <div id="flight-search" class="max-w-5xl mx-auto">
                <!-- Navigation Tabs -->
                <div class="flex items-center space-x-2 mb-3">
                    <button
                        type="button"
                        wire:click="setTab('flights')"
                        class="px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center space-x-2 transition cursor-pointer {{ $activeTab === 'flights' ? 'bg-white text-indigo-700 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>Flight Inquiries</span>
                    </button>

                    <button
                        type="button"
                        wire:click="setTab('hotels')"
                        class="px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center space-x-2 transition cursor-pointer {{ $activeTab === 'hotels' ? 'bg-white text-indigo-700 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Hotels & Resorts</span>
                    </button>

                    <button
                        type="button"
                        wire:click="setTab('packages')"
                        class="px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center space-x-2 transition cursor-pointer {{ $activeTab === 'packages' ? 'bg-white text-indigo-700 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Tour Packages</span>
                    </button>
                </div>

                <!-- Tab 1: Flights (Embedding Livewire FlightInquiryForm) -->
                @if ($activeTab === 'flights')
                    <div>
                        <livewire:public.flight-inquiry-form />
                    </div>
                @endif

                <!-- Tab 2: Hotels Search Tab -->
                @if ($activeTab === 'hotels')
                    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 text-slate-800 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="portal_h_search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search Property</label>
                                <input
                                    type="text"
                                    id="portal_h_search"
                                    wire:model.live.debounce.300ms="hotelSearch"
                                    placeholder="Hotel name or address..."
                                    class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300"
                                />
                            </div>
                            <div>
                                <label for="portal_h_dest" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Destination</label>
                                <select id="portal_h_dest" wire:model.live="hotelDestinationId" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300">
                                    <option value="">All Destinations</option>
                                    @foreach ($destinations as $dest)
                                        <option value="{{ $dest->id }}">{{ $dest->name }} ({{ $dest->country }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <span class="text-xs text-slate-400">Showing top verified hotel partners with direct contracted B2B rates in PKR.</span>
                            </div>
                        </div>

                        <!-- Hotel Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                            @forelse ($tabHotels as $hotel)
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80 space-y-3 hover:shadow-md transition">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $hotel->name }}</h4>
                                            <span class="text-xs text-slate-500">{{ $hotel->destination?->name }}, {{ $hotel->destination?->country }}</span>
                                        </div>
                                        <div class="flex items-center text-amber-400 text-xs">
                                            @for ($i = 0; $i < $hotel->star_rating; $i++) ★ @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 line-clamp-1">{{ $hotel->location_address }}</p>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                        <div>
                                            <span class="text-[11px] text-slate-400 block">Nightly Rate</span>
                                            <strong class="text-indigo-600 text-sm font-bold">PKR {{ number_format($hotel->nightly_rate_pkr) }}</strong>
                                        </div>
                                        @if ($hotel->is_partner)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                                Contract Partner
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-sm">
                                    No hotels found matching criteria.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Tab 3: Packages Search Tab -->
                @if ($activeTab === 'packages')
                    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 text-slate-800 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="portal_p_search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search Package</label>
                                <input
                                    type="text"
                                    id="portal_p_search"
                                    wire:model.live.debounce.300ms="packageSearch"
                                    placeholder="Search tour title..."
                                    class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300"
                                />
                            </div>
                            <div>
                                <label for="portal_p_dest" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Destination</label>
                                <select id="portal_p_dest" wire:model.live="packageDestinationId" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300">
                                    <option value="">All Destinations</option>
                                    @foreach ($destinations as $dest)
                                        <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="portal_p_type" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Package Category</label>
                                <select id="portal_p_type" wire:model.live="packageType" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-300">
                                    <option value="all">All Packages</option>
                                    <option value="umrah">Umrah Pilgrimage Only</option>
                                    <option value="general">Holidays & Adventure</option>
                                </select>
                            </div>
                        </div>

                        <!-- Package Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                            @forelse ($tabPackages as $pkg)
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80 space-y-3 hover:shadow-md transition">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $pkg->is_umrah ? 'bg-emerald-600 text-white' : 'bg-indigo-100 text-indigo-700' }}">
                                            {{ $pkg->is_umrah ? '🕋 Umrah' : $pkg->destination?->name }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-semibold">{{ $pkg->duration_days }} Days</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug">{{ $pkg->title }}</h4>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                        <div>
                                            <span class="text-[11px] text-slate-400 block">Total Price (PKR)</span>
                                            <strong class="text-emerald-700 text-sm font-bold">PKR {{ number_format($pkg->price_pkr) }}</strong>
                                        </div>
                                        <span class="text-xs text-slate-500">{{ $pkg->difficulty_rating }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-sm">
                                    No tour packages found matching criteria.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Airline Partnerships Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-white border border-slate-200/80 p-8 shadow-xs space-y-6">
            <div class="text-center space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Official GDS Ticketing Partners</span>
                <h3 class="text-xl font-bold text-slate-900">Book Directly Across Domestic & International Carriers</h3>
            </div>

            <!-- Airlines Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 items-center text-center">
                @php
                    $airlines = [
                        ['name' => 'PIA', 'type' => 'National Flag Carrier', 'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                        ['name' => 'Airblue', 'type' => 'Domestic & Gulf', 'bg' => 'bg-blue-50 text-blue-800 border-blue-200'],
                        ['name' => 'SereneAir', 'type' => 'Premium Travel', 'bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200'],
                        ['name' => 'Fly Jinnah', 'type' => 'Direct Baku & Local', 'bg' => 'bg-rose-50 text-rose-800 border-rose-200'],
                        ['name' => 'AirSial', 'type' => 'Karachi & Sialkot Hub', 'bg' => 'bg-amber-50 text-amber-800 border-amber-200'],
                        ['name' => 'Emirates', 'type' => 'Dubai Global Hub', 'bg' => 'bg-red-50 text-red-800 border-red-200'],
                        ['name' => 'Saudia', 'type' => 'Official Umrah Flights', 'bg' => 'bg-emerald-50 text-emerald-900 border-emerald-300'],
                        ['name' => 'Qatar Airways', 'type' => 'Doha World Network', 'bg' => 'bg-purple-50 text-purple-800 border-purple-200'],
                    ];
                @endphp

                @foreach ($airlines as $airline)
                    <div class="p-3 rounded-2xl border {{ $airline['bg'] }} flex flex-col justify-center items-center h-24 hover:scale-105 transition">
                        <span class="text-base font-extrabold tracking-tight">{{ $airline['name'] }}</span>
                        <span class="text-[10px] text-slate-500 font-medium mt-1 leading-tight">{{ $airline['type'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Umrah Packages Highlight Section -->
    <section id="umrah-packages" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 flex items-center">
                    <span class="mr-1.5">🕋</span> Sacred Pilgrimage
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Executive & Economy Umrah Packages 2026</h2>
                <p class="text-sm text-slate-500 mt-1">Direct PIA and Saudia Airlines bookings with 5-star Makkah Clock Tower & Madinah central accommodations.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($umrahPackages as $umrah)
                <div class="bg-white rounded-3xl border border-emerald-200 shadow-md p-6 sm:p-8 space-y-6 relative overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">
                                🕋 Verified Umrah Package
                            </span>
                            <h3 class="text-xl font-bold text-slate-900 mt-2">{{ $umrah->title }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block">Package Price</span>
                            <strong class="text-2xl font-black text-emerald-700">PKR {{ number_format($umrah->price_pkr) }}</strong>
                            <span class="text-[11px] text-slate-400 block">per pilgrim</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs font-semibold uppercase text-slate-500">Key Inclusions:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700">
                            @if (!empty($umrah->inclusions))
                                @foreach (array_slice($umrah->inclusions, 0, 6) as $inc)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="truncate">{{ $inc }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Duration: <strong>{{ $umrah->duration_days }} Days</strong></span>
                        <span>Visa & Health Insurance Included</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Featured Tour Packages -->
    <section id="featured-tours" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Explore Northern Pakistan & Abroad</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Featured Holiday Packages</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($featuredPackages as $pkg)
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                            {{ $pkg->destination?->name }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">{{ $pkg->duration_days }} Days</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 leading-snug line-clamp-2">
                        {{ $pkg->title }}
                    </h3>

                    <div class="space-y-1 text-xs text-slate-600">
                        @if (!empty($pkg->inclusions))
                            @foreach (array_slice($pkg->inclusions, 0, 3) as $inc)
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-indigo-500">&bull;</span>
                                    <span class="truncate">{{ $inc }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block">Starting From</span>
                            <span class="text-lg font-black text-slate-900">PKR {{ number_format($pkg->price_pkr) }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                            {{ $pkg->difficulty_rating }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Popular Destinations by Region -->
    <section id="destinations" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Explore by Geographic Region</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Popular Destinations</h2>
            </div>

            <!-- Region Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                @foreach (['Northern Pakistan', 'Middle East', 'Central Asia', 'Europe'] as $region)
                    <button
                        type="button"
                        wire:click="setRegion('{{ $region }}')"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $selectedRegion === $region ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}"
                    >
                        {{ $region }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($regionalDestinations as $dest)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 space-y-3 shadow-xs hover:border-indigo-400 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $dest->country }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $dest->type === 'domestic' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                            {{ ucfirst($dest->type) }}
                        </span>
                    </div>
                    <h4 class="text-base font-bold text-slate-900">{{ $dest->name }}</h4>
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $dest->description }}</p>
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 font-medium">
                        <span>{{ $dest->hotels_count }} Hotels</span>
                        <span>{{ $dest->tour_packages_count }} Packages</span>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-slate-400 text-sm">
                    No destinations currently listed in this region.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Customer Testimonials -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-2 max-w-xl mx-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pilgrim & Traveler Reviews</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">What Our Clients Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="text-amber-400 text-sm">★★★★★</div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
                    "Alhamdulillah, our 14-day Umrah package was flawless. Clock tower hotel was literally steps from the Haram gate, and our Pakistani mutawwif guided our parents with immense care."
                </p>
                <div class="pt-2 border-t border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center">
                        BT
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Bilal Tariq & Family</span>
                        <span class="block text-[11px] text-slate-400">Islamabad, Pakistan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="text-amber-400 text-sm">★★★★★</div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
                    "The Hunza & Khunjerab tour was beyond expectations. 4x4 Prado was in top condition, Attabad boating was memorable, and Luxus Resort view was spectacular. Best travel agency in town!"
                </p>
                <div class="pt-2 border-t border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-800 font-bold text-xs flex items-center justify-center">
                        FZ
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Fatima Zahra</span>
                        <span class="block text-[11px] text-slate-400">Lahore, Pakistan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="text-amber-400 text-sm">★★★★★</div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
                    "Submitted a flight inquiry for Dubai with specific baggage requirements. Received an official quotation in PKR on WhatsApp within 10 minutes and tickets issued instantly via Raast transfer."
                </p>
                <div class="pt-2 border-t border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-800 font-bold text-xs flex items-center justify-center">
                        UG
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Usman Ghani</span>
                        <span class="block text-[11px] text-slate-400">Karachi, Pakistan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
