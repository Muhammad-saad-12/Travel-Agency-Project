<div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-6 md:p-8 relative">
    <form wire:submit.prevent="submit" class="space-y-6">
        <!-- Trip Type Switcher -->
        <div class="flex flex-wrap items-center gap-3 pb-2 border-b border-slate-100">
            <label class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold cursor-pointer transition {{ $trip_type === 'round_trip' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <input type="radio" value="round_trip" wire:model.live="trip_type" class="sr-only" />
                <span>Round Trip</span>
            </label>
            <label class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold cursor-pointer transition {{ $trip_type === 'one_way' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <input type="radio" value="one_way" wire:model.live="trip_type" class="sr-only" />
                <span>One Way</span>
            </label>
            <label class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold cursor-pointer transition {{ $trip_type === 'multi_city' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <input type="radio" value="multi_city" wire:model.live="trip_type" class="sr-only" />
                <span>Multi-City Leg</span>
            </label>

            <div class="ml-auto flex items-center space-x-2 text-xs text-slate-500 font-medium">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-semibold">
                    Instant PKR Quote
                </span>
            </div>
        </div>

        @if ($trip_type !== 'multi_city')
            <!-- Standard Origin / Destination & Dates -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Origin -->
                <div>
                    <label for="f_origin" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Departure City <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="f_origin"
                        wire:model.live.debounce.300ms="origin"
                        placeholder="e.g. Islamabad (ISB)"
                        class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('origin') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                    />
                    @error('origin') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Destination -->
                <div>
                    <label for="f_destination" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Destination City <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="f_destination"
                        wire:model.live.debounce.300ms="destination"
                        placeholder="e.g. Jeddah (JED)"
                        class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('destination') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                    />
                    @error('destination') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Departure Date -->
                <div>
                    <label for="f_dep_date" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Departure Date <span class="text-rose-500">*</span></label>
                    <input
                        type="date"
                        id="f_dep_date"
                        wire:model.live="departure_date"
                        class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('departure_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                    />
                    @error('departure_date') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Return Date -->
                <div>
                    <label for="f_ret_date" class="block text-xs font-semibold uppercase text-slate-600 mb-1">
                        Return Date {{ $trip_type === 'round_trip' ? '*' : '(Optional)' }}
                    </label>
                    <input
                        type="date"
                        id="f_ret_date"
                        wire:model.live="return_date"
                        {{ $trip_type === 'one_way' ? 'disabled' : '' }}
                        class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('return_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} {{ $trip_type === 'one_way' ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'shadow-xs' }}"
                    />
                    @error('return_date') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @else
            <!-- Multi-City Segments Builder -->
            <div class="space-y-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Multi-City Flight Legs</span>
                    <button
                        type="button"
                        wire:click="addSegment"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer flex items-center"
                    >
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Another Flight Leg
                    </button>
                </div>

                @foreach ($multi_city_segments as $idx => $segment)
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-3 items-center bg-white p-3 rounded-xl border border-slate-200">
                        <div class="md:col-span-1 text-xs font-bold text-slate-500">
                            Leg {{ $idx + 1 }}
                        </div>
                        <div class="md:col-span-2">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="multi_city_segments.{{ $idx }}.origin"
                                placeholder="From e.g. Islamabad"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="multi_city_segments.{{ $idx }}.destination"
                                placeholder="To e.g. Dubai"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300"
                            />
                        </div>
                        <div class="md:col-span-1">
                            <input
                                type="date"
                                wire:model.live="multi_city_segments.{{ $idx }}.date"
                                class="w-full text-xs px-2 py-2 rounded-lg border border-slate-300"
                            />
                        </div>
                        <div class="md:col-span-1 text-right">
                            @if (count($multi_city_segments) > 2)
                                <button
                                    type="button"
                                    wire:click="removeSegment({{ $idx }})"
                                    class="text-rose-500 hover:text-rose-700 text-xs font-bold p-1 cursor-pointer"
                                >
                                    Remove
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Passengers & Cabin Class Selectors -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50/70 rounded-2xl border border-slate-200/80">
            <div>
                <label for="p_adults" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Adults (12+ yrs)</label>
                <select id="p_adults" wire:model.live="adults" class="block w-full px-3 py-2 text-sm rounded-xl border border-slate-300 font-semibold text-slate-800">
                    @for ($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Adult' : 'Adults' }}</option>
                    @endfor
                </select>
                @error('adults') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="p_children" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Children (2-11 yrs)</label>
                <select id="p_children" wire:model.live="children" class="block w-full px-3 py-2 text-sm rounded-xl border border-slate-300 font-semibold text-slate-800">
                    @for ($i = 0; $i <= 8; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Child' : 'Children' }}</option>
                    @endfor
                </select>
                @error('children') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="p_infants" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Infants (&lt;2 yrs)</label>
                <select id="p_infants" wire:model.live="infants" class="block w-full px-3 py-2 text-sm rounded-xl border border-slate-300 font-semibold text-slate-800">
                    @for ($i = 0; $i <= 4; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Infant' : 'Infants' }}</option>
                    @endfor
                </select>
                @error('infants') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="p_cabin" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Cabin Class</label>
                <select id="p_cabin" wire:model.live="cabin_class" class="block w-full px-3 py-2 text-sm rounded-xl border border-slate-300 font-semibold text-slate-800">
                    <option value="Economy">Economy</option>
                    <option value="Premium Economy">Premium Economy</option>
                    <option value="Business">Business Class</option>
                    <option value="First">First Class</option>
                </select>
            </div>
        </div>

        <!-- Customer Contact Details & Notes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div>
                <label for="c_name" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="c_name"
                    wire:model.live.debounce.300ms="customer_name"
                    placeholder="e.g. Tariq Mehmood"
                    class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('customer_name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                />
                @error('customer_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="c_phone" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Phone / WhatsApp <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="c_phone"
                    wire:model.live.debounce.300ms="customer_phone"
                    placeholder="e.g. +92 300 1234567"
                    class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('customer_phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                />
                @error('customer_phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="c_email" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    id="c_email"
                    wire:model.live.debounce.300ms="customer_email"
                    placeholder="e.g. tariq@gmail.com"
                    class="block w-full px-3.5 py-2.5 text-sm rounded-xl border {{ $errors->has('customer_email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500' }} shadow-xs"
                />
                @error('customer_email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
                <label for="c_notes" class="block text-xs font-semibold uppercase text-slate-600 mb-1">Special Preferences / Airline Notes (Optional)</label>
                <input
                    type="text"
                    id="c_notes"
                    wire:model.live.debounce.300ms="notes"
                    placeholder="e.g. Prefer Saudia or PIA direct flight, extra 20kg baggage required, wheelchair assistance"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 shadow-xs"
                />
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between pt-4 border-t border-slate-100 gap-4">
            <div class="text-xs text-slate-500 flex items-center">
                <svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Lowest Fare Guaranteed &bull; GDS Live Verification &bull; No Booking Fee</span>
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-700 hover:to-sky-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition active:scale-[0.98] cursor-pointer"
            >
                <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-2.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <!-- Plane icon -->
                <svg wire:loading.remove wire:target="submit" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span>Request Flight Quotation (PKR)</span>
            </button>
        </div>
    </form>

    <!-- Success Confirmation Modal -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-8 text-center space-y-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
                <!-- Check Icon -->
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <h3 class="text-2xl font-bold text-slate-900">Inquiry Received!</h3>
                    <p class="text-sm text-slate-500">
                        Your flight inquiry has been dispatched to our ticketing desk. An airline specialist is calculating the best competitive fare for your route.
                    </p>
                </div>

                <!-- Reference Badge -->
                <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-500">Inquiry Reference Number</span>
                    <div class="text-2xl font-black font-mono text-indigo-700 tracking-wider">
                        {{ $generatedReference }}
                    </div>
                    <span class="text-[11px] text-slate-400">Save this reference for WhatsApp or phone status checks</span>
                </div>

                <div class="text-xs text-slate-500 space-y-1 text-left bg-slate-50 p-4 rounded-xl">
                    <div><strong>Route:</strong> {{ $origin }} &rarr; {{ $destination }} ({{ ucwords(str_replace('_', ' ', $trip_type)) }})</div>
                    <div><strong>Travelers:</strong> {{ $adults }} Adults{{ $children ? ", {$children} Children" : '' }}{{ $infants ? ", {$infants} Infants" : '' }} &bull; {{ $cabin_class }}</div>
                </div>

                <div>
                    <button
                        type="button"
                        wire:click="closeSuccessModal"
                        class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition cursor-pointer"
                    >
                        Close & Continue
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
