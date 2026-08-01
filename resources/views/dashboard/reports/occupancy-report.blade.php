@extends('dashboard.base')

@section('title', 'Occupancy Report')

@section('content')

<div class="p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Occupancy Report
            </h1>

            <p class="text-zinc-500 mt-1">
                View room occupancy statistics and availability.
            </p>
        </div>

        <div class="flex gap-3">

            <button
                class="px-4 py-2 border rounded-lg hover:bg-zinc-50">
                <i class="fa-solid fa-file-pdf mr-2 text-red-600"></i>
                Export PDF
            </button>

            <button
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                <i class="fa-solid fa-file-excel mr-2"></i>
                Export Excel
            </button>

        </div>

    </div>

    <!-- Filters -->

    <div class="bg-white border rounded-xl p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <input
                type="date"
                class="border rounded-lg px-3 py-2">

            <input
                type="date"
                class="border rounded-lg px-3 py-2">

            <select class="border rounded-lg px-3 py-2">
                <option>All Buildings</option>
                <option>Building A</option>
                <option>Building B</option>
            </select>

            <select class="border rounded-lg px-3 py-2">
                <option>All Room Types</option>
                <option>Single</option>
                <option>Double</option>
                <option>Deluxe</option>
                <option>Suite</option>
            </select>

            <button
                class="bg-zinc-800 text-white rounded-lg">
                Generate Report
            </button>

        </div>

    </div>

    <!-- Summary Cards -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Total Rooms
            </p>

            <h2 class="text-3xl font-bold mt-2">
                120
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Occupied Rooms
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">
                92
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Vacant Rooms
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                28
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Occupancy Rate
            </p>

            <h2 class="text-3xl font-bold text-violet-600 mt-2">
                76.7%
            </h2>

        </div>

    </div>

    <!-- Table -->

    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="p-4 text-left">Room No.</th>
                    <th class="text-left">Building</th>
                    <th class="text-left">Room Type</th>
                    <th class="text-left">Guest</th>
                    <th class="text-left">Check In</th>
                    <th class="text-left">Check Out</th>
                    <th class="text-center">Status</th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="p-4">101</td>

                    <td>Main Building</td>

                    <td>Deluxe</td>

                    <td>John Smith</td>

                    <td>28 Jul 2026</td>

                    <td>30 Jul 2026</td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                            Occupied

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">102</td>

                    <td>Main Building</td>

                    <td>Single</td>

                    <td>-</td>

                    <td>-</td>

                    <td>-</td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">

                            Vacant

                        </span>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection