@extends('dashboard.base')

@section('title', 'Manage Item')

@section('content')

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-3xl font-bold text-zinc-800">
                    Manage Item
                </h1>

                <p class="text-zinc-500 mt-1">
                    Purchase history & inventory management.
                </p>

            </div>

            <button id="addPurchaseBtn"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition">

                <i class="fa-solid fa-plus"></i>

                Add Purchase

            </button>

        </div>

        <!-- Item Summary -->

        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-5 border-b">

                <div>

                    <h2 id="itemName" class="text-2xl font-bold text-zinc-800">

                        {{ $item->item_name }}

                    </h2>

                    <p class="text-zinc-500 mt-1">

                        Manage purchases, pricing and stock.

                    </p>

                </div>

                @if ($item->status)
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-medium">

                        <i class="fa-solid fa-circle-check"></i>

                        Active

                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-medium">

                        <i class="fa-solid fa-circle-xmark"></i>

                        Inactive

                    </span>
                @endif

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5">

                <!-- Category -->

                <div class="p-6 border-r border-b xl:border-b-0">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">

                            <i class="fa-solid fa-layer-group text-lg"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-zinc-500">

                                Category

                            </p>

                            <h3 class="font-semibold text-zinc-800 mt-1">

                                {{ $item->category }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- Unit -->

                <div class="p-6 border-r border-b xl:border-b-0">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center">

                            <i class="fa-solid fa-ruler text-lg"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-zinc-500">

                                Unit

                            </p>

                            <h3 class="font-semibold mt-1">

                                {{ $item->unit }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- Stock -->

                <div class="p-6 border-r border-b xl:border-b-0">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">

                            <i class="fa-solid fa-cubes text-lg"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-zinc-500">

                                Current Stock

                            </p>

                            <h3 id="itemStock" class="text-2xl font-bold text-emerald-600 mt-1">

                                {{ $item->opening_stock }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- Price -->

                <div class="p-6 border-r border-b xl:border-b-0">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">

                            <i class="fa-solid fa-indian-rupee-sign text-lg"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-zinc-500">

                                Latest Price

                            </p>

                            <h3 id="purchasePrice" class="text-2xl font-bold mt-1">

                                ₹{{ number_format($item->purchase_price, 2) }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- Minimum Stock -->

                <div class="p-6">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">

                            <i class="fa-solid fa-triangle-exclamation text-lg"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-wider text-zinc-500">

                                Minimum Stock

                            </p>

                            <h3 class="text-2xl font-bold mt-1">

                                {{ $item->minimum_stock }}

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Purchase History -->

        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-5 border-b">

                <div>

                    <h2 class="text-xl font-bold text-zinc-800">

                        Purchase History

                    </h2>

                    <p class="text-sm text-zinc-500 mt-1">

                        All purchase transactions for this item.

                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <button id="refreshHistory" class="h-10 w-10 rounded-xl border hover:bg-zinc-100 transition">

                        <i class="fa-solid fa-rotate-right"></i>

                    </button>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-zinc-100">

                        <tr>

                            <th class="px-5 py-4 text-left font-semibold">

                                Purchase Date

                            </th>

                            <th class="px-5 py-4 text-center font-semibold">

                                Quantity

                            </th>

                            <th class="px-5 py-4 text-right font-semibold">
                                Total Amount


                            </th>

                            <th class="px-5 py-4 text-center font-semibold">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody id="purchaseHistoryTable">

                        <tr>

                            <td colspan="4" class="py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="h-16 w-16 rounded-full bg-zinc-100 flex items-center justify-center">

                                        <i class="fa-solid fa-box-open text-3xl text-zinc-400"></i>

                                    </div>

                                    <h3 class="mt-5 text-lg font-semibold text-zinc-700">

                                        No Purchase History

                                    </h3>

                                    <p class="mt-1 text-sm text-zinc-500">

                                        Click <strong>Add Purchase</strong> to create the first purchase.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Add Purchase Modal -->

    <div id="purchaseModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl">

            <!-- Header -->

            <div class="flex items-center justify-between px-6 py-5 border-b">

                <div>

                    <h2 class="text-xl font-bold text-zinc-800">
                        Add Purchase
                    </h2>

                    <p class="text-sm text-zinc-500 mt-1">
                        Add new purchase for
                        <strong>{{ $item->item_name }}</strong>
                    </p>

                </div>

                <button id="closePurchaseModal" class="h-10 w-10 rounded-lg hover:bg-zinc-100">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>

            <!-- Body -->
            <form id="purchaseForm" class="p-6 space-y-6">

                @csrf

                <!-- Purchase Date -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Purchase Date
                    </label>

                    <input type="date" id="purchase_date" name="purchase_date" value="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                </div>

                <!-- Quantity & Amount -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Quantity ({{ $item->unit }})
                        </label>

                        <input type="number" id="quantity" name="quantity" min="0.01" step="0.01"
                            placeholder="Enter Quantity"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Total Amount (₹)
                        </label>

                        <input type="number" id="total_amount" name="total_amount" min="0.01" step="0.01"
                            placeholder="Enter Total Bill Amount"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    </div>

                </div>

                <!-- Unit Price Preview -->

                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-zinc-600">

                            Calculated Unit Price

                        </span>

                        <span id="calculatedPrice" class="text-xl font-bold text-emerald-700">

                            ₹0.00 / {{ $item->unit }}

                        </span>

                    </div>

                    <p class="text-xs text-zinc-500 mt-2">

                        Unit Price = Total Amount ÷ Quantity

                    </p>

                </div>

                <!-- Remarks -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Remarks
                    </label>

                    <textarea id="remarks" name="remarks" rows="3" placeholder="Optional Remarks..."
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 resize-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>

                </div>

                <!-- Footer -->

                <div class="flex justify-end gap-3 border-t pt-5">

                    <button type="button" id="cancelPurchase"
                        class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100">

                        Cancel

                    </button>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>

                        Save Purchase

                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.manageItem = {
            itemId: {{ $item->id }},
            unit: "{{ $item->unit }}",
            ajaxUrl: "{{ route('dashboard.inventory.items.manage', $item) }}",
            purchaseUrl: "{{ route('dashboard.inventory.items.purchase', $item) }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/dashboard/inventory/items/manage.js') }}"></script>
@endpush
