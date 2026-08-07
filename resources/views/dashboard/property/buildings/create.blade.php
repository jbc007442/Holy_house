@extends('dashboard.base')

@section('title', 'Create Building')

@section('content')

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-3xl font-bold text-zinc-800">
                    Create Building
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Add a new building to your property.
                </p>

            </div>

            <a href="{{ route('dashboard.property.buildings') }}"
                class="inline-flex items-center gap-2 h-11 px-5 rounded-xl border border-zinc-300 bg-white hover:bg-zinc-100 transition">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

        <!-- Form -->
        <form action="{{ route('dashboard.property.buildings.store') }}" method="POST">

            @csrf

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">

                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-zinc-200 bg-zinc-50">

                    <h2 class="text-lg font-semibold text-zinc-800">
                        Building Information
                    </h2>

                    <p class="text-sm text-zinc-500 mt-1">
                        Enter the details of your building.
                    </p>

                </div>

                <!-- Card Body -->
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Building Name -->
                        <div>

                            <label class="block text-sm font-medium text-zinc-700 mb-2">
                                Building Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Main Building"
                                class="w-full h-11 rounded-xl border @error('name') border-red-500 @else border-zinc-300 @enderror px-4 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        <!-- Building Code -->
                        <div>

                            <label class="block text-sm font-medium text-zinc-700 mb-2">
                                Building Code <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="code" value="{{ old('code') }}" placeholder="B001"
                                class="w-full h-11 rounded-xl border @error('code') border-red-500 @else border-zinc-300 @enderror px-4 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">

                            @error('code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

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

                                <div class="flex gap-3 floor-row">

                                    <input type="text" name="floors[]" placeholder="Ground Floor"
                                        class="flex-1 h-11 rounded-xl border border-zinc-300 px-4 focus:outline-none focus:ring-2 focus:ring-amber-400">

                                    <button type="button"
                                        class="remove-floor h-11 w-11 rounded-xl border border-red-300 text-red-500 hover:bg-red-50">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            </div>

                            @error('floors')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        <!-- Status -->
                        <div>

                            <label class="block text-sm font-medium text-zinc-700 mb-2">
                                Status
                            </label>

                            <select name="status"
                                class="w-full h-11 rounded-xl border @error('status') border-red-500 @else border-zinc-300 @enderror px-4 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">

                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                    <!-- Address -->
                    <div class="mt-6">

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Address
                        </label>

                        <textarea name="address" rows="3" placeholder="Building address"
                            class="w-full rounded-xl border @error('address') border-red-500 @else border-zinc-300 @enderror px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">{{ old('address') }}</textarea>

                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Description -->
                    <div class="mt-6">

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Description
                        </label>

                        <textarea name="description" rows="4" placeholder="Additional information about this building"
                            class="w-full rounded-xl border @error('description') border-red-500 @else border-zinc-300 @enderror px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                <!-- Card Footer -->
                <div class="border-t border-zinc-200 bg-zinc-50 px-6 py-4">

                    <div class="flex items-center justify-end gap-3">

                        <a href="{{ route('dashboard.property.buildings') }}"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-xl border border-zinc-300 bg-white hover:bg-zinc-100 transition">

                            Cancel

                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium transition">

                            <i class="fa-solid fa-floppy-disk mr-2"></i>

                            Save Building

                        </button>

                    </div>

                </div>

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
