@extends('dashboard.base')

@section('title', 'Items')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Items
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Manage inventory items.
                </p>
            </div>

            <a href="{{ route('dashboard.inventory.items.create') }}"
                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Item
            </a>

        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm text-zinc-500">Total Items</p>
                <h3 id="totalItems" class="text-3xl font-bold mt-2">0</h3>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm text-zinc-500">Active</p>
                <h3 id="activeItems" class="text-3xl font-bold text-green-600 mt-2">0</h3>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm text-zinc-500">Inactive</p>
                <h3 id="inactiveItems" class="text-3xl font-bold text-red-600 mt-2">0</h3>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm text-zinc-500">Low Stock</p>
                <h3 id="lowStockItems" class="text-3xl font-bold text-amber-600 mt-2">0</h3>
            </div>

        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border p-5">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <input type="text" id="searchFilter" placeholder="Search item..."
                    class="rounded-xl border border-zinc-300 px-4 py-3">

                <select id="categoryFilter" class="rounded-xl border border-zinc-300 px-4 py-3">

                    <option value="">All Categories</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category }}">
                            {{ $category }}
                        </option>
                    @endforeach

                </select>

                <select id="statusFilter" class="rounded-xl border border-zinc-300 px-4 py-3">

                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>

                </select>

                <button id="refreshItems" class="rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                    Refresh
                </button>

            </div>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-zinc-100">

                        <tr>

                            <th class="px-4 py-3 text-left">Item</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Unit</th>
                            <th class="px-4 py-3 text-center">Opening Stock</th>
                            <th class="px-4 py-3 text-center">Minimum Stock</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody id="itemTableBody">

                    </tbody>

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

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        window.itemConfig = {
            indexUrl: "{{ route('dashboard.inventory.items') }}",
            viewUrl: "{{ route('dashboard.inventory.items.show', ':id') }}",
            editUrl: "{{ route('dashboard.inventory.items.edit', ':id') }}",
            manageUrl: "{{ route('dashboard.inventory.items.manage', ':id') }}",
            destroyUrl: "{{ route('dashboard.inventory.items.destroy', ':id') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/dashboard/inventory/items/item.js') }}"></script>
@endpush
