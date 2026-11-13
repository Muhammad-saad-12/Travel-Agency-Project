<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Hotel Management</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                    Module 13
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                    Pricing: PKR
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Manage partner hotels, room inventories, luxury resort chalets, and Makkah & Madinah Haram-facing accommodations.
            </p>
        </div>

        <div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm shadow-sm shadow-indigo-600/30 transition cursor-pointer"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Hotel</span>
            </button>
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

    <!-- Filters & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="hotel_search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search</label>
                <input
                    type="text"
                    id="hotel_search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search hotel name, address..."
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                />
            </div>

            <div>
                <label for="hotel_filter_dest" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Destination</label>
                <select
                    id="hotel_filter_dest"
                    wire:model.live="filterDestination"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Destinations</option>
                    @foreach ($destinations as $dest)
                        <option value="{{ $dest->id }}">{{ $dest->name }} ({{ $dest->country }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="hotel_filter_stars" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Star Rating</label>
                <select
                    id="hotel_filter_stars"
                    wire:model.live="filterStars"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Star Categories</option>
                    <option value="5">5 Star Luxury</option>
                    <option value="4">4 Star Premium</option>
                    <option value="3">3 Star Standard</option>
                    <option value="2">2 Star Economy</option>
                    <option value="1">1 Star Budget</option>
                </select>
            </div>

            <div>
                <label for="hotel_filter_partner" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Partnership</label>
                <select
                    id="hotel_filter_partner"
                    wire:model.live="filterPartner"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Hotels</option>
                    <option value="1">Official Partners Only</option>
                    <option value="0">Standard Properties</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                <thead class="bg-slate-50/80 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Hotel / Property</th>
                        <th class="px-6 py-3.5">Destination</th>
                        <th class="px-6 py-3.5 text-center">Stars</th>
                        <th class="px-6 py-3.5">Rate / Night (PKR)</th>
                        <th class="px-6 py-3.5 text-center">Rooms (Available / Total)</th>
                        <th class="px-6 py-3.5 text-center">Partner</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($hotels as $hotel)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $hotel->name }}</div>
                                <div class="text-xs text-slate-400 line-clamp-1 max-w-xs">{{ $hotel->location_address }}</div>
                                @if (!empty($hotel->room_types))
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach (array_slice($hotel->room_types, 0, 2) as $type)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600 font-medium">
                                                {{ $type }}
                                            </span>
                                        @endforeach
                                        @if (count($hotel->room_types) > 2)
                                            <span class="text-[10px] text-slate-400">+{{ count($hotel->room_types) - 2 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $hotel->destination?->name ?? 'N/A' }}</span>
                                <div class="text-xs text-slate-400">{{ $hotel->destination?->city }}, {{ $hotel->destination?->country }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-0.5 text-amber-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $hotel->star_rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">
                                    PKR {{ number_format($hotel->nightly_rate_pkr) }}
                                </div>
                                <span class="text-[11px] text-slate-400">net per night</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $hotel->available_rooms > 5 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $hotel->available_rooms }} / {{ $hotel->total_rooms }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    type="button"
                                    wire:click="togglePartner({{ $hotel->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition {{ $hotel->is_partner ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                >
                                    {{ $hotel->is_partner ? 'Partner' : 'Standard' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $hotel->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold px-2 py-1 rounded hover:bg-indigo-50 transition cursor-pointer"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    wire:click="confirmDelete({{ $hotel->id }})"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold px-2 py-1 rounded hover:bg-rose-50 transition cursor-pointer"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm">No hotels found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $hotels->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $editingHotelId ? 'Edit Hotel' : 'Add New Hotel' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label for="h_name" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Hotel / Resort Name <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                id="h_name"
                                wire:model.live.debounce.300ms="name"
                                placeholder="e.g. Hunza Serena Inn"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }}"
                            />
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="h_dest" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Destination <span class="text-rose-500">*</span></label>
                            <select
                                id="h_dest"
                                wire:model.live="destination_id"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            >
                                <option value="">Select Destination</option>
                                @foreach ($destinations as $dest)
                                    <option value="{{ $dest->id }}">{{ $dest->name }} ({{ $dest->country }})</option>
                                @endforeach
                            </select>
                            @error('destination_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="h_stars" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Star Rating (1-5) <span class="text-rose-500">*</span></label>
                            <select
                                id="h_stars"
                                wire:model.live="star_rating"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            >
                                <option value="5">5 Star Luxury</option>
                                <option value="4">4 Star Premium</option>
                                <option value="3">3 Star Standard</option>
                                <option value="2">2 Star Economy</option>
                                <option value="1">1 Star Basic</option>
                            </select>
                            @error('star_rating') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="h_addr" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Location Address <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="h_addr"
                            wire:model.live.debounce.300ms="location_address"
                            placeholder="Full address, proximity to city center or Haram"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('location_address') ? 'border-rose-400' : 'border-slate-300' }}"
                        />
                        @error('location_address') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="h_rooms_types" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Room Types (comma separated) <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="h_rooms_types"
                            wire:model.live.debounce.300ms="room_types_input"
                            placeholder="e.g. Deluxe Room, Executive Suite, Family Quad"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                        />
                        @error('room_types_input') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="h_rate" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Nightly Rate (PKR) <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                id="h_rate"
                                wire:model.live.debounce.300ms="nightly_rate_pkr"
                                min="0"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            />
                            @error('nightly_rate_pkr') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="h_total_r" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Total Rooms <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                id="h_total_r"
                                wire:model.live.debounce.300ms="total_rooms"
                                min="1"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            />
                            @error('total_rooms') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="h_avail_r" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Available Rooms <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                id="h_avail_r"
                                wire:model.live.debounce.300ms="available_rooms"
                                min="0"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            />
                            @error('available_rooms') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input
                            type="checkbox"
                            id="h_partner"
                            wire:model.live="is_partner"
                            class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                        />
                        <label for="h_partner" class="text-sm font-medium text-slate-700">Official Contracted Partner Property</label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-xs"
                        >
                            Save Hotel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-900">Confirm Deletion</h3>
                <p class="text-sm text-slate-600">
                    Are you sure you want to delete this hotel property?
                </p>
                <div class="flex justify-end space-x-3 pt-3">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 rounded-xl border border-slate-300 text-sm font-medium text-slate-700"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="delete"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold"
                    >
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
