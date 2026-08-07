@extends('dashboard.base')

@section('title', 'Edit Building')

@section('content')

    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Edit Building
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Update building information.
                </p>
            </div>

            <a href="{{ route('dashboard.property.buildings') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl hover:bg-zinc-50">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

        <form action="{{ route('dashboard.property.buildings.update', $building->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-zinc-200 p-6">

                <h2 class="text-lg font-semibold mb-6">
                    Building Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Building Name -->
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Building Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="name" value="{{ old('name', $building->name) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-400"
                            placeholder="Main Building">

                    </div>

                    <!-- Building Code -->
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Building Code <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="code" value="{{ old('code', $building->code) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-400"
                            placeholder="B001">

                    </div>

                    <!-- Floors -->
                    <div class="md:col-span-2">

                        <div class="flex items-center justify-between mb-3">

                            <label class="block text-sm font-medium text-zinc-700">
                                Floors <span class="text-red-500">*</span>
                            </label>

                            <button type="button" id="add-floor"
                                class="inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm">

                                <i class="fa-solid fa-plus"></i>

                                Add Floor

                            </button>

                        </div>

                        <div id="floors-container" class="space-y-3">

                            @php
                                $floors = old('floors', $building->floors->pluck('name')->toArray());
                            @endphp

                            @forelse($floors as $floor)
                                <div class="flex gap-3 floor-row">

                                    <input type="text" name="floors[]" value="{{ $floor }}"
                                        placeholder="Floor Name"
                                        class="flex-1 h-11 rounded-xl border border-zinc-300 px-4 focus:outline-none focus:ring-2 focus:ring-amber-400">

                                    <button type="button"
                                        class="remove-floor h-11 w-11 rounded-xl border border-red-300 text-red-500 hover:bg-red-50">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            @empty

                                <div class="flex gap-3 floor-row">

                                    <input type="text" name="floors[]" placeholder="Ground Floor"
                                        class="flex-1 h-11 rounded-xl border border-zinc-300 px-4 focus:outline-none focus:ring-2 focus:ring-amber-400">

                                    <button type="button"
                                        class="remove-floor h-11 w-11 rounded-xl border border-red-300 text-red-500 hover:bg-red-50">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>
                            @endforelse

                        </div>

                    </div>

                    <!-- Status -->
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Status
                        </label>

                        <select name="status"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-400">

                            <option value="active" {{ old('status', $building->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ old('status', $building->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

                <!-- Address -->
                <div class="mt-6">

                    <label class="block text-sm font-medium mb-2">
                        Address
                    </label>

                    <textarea name="address" rows="3"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-400"
                        placeholder="Building address">{{ old('address', $building->address) }}</textarea>

                </div>

                <!-- Description -->
                <div class="mt-6">

                    <label class="block text-sm font-medium mb-2">
                        Description
                    </label>

                    <textarea name="description" rows="4"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-400"
                        placeholder="Additional information">{{ old('description', $building->description) }}</textarea>

                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('dashboard.property.buildings') }}" class="px-6 py-3 border rounded-xl hover:bg-zinc-50">

                    Cancel

                </a>

                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                    Update Building

                </button>

            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('floors-container');
            const addButton = document.getElementById('add-floor');

            addButton.addEventListener('click', function() {

                const row = document.createElement('div');

                row.className = 'flex gap-3 floor-row';

                row.innerHTML = `
            <input
                type="text"
                name="floors[]"
                placeholder="Floor Name"
                class="flex-1 h-11 rounded-xl border border-zinc-300 px-4 focus:outline-none focus:ring-2 focus:ring-amber-400">

            <button
                type="button"
                class="remove-floor h-11 w-11 rounded-xl border border-red-300 text-red-500 hover:bg-red-50">

                <i class="fa-solid fa-trash"></i>

            </button>
        `;

                container.appendChild(row);
            });

            container.addEventListener('click', function(e) {

                const button = e.target.closest('.remove-floor');

                if (!button) return;

                if (container.querySelectorAll('.floor-row').length === 1) {
                    return;
                }

                button.closest('.floor-row').remove();
            });

        });
    </script>
@endpush
