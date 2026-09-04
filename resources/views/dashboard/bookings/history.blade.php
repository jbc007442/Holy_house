@extends('dashboard.base')

@section('title', 'Booking History')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Booking History
                </h1>

                <p class="text-zinc-500 mt-1">
                    View all checked out booking history.
                </p>
            </div>

        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-zinc-200 p-5">

            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">

                <!-- Search -->
                <input
                    id="searchHistory"
                    type="text"
                    placeholder="Booking / Guest / Mobile"
                    class="rounded-xl border border-zinc-300 px-4 py-3 focus:border-amber-500 focus:outline-none"
                >

                <!-- From Date -->
                <input
                    id="fromDate"
                    type="date"
                    class="rounded-xl border border-zinc-300 px-4 py-3 focus:border-amber-500 focus:outline-none"
                >

                <!-- To Date -->
                <input
                    id="toDate"
                    type="date"
                    class="rounded-xl border border-zinc-300 px-4 py-3 focus:border-amber-500 focus:outline-none"
                >

                <!-- Building -->
                <select
                    id="buildingFilter"
                    class="rounded-xl border border-zinc-300 px-4 py-3 focus:border-amber-500 focus:outline-none"
                >
                    <option value="">
                        All Buildings
                    </option>

                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}">
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Search Button -->
                <button
                    id="searchBtn"
                    type="button"
                    class="rounded-xl bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 transition"
                >
                    <i class="fa-solid fa-magnifying-glass mr-2"></i>
                    Search
                </button>

                <!-- Reset Button -->
                <button
                    id="resetBtn"
                    type="button"
                    class="rounded-xl border border-zinc-300 hover:bg-zinc-100 px-4 py-3 transition"
                >
                    Reset
                </button>

                <!-- Export Excel -->
                <a
                    id="exportExcelBtn"
                    href="{{ route('dashboard.bookings.history.export') }}"
                    class="flex items-center justify-center rounded-xl bg-green-600 hover:bg-green-700 text-white px-4 py-3 transition"
                >
                    <i class="fa-solid fa-file-excel mr-2"></i>
                    Export Excel
                </a>

            </div>

        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

            <!-- Page Size -->
            <div
                class="flex flex-col gap-3 border-b border-zinc-200 bg-zinc-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
            >

                <div class="flex items-center gap-2 text-sm text-zinc-600">

                    <span>
                        Show
                    </span>

                    <select
                        id="perPage"
                        class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                        <option value="10">
                            10
                        </option>

                        <option value="25">
                            25
                        </option>

                        <option value="50">
                            50
                        </option>

                        <option value="100">
                            100
                        </option>
                    </select>

                    <span>
                        entries
                    </span>

                </div>

                <!-- Pagination Info -->
                <div
                    id="paginationInfo"
                    class="text-sm text-zinc-500"
                ></div>

            </div>

            <!-- Table -->
            <div class="w-full overflow-x-auto">

                <table class="min-w-[1400px] w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-5 py-4 text-left">
                                Booking No
                            </th>

                            <th class="px-5 py-4 text-left">
                                Guest
                            </th>

                            <th class="px-5 py-4 text-left">
                                Building
                            </th>

                            <th class="px-5 py-4 text-left">
                                Room
                            </th>

                            <th class="px-5 py-4 text-left">
                                Check In
                            </th>

                            <th class="px-5 py-4 text-left">
                                Check Out
                            </th>

                            <th class="px-5 py-4 text-center">
                                Total Days
                            </th>

                            <th class="px-5 py-4 text-center whitespace-nowrap">
                                Beds
                            </th>

                            <th class="px-5 py-4 text-right whitespace-nowrap">
                                Bed Price
                            </th>

                            <th class="px-5 py-4 text-left">
                                Total
                            </th>

                            <th class="px-5 py-4 text-right whitespace-nowrap">
                                GST
                            </th>

                            <th class="px-5 py-4 text-right whitespace-nowrap">
                                Grand Total
                            </th>

                            <th class="px-5 py-4 text-center">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="historyTable">

                        <tr>

                            <td
                                colspan="14"
                                class="py-12 text-center text-zinc-500"
                            >
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                                Loading booking history...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            <div
                id="paginationContainer"
                class="border-t bg-zinc-50 px-5 py-4"
            ></div>

        </div>

    </div>

@endsection

@push('scripts')

    <script>
        window.bookingHistoryConfig = {

            ajaxUrl: "{{ route('dashboard.bookings.history.ajax') }}",

            exportUrl: "{{ route('dashboard.bookings.history.export') }}",

            viewUrl: "{{ route('dashboard.bookings.show', ':id') }}"

        };
    </script>

    <script src="{{ asset('js/dashboard/booking/history/history.js') }}"></script>

@endpush