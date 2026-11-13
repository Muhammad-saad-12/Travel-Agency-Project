<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'FlyAero Travels - Flights, Umrah & Pakistan Tour Packages' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full font-sans bg-slate-50 text-slate-800 flex flex-col selection:bg-indigo-600 selection:text-white">
    @php
        $agencyService = app(\App\Services\AgencySettingService::class);
        $settings = $agencyService->getSettings();
        $cleanWhatsApp = preg_replace('/[^0-9]/', '', (string)$settings->whatsapp_number);
    @endphp

    <!-- Top Announcement Strip -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center space-x-3 text-[11px] sm:text-xs">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">
                    DTS Lic # {{ $settings->dts_license ?: '4521' }}
                </span>
                <span>NTN: {{ $settings->ntn ?: '7823419-4' }}</span>
                <span class="hidden md:inline text-slate-600">|</span>
                <span class="hidden md:inline text-slate-400">Government Approved Travel Agency</span>
            </div>

            <div class="flex items-center space-x-4 text-[11px] sm:text-xs">
                @if ($settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="hover:text-white flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $settings->phone }}</span>
                    </a>
                @endif
                <span class="text-slate-600">|</span>
                <span class="font-bold text-sky-400">Currency: {{ $settings->default_currency ?: 'PKR' }}</span>
                <a href="{{ route('admin.inquiries') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Admin Portal &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition">
                    <svg class="w-6 h-6 transform -rotate-45" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-slate-900 tracking-tight leading-none">
                        {{ $settings->business_name ?: 'FlyAero Travels' }}
                    </span>
                    <span class="text-xs text-indigo-600 font-semibold tracking-wide">Flights &bull; Umrah &bull; Northern Tours</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-700">
                <a href="#flight-search" class="hover:text-indigo-600 transition">Flight Inquiry</a>
                <a href="#featured-tours" class="hover:text-indigo-600 transition">Tour Packages</a>
                <a href="#umrah-packages" class="text-emerald-700 hover:text-emerald-800 flex items-center">
                    <span class="mr-1">🕋</span> Umrah 2026
                </a>
                <a href="#hotels-catalog" class="hover:text-indigo-600 transition">Partner Hotels</a>
                <a href="#destinations" class="hover:text-indigo-600 transition">Destinations</a>
            </nav>

            <!-- WhatsApp Action Button -->
            <div class="flex items-center space-x-3">
                @if ($cleanWhatsApp)
                    <a
                        href="https://wa.me/{{ $cleanWhatsApp }}?text=Assalam-o-Alaikum%20FlyAero!%20I%20would%20like%20to%20inquire%20about%20flights%20and%20packages."
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition cursor-pointer"
                    >
                        <!-- WhatsApp SVG -->
                        <svg class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.761.782 2.802.782h.001c3.182 0 5.768-2.587 5.769-5.766 0-3.18-2.587-5.768-5.776-5.768zm3.376 8.163c-.141.396-.713.738-1.002.783-.289.046-.66.072-2.128-.538-1.879-.78-3.085-2.693-3.18-2.82-.095-.126-.763-1.014-.763-1.934 0-.919.48-1.371.651-1.557.171-.186.374-.233.498-.233.125 0 .25.003.359.008.115.006.269-.044.421.32.156.375.534 1.302.58 1.396.047.094.078.204.016.327-.063.125-.094.204-.188.314-.093.11-.197.246-.282.33-.094.094-.192.196-.083.383.109.188.486.802 1.042 1.298.717.639 1.321.836 1.509.93.188.094.297.079.407-.047.109-.125.469-.547.594-.734.125-.188.25-.156.422-.094.172.062 1.094.516 1.282.609.188.094.313.141.359.219.047.078.047.453-.094.849z"/>
                        </svg>
                        <span>WhatsApp Chat</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 text-sm border-t border-slate-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: About Agency -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                            FA
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">{{ $settings->business_name ?: 'FlyAero Travels' }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Premier Pakistani travel & tours agency providing certified Umrah pilgrim services, GDS airline ticketing (Sabre/Amadeus), and luxury guided group tours across Northern Pakistan.
                    </p>
                    <div class="text-xs text-slate-400 space-y-1 pt-1">
                        <div><strong>DTS License:</strong> {{ $settings->dts_license ?: 'DTS-4521' }}</div>
                        <div><strong>NTN:</strong> {{ $settings->ntn ?: '7823419-4' }}</div>
                        <div><strong>STRN:</strong> {{ $settings->strn ?: '3277876123456' }}</div>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Popular Destinations</h4>
                    <ul class="text-xs space-y-2 text-slate-400">
                        <li><a href="#destinations" class="hover:text-white">Hunza Valley, Gilgit-Baltistan</a></li>
                        <li><a href="#destinations" class="hover:text-white">Skardu & Deosai Plains</a></li>
                        <li><a href="#destinations" class="hover:text-white">Swat & Malam Jabba</a></li>
                        <li><a href="#umrah-packages" class="hover:text-white">Makkah & Madinah Umrah</a></li>
                        <li><a href="#destinations" class="hover:text-white">Baku City, Azerbaijan</a></li>
                        <li><a href="#destinations" class="hover:text-white">Dubai & Abu Dhabi, UAE</a></li>
                    </ul>
                </div>

                <!-- Col 3: Accepted Payment Methods -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Secure Payment Methods</h4>
                    <p class="text-xs text-slate-400">
                        We accept multi-channel verified Pakistani & international payments in <strong>PKR</strong>:
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300 font-medium flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span> Raast P2P / P2M
                        </div>
                        <div class="bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300 font-medium flex items-center">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 mr-2"></span> 1Link Online Bank
                        </div>
                        <div class="bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300 font-medium flex items-center">
                            <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span> JazzCash / EasyPaisa
                        </div>
                        <div class="bg-slate-900 p-2.5 rounded-lg border border-slate-800 text-slate-300 font-medium flex items-center">
                            <span class="w-2 h-2 rounded-full bg-sky-400 mr-2"></span> Visa / Mastercard
                        </div>
                    </div>
                </div>

                <!-- Col 4: Official Contact -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Operations Desk</h4>
                    <p class="text-xs text-slate-400">
                        {{ $settings->address ?: 'Suite 402, Blue Area, Jinnah Avenue, Islamabad, Pakistan' }}
                    </p>
                    <div class="space-y-1.5 text-xs text-slate-300">
                        <div><strong>Phone:</strong> {{ $settings->phone ?: '+92 51 2345678' }}</div>
                        <div><strong>WhatsApp:</strong> {{ $settings->whatsapp_number ?: '+92 300 1234567' }}</div>
                        <div><strong>Email:</strong> {{ $settings->email ?: 'operations@flyaero.pk' }}</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $settings->business_name ?: 'FlyAero Travels & Tours' }}. All rights reserved.</p>
                <div class="flex items-center space-x-4">
                    <span>Base Currency: <strong>{{ $settings->default_currency ?: 'PKR' }}</strong></span>
                    <span>&bull;</span>
                    <a href="{{ route('admin.settings') }}" class="hover:text-slate-300">Admin Control Panel</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Support Button -->
    @if ($cleanWhatsApp)
        <aside aria-label="WhatsApp quick chat" class="fixed bottom-6 right-6 z-50">
            <a
                href="https://wa.me/{{ $cleanWhatsApp }}?text=Assalam-o-Alaikum!%20I%20am%20interested%20in%20flight%20booking%20and%20packages."
                target="_blank"
                rel="noopener"
                class="group flex items-center space-x-2.5 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-3 rounded-full shadow-2xl shadow-emerald-500/40 hover:scale-105 active:scale-95 transition cursor-pointer"
                aria-label="Chat on WhatsApp with travel agent"
            >
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.761.782 2.802.782h.001c3.182 0 5.768-2.587 5.769-5.766 0-3.18-2.587-5.768-5.776-5.768zm3.376 8.163c-.141.396-.713.738-1.002.783-.289.046-.66.072-2.128-.538-1.879-.78-3.085-2.693-3.18-2.82-.095-.126-.763-1.014-.763-1.934 0-.919.48-1.371.651-1.557.171-.186.374-.233.498-.233.125 0 .25.003.359.008.115.006.269-.044.421.32.156.375.534 1.302.58 1.396.047.094.078.204.016.327-.063.125-.094.204-.188.314-.093.11-.197.246-.282.33-.094.094-.192.196-.083.383.109.188.486.802 1.042 1.298.717.639 1.321.836 1.509.93.188.094.297.079.407-.047.109-.125.469-.547.594-.734.125-.188.25-.156.422-.094.172.062 1.094.516 1.282.609.188.094.313.141.359.219.047.078.047.453-.094.849z"/>
                </svg>
                <span class="font-bold text-xs tracking-wide hidden sm:inline">WhatsApp Booking Desk</span>
            </a>
        </aside>
    @endif

    @livewireScripts
</body>
</html>
