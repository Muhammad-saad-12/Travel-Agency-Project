<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Destination Management</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Module 12
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Manage domestic flight and tour destinations in Pakistan, Middle Eastern religious pilgrimage hubs, and international vacation spots.
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
                <span>Add Destination</span>
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
                <label for="search" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Search</label>
                <input
                    type="text"
                    id="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search name, city, country..."
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                />
            </div>

            <div>
                <label for="filterType" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Type</label>
                <select
                    id="filterType"
                    wire:model.live="filterType"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Types (Domestic & International)</option>
                    <option value="domestic">Domestic (Pakistan)</option>
                    <option value="international">International</option>
                </select>
            </div>

            <div>
                <label for="filterRegion" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Region</label>
                <select
                    id="filterRegion"
                    wire:model.live="filterRegion"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Regions</option>
                    <option value="Northern Pakistan">Northern Pakistan</option>
                    <option value="Middle East">Middle East</option>
                    <option value="Central Asia">Central Asia</option>
                    <option value="Europe">Europe</option>
                </select>
            </div>

            <div>
                <label for="filterActive" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Status</label>
                <select
                    id="filterActive"
                    wire:model.live="filterActive"
                    class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs"
                >
                    <option value="">All Statuses</option>
                    <option value="1">Active Only</option>
                    <option value="0">Inactive</option>
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
                        <th class="px-6 py-3.5">Destination</th>
                        <th class="px-6 py-3.5">Location</th>
                        <th class="px-6 py-3.5">Type & Region</th>
                        <th class="px-6 py-3.5 text-center">Hotels / Packages</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($destinations as $dest)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $dest->name }}</div>
                                <div class="text-xs font-mono text-slate-400">/{{ $dest->slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $dest->city }}</div>
                                <div class="text-xs text-slate-500">{{ $dest->country }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @if ($dest->type === 'domestic')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Domestic
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                            International
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700 font-medium">
                                        {{ $dest->region }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $dest->hotels_count }} Hotels &bull; {{ $dest->tour_packages_count }} Tours
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $dest->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition {{ $dest->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                >
                                    {{ $dest->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $dest->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold px-2 py-1 rounded hover:bg-indigo-50 transition cursor-pointer"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    wire:click="confirmDelete({{ $dest->id }})"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold px-2 py-1 rounded hover:bg-rose-50 transition cursor-pointer"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm">No destinations found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $destinations->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $editingDestinationId ? 'Edit Destination' : 'Add New Destination' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="dest_name" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Name <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="dest_name"
                            wire:model.live.debounce.300ms="name"
                            placeholder="e.g. Hunza Valley"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}"
                        />
                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="dest_country" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                id="dest_country"
                                wire:model.live.debounce.300ms="country"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('country') ? 'border-rose-400' : 'border-slate-300' }}"
                            />
                            @error('country') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="dest_city" class="block text-xs font-semibold uppercase text-slate-700 mb-1">City <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                id="dest_city"
                                wire:model.live.debounce.300ms="city"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border {{ $errors->has('city') ? 'border-rose-400' : 'border-slate-300' }}"
                            />
                            @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="dest_type" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Type <span class="text-rose-500">*</span></label>
                            <select
                                id="dest_type"
                                wire:model.live="type"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            >
                                <option value="domestic">Domestic (Pakistan)</option>
                                <option value="international">International</option>
                            </select>
                        </div>

                        <div>
                            <label for="dest_region" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Region <span class="text-rose-500">*</span></label>
                            <select
                                id="dest_region"
                                wire:model.live="region"
                                class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            >
                                <option value="Northern Pakistan">Northern Pakistan</option>
                                <option value="Middle East">Middle East</option>
                                <option value="Central Asia">Central Asia</option>
                                <option value="Europe">Europe</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="dest_desc" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Description</label>
                        <textarea
                            id="dest_desc"
                            wire:model.live.debounce.300ms="description"
                            rows="3"
                            class="block w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300"
                            placeholder="Destination overview and key attractions..."
                        ></textarea>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input
                            type="checkbox"
                            id="dest_active"
                            wire:model.live="is_active"
                            class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                        />
                        <label for="dest_active" class="text-sm font-medium text-slate-700">Active for flight bookings & tour packages</label>
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
                            Save Destination
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
                    Are you sure you want to delete this destination? Linked hotels and tour packages may also be affected.
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
