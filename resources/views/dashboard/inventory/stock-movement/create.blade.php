@extends('dashboard.base')

@section('title', 'New Stock Movement')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    New Stock Movement
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Record inventory stock in, stock out or adjustments.
                </p>
            </div>

            <a href="{{ route('dashboard.inventory.stock-movement') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                Back
            </a>

        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

            <form action="{{ route('dashboard.inventory.stock-movement.store') }}" method="POST">
                @csrf

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Item -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Item <span class="text-red-500">*</span>
                        </label>

                        <select id="item_id" name="item_id"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="">Select Item</option>

                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->current_stock }}"
                                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }}
                                </option>
                            @endforeach

                        </select>

                        @error('item_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-sm text-zinc-500">Available Stock:</span>
                            <span id="currentStock"
                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                -
                            </span>
                        </div>
                    </div>

                    <!-- Movement Type -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Movement Type <span class="text-red-500">*</span>
                        </label>

                        <select name="type"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="">Select Type</option>

                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>
                                Stock Out
                            </option>

                            <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>
                                Adjustment
                            </option>

                        </select>

                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>

                        <input id="quantity" type="number" name="quantity" min="1" value="{{ old('quantity') }}"
                            placeholder="Enter Quantity"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Reference
                        </label>

                        <input type="text" name="reference" value="{{ old('reference') }}"
                            placeholder="Purchase Bill, Manual Adjustment..."
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('reference')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Remarks
                        </label>

                        <textarea rows="4" name="remarks" placeholder="Enter remarks..."
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Footer -->
                <div class="border-t border-zinc-200 px-8 py-5 flex justify-end gap-3">

                    <a href="{{ route('dashboard.inventory.stock-movement') }}"
                        class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                        Save Movement
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const itemSelect = document.getElementById('item_id');
            const quantityInput = document.getElementById('quantity');
            const currentStock = document.getElementById('currentStock');
            const form = document.querySelector('form');

            function getCurrentStock() {
                const option = itemSelect.options[itemSelect.selectedIndex];

                if (!option || !option.dataset.stock) {
                    currentStock.textContent = '-';
                    quantityInput.removeAttribute('max');
                    return 0;
                }

                const stock = parseInt(option.dataset.stock) || 0;

                currentStock.textContent = stock;
                quantityInput.max = stock;

                return stock;
            }

            getCurrentStock();

            itemSelect.addEventListener('change', getCurrentStock);

            quantityInput.addEventListener('input', function() {

                const stock = getCurrentStock();
                const qty = parseInt(this.value) || 0;

                if (qty > stock) {
                    this.setCustomValidity(`Only ${stock} item(s) available.`);
                } else {
                    this.setCustomValidity('');
                }

                this.reportValidity();
            });

            form.addEventListener('submit', function(e) {

                const stock = getCurrentStock();
                const qty = parseInt(quantityInput.value) || 0;

                if (qty > stock) {
                    e.preventDefault();

                    quantityInput.setCustomValidity(`Only ${stock} item(s) available.`);
                    quantityInput.reportValidity();
                } else {
                    quantityInput.setCustomValidity('');
                }
            });

        });
    </script>
@endpush
