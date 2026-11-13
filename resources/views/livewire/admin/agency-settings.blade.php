<div class="space-y-8">
    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Agency Settings</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                    Module 16
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Base Currency: {{ $default_currency }}
                </span>
            </div>
            <p class="mt-1.5 text-sm text-slate-500 max-w-2xl">
                Configure your agency legal identities, tax credentials, operational contacts, and default accounting currency for flight bookings.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button
                type="button"
                wire:click="save"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm shadow-sm shadow-indigo-600/30 hover:shadow-md transition active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
            >
                <!-- Loading Spinner -->
                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <!-- Disk Icon -->
                <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <!-- Feedback Banner / Flash Message -->
    @if ($savedSuccessfully || session('status'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm transition">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-emerald-900">Settings Saved</h4>
                        <p class="text-xs text-emerald-700">
                            {{ session('status') ?? 'Agency configuration updated successfully across all flight booking documents.' }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    wire:click="$set('savedSuccessfully', false)"
                    class="text-emerald-500 hover:text-emerald-700 p-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Section 1: Business Profile -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-7 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-semibold text-sm">
                                01
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Agency Business Identity</h3>
                                <p class="text-xs text-slate-500">Legal commercial entity name and physical registered office</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Business / Agency Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="business_name"
                                    wire:model.live.debounce.300ms="business_name"
                                    placeholder="e.g. FlyAero Travels & Tours (Pvt) Ltd."
                                    class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('business_name') ? 'border-rose-400 bg-rose-50/20 text-rose-900 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                                />
                                @error('business_name')
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @enderror
                            </div>
                            @error('business_name')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-400">Primary trading title printed on ticket vouchers and booking confirmations.</p>
                            @enderror
                        </div>

                        <!-- DTS License -->
                        <div>
                            <label for="dts_license" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Department of Tourist Services (DTS) License
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="dts_license"
                                    wire:model.live.debounce.300ms="dts_license"
                                    placeholder="e.g. DTS-4521 / ID-9821"
                                    class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('dts_license') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                                />
                            </div>
                            @error('dts_license')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-400">Required Pakistani tourism regulatory license for travel agencies and tour operators.</p>
                            @enderror
                        </div>

                        <!-- Physical Address -->
                        <div>
                            <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Registered Physical Address
                            </label>
                            <textarea
                                id="address"
                                wire:model.live.debounce.300ms="address"
                                rows="3"
                                placeholder="Suite / Office address, Floor, Building, City, Country"
                                class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                            ></textarea>
                            @error('address')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Tax & Legal Identifiers -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-7 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-semibold text-sm">
                                02
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Tax & Regulatory Credentials</h3>
                                <p class="text-xs text-slate-500">Federal Board of Revenue (FBR) identifiers for invoices and tax compliance</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- NTN -->
                        <div>
                            <label for="ntn" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                National Tax Number (NTN)
                            </label>
                            <input
                                type="text"
                                id="ntn"
                                wire:model.live.debounce.300ms="ntn"
                                placeholder="e.g. 7823419-4"
                                class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('ntn') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                            />
                            @error('ntn')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-400">7-digit NTN with verification digit.</p>
                            @enderror
                        </div>

                        <!-- STRN -->
                        <div>
                            <label for="strn" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Sales Tax Registration Number (STRN)
                            </label>
                            <input
                                type="text"
                                id="strn"
                                wire:model.live.debounce.300ms="strn"
                                placeholder="e.g. 3277876123456"
                                class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('strn') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                            />
                            @error('strn')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-400">Sales tax number for provincial / federal services.</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Communication & Customer Contact -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-7 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-semibold text-sm">
                                03
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Communication Channels</h3>
                                <p class="text-xs text-slate-500">Contact points shown on flight confirmations and agent inquiries</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Official Landline / Desk Phone
                            </label>
                            <input
                                type="text"
                                id="phone"
                                wire:model.live.debounce.300ms="phone"
                                placeholder="e.g. +92 51 2345678"
                                class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                            />
                            @error('phone')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                WhatsApp Support Number
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="whatsapp_number"
                                    wire:model.live.debounce.300ms="whatsapp_number"
                                    placeholder="e.g. +92 300 1234567"
                                    class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('whatsapp_number') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                                />
                            </div>
                            @error('whatsapp_number')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-slate-400">Used for automated e-ticket PDF sending and PNR schedule changes.</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Official Operations Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                wire:model.live.debounce.300ms="email"
                                placeholder="e.g. reservations@flyaero.pk"
                                class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs placeholder-slate-400 transition"
                            />
                            @error('email')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Currency & Financial Configuration -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-7 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-semibold text-sm">
                                04
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Financial Base Currency</h3>
                                <p class="text-xs text-slate-500">Base operational currency for flight search pricing and agency ledgers</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="default_currency" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Default Accounting Currency <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="default_currency"
                            wire:model.live="default_currency"
                            class="block w-full px-4 py-2.5 text-sm rounded-xl border {{ $errors->has('default_currency') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} shadow-xs font-semibold text-slate-800 transition"
                        >
                            <option value="PKR">PKR - Pakistani Rupee (Standard)</option>
                            <option value="USD">USD - United States Dollar</option>
                            <option value="AED">AED - United Arab Emirates Dirham</option>
                            <option value="SAR">SAR - Saudi Riyal</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="EUR">EUR - Euro</option>
                        </select>
                        @error('default_currency')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @else
                            <p class="mt-1.5 text-xs text-slate-500">
                                Default is set to <strong class="text-slate-800">PKR</strong>. All GDS airline fares (Sabre/Amadeus/Galileo) and local low-cost carriers (Fly Jinnah, Airblue, Serene Air, PIA) are converted and reconciled against this base currency.
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Bottom Save Button Bar -->
                <div class="flex items-center justify-end space-x-4 pt-2">
                    <button
                        type="button"
                        wire:click="resetToDefaults"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 text-sm font-medium transition cursor-pointer"
                    >
                        Reset Defaults
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm shadow-indigo-600/30 transition active:scale-[0.98] disabled:opacity-50 cursor-pointer"
                    >
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Save Agency Settings</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview Panel (Right 1 col) -->
        <div class="space-y-6">
            <!-- Ticket / Voucher Header Preview Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5 sticky top-24">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Live Voucher Preview</h4>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">Real-time</span>
                </div>

                <!-- Simulation Ticket Header Card -->
                <div class="rounded-xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-5 shadow-lg relative overflow-hidden">
                    <!-- Subtle Airplane Background Silhouette -->
                    <div class="absolute -right-6 -bottom-6 opacity-10 pointer-events-none">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                        </svg>
                    </div>

                    <div class="relative z-10 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-sky-400">Electronic Ticket Voucher</span>
                                <h3 class="text-base font-bold text-white tracking-tight mt-0.5">
                                    {{ $business_name ?: 'Your Agency Business Name' }}
                                </h3>
                            </div>
                            <div class="px-2 py-1 rounded bg-white/10 text-white font-mono text-xs font-bold">
                                {{ $default_currency }}
                            </div>
                        </div>

                        <!-- Regulatory Badges -->
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                            <div class="bg-white/5 rounded-lg p-2 border border-white/10">
                                <span class="block text-slate-400 text-[10px]">DTS License</span>
                                <span class="font-medium text-slate-200 truncate block">
                                    {{ $dts_license ?: 'Not Specified' }}
                                </span>
                            </div>
                            <div class="bg-white/5 rounded-lg p-2 border border-white/10">
                                <span class="block text-slate-400 text-[10px]">NTN / STRN</span>
                                <span class="font-medium text-slate-200 truncate block">
                                    {{ $ntn ?: ($strn ?: 'Not Specified') }}
                                </span>
                            </div>
                        </div>

                        <!-- Contact Line -->
                        <div class="pt-2 border-t border-white/10 text-[11px] text-slate-300 space-y-1">
                            @if($phone || $whatsapp_number)
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="truncate">{{ $phone ?: $whatsapp_number }}</span>
                                </div>
                            @endif

                            @if($email)
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="truncate">{{ $email }}</span>
                                </div>
                            @endif

                            @if($address)
                                <div class="flex items-start space-x-1.5 text-[10px] text-slate-400 pt-1">
                                    <svg class="w-3.5 h-3.5 text-sky-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="line-clamp-2">{{ $address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Module 16 Metadata & System Guidelines -->
                <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-4 space-y-3">
                    <h5 class="text-xs font-semibold text-slate-800 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Module 16 Specifications
                    </h5>
                    <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside">
                        <li>Real-time input validation on keystrokes/blur</li>
                        <li>DTS License validation for Tourist Services</li>
                        <li>FBR NTN & STRN corporate tax compliance</li>
                        <li>Base operational currency defaulting to <strong>PKR</strong></li>
                        <li>Cached settings retrieval via <strong>AgencySettingService</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
