@extends('dashboard.base')

@section('title', 'Edit Stock Movement')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Edit Stock Movement
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Update stock movement details.
            </p>
        </div>

        <a href="{{ route('dashboard.inventory.stock-movement') }}"
            class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
            Back
        </a>

    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

        <form action="{{ route('dashboard.inventory.stock-movement.update', $stockMovement->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Item -->
                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Item <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="item_id"
                        name="item_id"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @foreach($items as $item)
                            <option
                                value="{{ $item->id }}"
                                data-stock="{{ $item->current_stock }}"
                                @selected(old('item_id', $stockMovement->item_id) == $item->id)>

                                {{ $item->item_name }}

                            </option>
                        @endforeach

                    </select>

                    @error('item_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-2 flex items-center gap-2">

                        <span class="text-sm text-zinc-500">
                            Available Stock:
                        </span>

                        <span
                            id="currentStock"
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

                    <select
                        name="type"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option
                            value="out"
                            @selected(old('type', $stockMovement->type) == 'out')>
                            Stock Out
                        </option>

                        <option
                            value="adjustment"
                            @selected(old('type', $stockMovement->type) == 'adjustment')>
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

                    <input
                        id="quantity"
                        type="number"
                        min="1"
                        name="quantity"
                        value="{{ old('quantity', $stockMovement->quantity) }}"
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

                    <input
                        type="text"
                        name="reference"
                        value="{{ old('reference', $stockMovement->reference) }}"
                        placeholder="Reference"
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

                    <textarea
                        rows="4"
                        name="remarks"
                        placeholder="Enter remarks..."
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('remarks', $stockMovement->remarks) }}</textarea>

                    @error('remarks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            <div class="border-t border-zinc-200 px-8 py-5 flex justify-end gap-3">

                <a
                    href="{{ route('dashboard.inventory.stock-movement') }}"
                    class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">

                    Update Movement

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const currentStock = document.getElementById('currentStock');
    const form = document.querySelector('form');

    // Original quantity before editing
    const originalQty = {{ $stockMovement->quantity }};

    function updateStock() {

        const option = itemSelect.options[itemSelect.selectedIndex];

        if (!option || !option.dataset.stock) {
            currentStock.textContent = '-';
            quantityInput.removeAttribute('max');
            return;
        }

        const current = parseInt(option.dataset.stock) || 0;
        const entered = parseInt(quantityInput.value) || 0;

        // Maximum quantity user can enter
        const maxAllowed = current + originalQty;

        // Remaining stock after this update
        const remaining = maxAllowed - entered;

        currentStock.textContent = remaining;
        quantityInput.max = maxAllowed;

        if (entered > maxAllowed) {
            quantityInput.setCustomValidity(`Only ${maxAllowed} item(s) available.`);
        } else {
            quantityInput.setCustomValidity('');
        }

        quantityInput.reportValidity();
    }

    updateStock();

    itemSelect.addEventListener('change', updateStock);
    quantityInput.addEventListener('input', updateStock);

    form.addEventListener('submit', function (e) {

        updateStock();

        if (!quantityInput.checkValidity()) {
            e.preventDefault();
            quantityInput.reportValidity();
        }

    });

});
</script>
@endpush