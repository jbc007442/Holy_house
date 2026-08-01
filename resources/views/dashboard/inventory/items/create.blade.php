@extends('dashboard.base')

@section('title', 'Add Item')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Add Item
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Create a new inventory item.
            </p>
        </div>

        <a href="{{ route('dashboard.inventory.items') }}"
            class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
            Back
        </a>

    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

        <form action="{{ route('dashboard.inventory.items.store') }}" method="POST">

            @csrf

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Item Name -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Item Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="item_name"
                        value="{{ old('item_name') }}"
                        placeholder="Water Bottle"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('item_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="category"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        <option value="">Select Category</option>
                        <option value="Kitchen" {{ old('category')=='Kitchen' ? 'selected' : '' }}>Kitchen</option>
                        <option value="Housekeeping" {{ old('category')=='Housekeeping' ? 'selected' : '' }}>Housekeeping</option>
                        <option value="Laundry" {{ old('category')=='Laundry' ? 'selected' : '' }}>Laundry</option>
                        <option value="Other" {{ old('category')=='Other' ? 'selected' : '' }}>Other</option>

                    </select>

                    @error('category')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="unit"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        <option value="">Select Unit</option>
                        <option value="Nos" {{ old('unit')=='Nos' ? 'selected' : '' }}>Nos</option>
                        <option value="Piece" {{ old('unit')=='Piece' ? 'selected' : '' }}>Piece</option>
                        <option value="Box" {{ old('unit')=='Box' ? 'selected' : '' }}>Box</option>
                        <option value="Bottle" {{ old('unit')=='Bottle' ? 'selected' : '' }}>Bottle</option>
                        <option value="Packet" {{ old('unit')=='Packet' ? 'selected' : '' }}>Packet</option>
                        <option value="Kg" {{ old('unit')=='Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Liter" {{ old('unit')=='Liter' ? 'selected' : '' }}>Liter</option>

                    </select>

                    @error('unit')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purchase Price -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Purchase Price <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="purchase_price"
                        value="{{ old('purchase_price') }}"
                        placeholder="0.00"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('purchase_price')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opening Stock -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Opening Stock <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="opening_stock"
                        value="{{ old('opening_stock') }}"
                        placeholder="100"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('opening_stock')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Stock -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Minimum Stock <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="minimum_stock"
                        value="{{ old('minimum_stock') }}"
                        placeholder="10"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('minimum_stock')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        <option value="1" {{ old('status',1)=='1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Inactive</option>

                    </select>

                    @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Remarks
                    </label>

                    <textarea
                        rows="4"
                        name="remarks"
                        placeholder="Enter remarks..."
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">{{ old('remarks') }}</textarea>

                    @error('remarks')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Footer -->
            <div class="border-t border-zinc-200 px-8 py-5 flex justify-end gap-3">

                <a href="{{ route('dashboard.inventory.items') }}"
                    class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                    Cancel
                </a>

                <button
                    type="submit"
                    onclick="showSaving()"
                    class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium transition">
                    Save Item
                </button>

            </div>

        </form>

    </div>

</div>

@endsection