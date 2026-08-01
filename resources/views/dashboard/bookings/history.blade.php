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

        <div class="flex gap-3">

            <button
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100">

                <i class="fa-solid fa-file-pdf mr-2"></i>

                Export PDF

            </button>

            <button
                class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white">

                <i class="fa-solid fa-file-excel mr-2"></i>

                Export Excel

            </button>

        </div>

    </div>

    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

            <div>

                <p class="text-sm text-zinc-500">
                    Completed
                </p>

                <h2 id="completedCount"
                    class="text-3xl font-bold mt-2">

                    0

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">

                <i class="fa-solid fa-circle-check text-2xl"></i>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

            <div>

                <p class="text-sm text-zinc-500">
                    Revenue
                </p>

                <h2 id="revenueAmount"
                    class="text-3xl font-bold text-green-600 mt-2">

                    ₹0

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">

                <i class="fa-solid fa-wallet text-2xl"></i>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

            <div>

                <p class="text-sm text-zinc-500">
                    Average Stay
                </p>

                <h2 id="averageStay"
                    class="text-3xl font-bold text-blue-600 mt-2">

                    0 Days

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                <i class="fa-solid fa-bed text-2xl"></i>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

            <div>

                <p class="text-sm text-zinc-500">
                    Checked Out Today
                </p>

                <h2 id="todayCheckout"
                    class="text-3xl font-bold text-amber-600 mt-2">

                    0

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">

                <i class="fa-solid fa-right-from-bracket text-2xl"></i>

            </div>

        </div>

    </div>

    <!-- Filters -->

    <div class="bg-white rounded-2xl border border-zinc-200 p-5">

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

            <input
                id="searchHistory"
                type="text"
                placeholder="Booking / Guest / Mobile"
                class="rounded-xl border border-zinc-300 px-4 py-3">

            <input
                id="fromDate"
                type="date"
                class="rounded-xl border border-zinc-300 px-4 py-3">

            <input
                id="toDate"
                type="date"
                class="rounded-xl border border-zinc-300 px-4 py-3">

            <select
                id="buildingFilter"
                class="rounded-xl border border-zinc-300 px-4 py-3">

                <option value="">
                    All Buildings
                </option>

                @foreach($buildings as $building)

                    <option value="{{ $building->id }}">
                        {{ $building->name }}
                    </option>

                @endforeach

            </select>

            <button
                id="searchBtn"
                class="rounded-xl bg-amber-500 hover:bg-amber-600 text-white">

                <i class="fa-solid fa-magnifying-glass mr-2"></i>

                Search

            </button>

            <button
                id="resetBtn"
                class="rounded-xl border border-zinc-300 hover:bg-zinc-100">

                Reset

            </button>

        </div>

    </div>

    <!-- Table -->

    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

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

                        <th class="px-5 py-4 text-left">
                            Total
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

                        <td colspan="9"
                            class="py-12 text-center text-zinc-500">

                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                            Loading booking history...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <div id="paginationContainer"
             class="border-t bg-zinc-50 px-5 py-4">

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

window.bookingHistoryConfig = {

    ajaxUrl: "{{ route('dashboard.bookings.history.ajax') }}",

    viewUrl: "{{ route('dashboard.bookings.show', ':id') }}"

};

</script>

<script src="{{ asset('js/dashboard/booking/history/history.js') }}"></script>

@endpush