@extends('dashboard.base')

@section('title', 'Stock Report')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Stock Report
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    View current inventory stock levels.
                </p>
            </div>

            <div class="flex gap-3">

                <button type="button" class="px-5 py-2.5 border border-zinc-300 rounded-xl hover:bg-zinc-100 transition">

                    <i class="fa-solid fa-file-pdf mr-2"></i>
                    PDF

                </button>

                <button type="button" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl transition">

                    <i class="fa-solid fa-file-excel mr-2"></i>
                    Excel

                </button>

            </div>

        </div>

        <!-- Filters -->

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

            <form id="filterForm" class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                    <input type="text" name="search" placeholder="Search Item..."
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                    <select name="category" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                        <option value="">
                            All Categories
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category }}">
                                {{ $category }}
                            </option>
                        @endforeach

                    </select>

                    <select name="status" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                        <option value="">
                            All Status
                        </option>

                        <option value="instock">
                            In Stock
                        </option>

                        <option value="low">
                            Low Stock
                        </option>

                        <option value="out">
                            Out of Stock
                        </option>

                    </select>

                    <button type="submit" class="bg-zinc-800 hover:bg-zinc-900 text-white rounded-xl">

                        Filter

                    </button>

                    <button type="button" id="resetBtn" class="border border-zinc-300 rounded-xl hover:bg-zinc-100">

                        Reset

                    </button>

                </div>

            </form>

        </div>

        <!-- Table -->

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold">
                                Item
                            </th>

                            <th class="px-6 py-4 text-left font-semibold">
                                Category
                            </th>

                            <th class="px-6 py-4 text-left font-semibold">
                                Unit
                            </th>

                            <th class="px-6 py-4 text-right font-semibold">
                                Current Stock
                            </th>

                            <th class="px-6 py-4 text-right font-semibold">
                                Minimum Stock
                            </th>

                            <th class="px-6 py-4 text-center font-semibold">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="stockTableBody">

                        <tr>

                            <td colspan="7" class="px-6 py-10 text-center text-zinc-500">

                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                                Loading...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->

            <div id="paginationWrapper" class="border-t border-zinc-200 px-6 py-4">

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        const stockRoute = "{{ route('dashboard.inventory.stock-report') }}";
    </script>

    <script src="{{ asset('js/dashboard/inventory/stock-report/stock-report.js') }}"></script>
@endpush
