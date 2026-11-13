<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tour Package Management</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                    Module 11
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Pakistani & Umrah Tours
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Create and manage guided Northern Pakistan group itineraries, executive VIP Umrah packages, and international holiday experiences.
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
                <span>Add Tour Package</span>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="pkg_search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search</label>
                <input
                    type="text"
                    id="pkg_search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search package title..."
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                />
            </div>

            <div>
                <label for="pkg_dest" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Destination</label>
                <select
                    id="pkg_dest"
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
                <label for="pkg_diff" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Difficulty</label>
                <select
                    id="pkg_diff"
                    wire:model.live="filterDifficulty"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Difficulties</option>
                    <option value="Easy">Easy</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Challenging">Challenging</option>
                </select>
            </div>

            <div>
                <label for="pkg_umrah" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Tour Type</label>
                <select
                    id="pkg_umrah"
                    wire:model.live="filterUmrah"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Tour Types</option>
                    <option value="1">Umrah Packages Only</option>
                    <option value="0">General Holidays</option>
                </select>
            </div>

            <div>
                <label for="pkg_feat" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Featured</label>
                <select
                    id="pkg_feat"
                    wire:model.live="filterFeatured"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Packages</option>
                    <option value="1">Featured Only</option>
                    <option value="0">Standard</option>
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
                        <th class="px-6 py-3.5">Tour Package Title</th>
                        <th class="px-6 py-3.5">Destination</th>
                        <th class="px-6 py-3.5 text-center">Duration</th>
                        <th class="px-6 py-3.5">Package Price (PKR)</th>
                        <th class="px-6 py-3.5 text-center">Difficulty</th>
                        <th class="px-6 py-3.5 text-center">Category / Flags</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $package->title }}</div>
                                <div class="text-xs font-mono text-slate-400">/{{ $package->slug }}</div>
                                @if (!empty($package->inclusions))
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach (array_slice($package->inclusions, 0, 2) as $inc)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-indigo-50 text-indigo-700 font-medium">
                                                {{ $inc }}
                                            </span>
                                        @endforeach
                                        @if (count($package->inclusions) > 2)
                                            <span class="text-[10px] text-slate-400">+{{ count($package->inclusions) - 2 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $package->destination?->name ?? 'N/A' }}</span>
                                <div class="text-xs text-slate-400">{{ $package->destination?->city }}, {{ $package->destination?->country }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-800">
                                    {{ $package->duration_days }} Days
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">
                                    PKR {{ number_format($package->price_pkr) }}
                                </div>
                                <span class="text-[11px] text-slate-400">per person total</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($package->difficulty_rating === 'Easy')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Easy
                                    </span>
                                @elseif ($package->difficulty_rating === 'Moderate')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Moderate
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                        Challenging
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    @if ($package->is_umrah)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-600 text-white shadow-xs">
                                            🕋 Umrah Special
                                        </span>
                                    @endif
                                    <button
                                        type="button"
                                        wire:click="toggleFeatured({{ $package->id }})"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium cursor-pointer transition {{ $package->is_featured ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}"
                                    >
                                        {{ $package->is_featured ? '★ Featured' : 'Standard' }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $package->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold px-2 py-1 rounded hover:bg-indigo-50 transition cursor-pointer"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    wire:click="confirmDelete({{ $package->id }})"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold px-2 py-1 rounded hover:bg-rose-50 transition cursor-pointer"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm">No tour packages found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $packages->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-2xl border border-slate-100 my-8">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $editingPackageId ? 'Edit Tour Package' : 'Create New Tour Package' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="pkg_title" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Package Title <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="pkg_title"
                            wire:model.live.debounce.300ms="title"
                            placeholder="e.g. 14-Day Executive Umrah Pilgrimage"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('title') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }}"
                        />
                        @error('title') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pkg_m_dest" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Destination <span class="text-rose-500">*</span></label>
                            <select
                                id="pkg_m_dest"
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
                            <label for="pkg_duration" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Duration (Days) <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                id="pkg_duration"
                                wire:model.live.debounce.300ms="duration_days"
                                min="1"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            />
                            @error('duration_days') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pkg_price" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Price Per Person (PKR) <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                id="pkg_price"
                                wire:model.live.debounce.300ms="price_pkr"
                                min="0"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            />
                            @error('price_pkr') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="pkg_diff_select" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Difficulty Rating <span class="text-rose-500">*</span></label>
                            <select
                                id="pkg_diff_select"
                                wire:model.live="difficulty_rating"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            >
                                <option value="Easy">Easy (Family / Senior Friendly)</option>
                                <option value="Moderate">Moderate (Active Sightseeing / Trekking)</option>
                                <option value="Challenging">Challenging (High Altitude Wilderness)</option>
                            </select>
                            @error('difficulty_rating') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="pkg_inclusions" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Package Inclusions (One per line)</label>
                        <textarea
                            id="pkg_inclusions"
                            wire:model.live.debounce.300ms="inclusions_text"
                            rows="3"
                            placeholder="Return Flight Tickets&#10;Hotel Stay with Breakfast&#10;Private AC Transport&#10;Ziyarat Guide"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 font-mono text-xs"
                        ></textarea>
                    </div>

                    <div>
                        <label for="pkg_itinerary" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Day-by-Day Itinerary (One per line)</label>
                        <textarea
                            id="pkg_itinerary"
                            wire:model.live.debounce.300ms="itinerary_text"
                            rows="4"
                            placeholder="Day 1: Arrival & Transfer&#10;Day 2: City Tour & Landmarks&#10;Day 3: Scenic Excursion"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 font-mono text-xs"
                        ></textarea>
                    </div>

                    <div class="flex items-center space-x-6 pt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.live="is_umrah"
                                class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                            />
                            <span class="text-sm font-medium text-slate-700">🕋 Religious Umrah Package</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.live="is_featured"
                                class="rounded text-amber-600 focus:ring-amber-500 h-4 w-4"
                            />
                            <span class="text-sm font-medium text-slate-700">★ Featured on Homepage</span>
                        </label>
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
                            Save Tour Package
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
                    Are you sure you want to delete this tour package?
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
