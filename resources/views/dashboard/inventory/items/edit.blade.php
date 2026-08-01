@extends('dashboard.base')

@section('title', 'Edit Item')

@section('content')

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Edit Item
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Update inventory item information.
                </p>
            </div>

            <a href="{{ route('dashboard.inventory.items') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 transition">
                Back
            </a>

        </div>

        <form action="{{ route('dashboard.inventory.items.update', $item->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Item Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Item Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="item_name" value="{{ old('item_name', $item->item_name) }}"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('item_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>

                        <select name="category"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="">Select Category</option>

                            <option value="Kitchen" {{ old('category', $item->category) == 'Kitchen' ? 'selected' : '' }}>
                                Kitchen
                            </option>

                            <option value="Housekeeping"
                                {{ old('category', $item->category) == 'Housekeeping' ? 'selected' : '' }}>
                                Housekeeping
                            </option>

                            <option value="Laundry" {{ old('category', $item->category) == 'Laundry' ? 'selected' : '' }}>
                                Laundry
                            </option>

                            <option value="Other" {{ old('category', $item->category) == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                        @error('category')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="unit" value="{{ old('unit', $item->unit) }}"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('unit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Price -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Purchase Price <span class="text-red-500">*</span>
                        </label>

                        <input type="number" step="0.01" name="purchase_price"
                            value="{{ old('purchase_price', $item->purchase_price) }}"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            readonly>

                        @error('purchase_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opening Stock -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Opening Stock <span class="text-red-500">*</span>
                        </label>

                        <input type="number" name="opening_stock" value="{{ old('opening_stock', $item->opening_stock) }}"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            readonly>

                        @error('opening_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Stock -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Minimum Stock <span class="text-red-500">*</span>
                        </label>

                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $item->minimum_stock) }}"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('minimum_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Status
                        </label>

                        <select name="status"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="1" {{ old('status', $item->status) ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ !old('status', $item->status) ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Remarks
                        </label>

                        <textarea name="remarks" rows="4"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('remarks', $item->remarks) }}</textarea>

                        @error('remarks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8">

                    <a href="{{ route('dashboard.inventory.items') }}"
                        class="px-6 py-2.5 rounded-xl border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                        Update Item
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
