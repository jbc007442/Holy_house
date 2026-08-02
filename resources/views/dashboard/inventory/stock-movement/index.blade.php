@extends('dashboard.base')

@section('title', 'Stock Movement')

@section('content')

    <div class="p-6 space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Stock Movement
                </h1>

                <p class="text-zinc-500 mt-1">
                    Manage all inventory stock movements.
                </p>
            </div>

            <a href="{{ route('dashboard.inventory.stock-movement.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl transition">

                <i class="fa-solid fa-plus mr-2"></i>
                New Movement

            </a>

        </div>

        <!-- Filters -->
        <div class="bg-white border border-zinc-200 rounded-xl p-5">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                <!-- Date -->
                <input type="date" id="dateFilter" class="w-full rounded-lg border border-zinc-300 px-4 py-3">

                <!-- Item -->
                <select id="itemFilter" class="w-full rounded-lg border border-zinc-300 px-4 py-3">

                    <option value="">All Items</option>

                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->item_name }}
                        </option>
                    @endforeach

                </select>

                <!-- Movement Type -->
                <select id="typeFilter" class="w-full rounded-lg border border-zinc-300 px-4 py-3">

                    <option value="">All Movement Types</option>
                    <option value="out">Stock Out</option>
                    <option value="adjustment">Adjustment</option>

                </select>

                <!-- Search -->
                <input type="text" id="searchFilter" placeholder="Search Item..."
                    class="w-full rounded-lg border border-zinc-300 px-4 py-3">

                <!-- Refresh -->
                <button id="refreshMovements" class="rounded-lg bg-zinc-800 hover:bg-zinc-900 text-white px-4">

                    Refresh

                </button>

            </div>

        </div>

        <!-- Table -->
        <table class="min-w-full table-fixed">
            <colgroup>
                <col class="w-44"> <!-- Date -->
                <col class="w-40"> <!-- Item -->
                <col class="w-52"> <!-- Movement -->
                <col class="w-28"> <!-- Quantity -->
                <col class="w-40"> <!-- Reference -->
                <col class="w-32"> <!-- Action -->
            </colgroup>

            <thead class="bg-zinc-100">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Item</th>
                    <th class="px-6 py-4 text-left font-semibold">Movement Type</th>
                    <th class="px-6 py-4 text-right font-semibold">Quantity</th>
                    <th class="px-6 py-4 text-left font-semibold">Reference</th>
                    <th class="px-6 py-4 text-center font-semibold">Action</th>
                </tr>
            </thead>

            <tbody id="movementTableBody"></tbody>
        </table>

        <div class="border-t bg-white px-6 py-4">
            <div class="flex items-center justify-between">

                <div id="paginationInfo" class="text-sm text-zinc-500">
                </div>

                <div id="pagination" class="flex items-center gap-2">
                </div>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        window.stockMovementConfig = {
            indexUrl: "{{ route('dashboard.inventory.stock-movement') }}",
            viewUrl: "{{ route('dashboard.inventory.stock-movement.show', ':id') }}",
            editUrl: "{{ route('dashboard.inventory.stock-movement.edit', ':id') }}",
            destroyUrl: "{{ route('dashboard.inventory.stock-movement.destroy', ':id') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/dashboard/inventory/stock-movement/stock-movement.js') }}"></script>
@endpush
