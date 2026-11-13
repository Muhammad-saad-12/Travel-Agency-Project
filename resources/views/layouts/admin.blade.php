<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'FlightDesk Admin' }} | {{ config('app.name', 'Flight Booking System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="lg:w-72 bg-slate-900 text-slate-200 flex-shrink-0 flex flex-col justify-between border-r border-slate-800">
            <!-- Brand & Navigation -->
            <div class="p-6">
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-400 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                            <!-- Airplane Icon -->
                            <svg class="w-6 h-6 transform -rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-base font-bold text-white tracking-tight leading-none">FlightDesk</span>
                            <span class="text-xs text-sky-400 font-medium">B2B Travel Operations</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 space-y-1">
                    <!-- Command & Intelligence -->
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Executive Center</p>

                    <!-- Operations Dashboard (Module 3) -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 3</span>
                    </a>

                    <!-- Advanced Analytics (Module 15) -->
                    <a href="{{ route('admin.analytics') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.analytics') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.analytics') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analytics & Reports
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.analytics') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 15</span>
                    </a>

                    <!-- Transactions & Back-Office -->
                    <p class="px-3 pt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Transactions & Engines</p>

                    <!-- Payments (Module 14) -->
                    <a href="{{ route('admin.payments') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.payments*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.payments*') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Payments (Raast/1Link)
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.payments*') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 14</span>
                    </a>

                    <!-- Booking Management (Modules 7 & 8) -->
                    <a href="{{ route('admin.bookings') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.bookings*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.bookings*') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Bookings
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.bookings*') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 7/8</span>
                    </a>

                    <!-- GDS PNR Decoder (Modules 13 & 14) -->
                    <a href="{{ route('admin.gds.importer') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.gds.importer*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.gds.importer*') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            GDS Decoder
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.gds.importer*') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 13/14</span>
                    </a>

                    <!-- Offline Ticketing & Refunds (Modules 15 & 16) -->
                    <a href="{{ route('admin.ticketing.queue') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.ticketing.queue*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.ticketing.queue*') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Offline & Refunds
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.ticketing.queue*') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 15/16</span>
                    </a>

                    <!-- Deposit & Credit Ledger (Modules 17 & 18) -->
                    <a href="{{ route('admin.ledger') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.ledger*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.ledger*') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Deposit & Ledger
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.ledger*') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 17/18</span>
                    </a>

                    <!-- Customer Inquiries -->
                    <p class="px-3 pt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Customer Desks</p>

                    <!-- Flight Inquiries (Module 5) -->
                    <a href="{{ route('admin.inquiries') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.inquiries') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.inquiries') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Flight Inquiries
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.inquiries') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 5</span>
                    </a>

                    <!-- Phase 1B Catalog Modules -->
                    <p class="px-3 pt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Catalog</p>
                    
                    <!-- Destinations (Module 12) -->
                    <a href="{{ route('admin.destinations') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.destinations') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.destinations') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Destinations
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.destinations') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 12</span>
                    </a>

                    <!-- Hotels (Module 13) -->
                    <a href="{{ route('admin.hotels') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.hotels') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.hotels') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Hotels & Resorts
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.hotels') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 13</span>
                    </a>

                    <!-- Tour Packages (Module 11) -->
                    <a href="{{ route('admin.packages') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.packages') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.packages') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Tour Packages
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.packages') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 11</span>
                    </a>

                    <p class="px-3 pt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Settings</p>

                    <!-- Agency Settings (Module 16) -->
                    <a href="{{ route('admin.settings') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings') ? 'text-sky-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Agency Settings
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ request()->routeIs('admin.settings') ? 'bg-sky-400/20 text-sky-200' : 'bg-slate-800 text-slate-400' }}">Mod 16</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer Status -->
            <div class="p-6 border-t border-slate-800 bg-slate-900/60">
                <div class="rounded-xl bg-slate-800/80 p-3.5 border border-slate-700/60">
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5">
                        <span class="font-medium">Default Currency</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            {{ config('app.currency', 'PKR') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="font-medium">Public Portal</span>
                        <a href="{{ route('home') }}" target="_blank" class="text-sky-400 hover:text-sky-300 font-mono text-[11px] underline">Open Website &rarr;</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Header Bar -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10">
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">FlightDesk B2B Back-Office</span>
                        <span class="text-slate-300">|</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Live Operations
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('home') }}" class="text-xs font-medium text-slate-600 hover:text-indigo-600 flex items-center transition">
                            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Customer Portal
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
