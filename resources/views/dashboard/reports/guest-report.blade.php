@extends('dashboard.base')

@section('title', 'Guest Report')

@section('content')

<div class="p-6">

    <!-- Header -->

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Guest Report
            </h1>

            <p class="text-zinc-500 mt-1">
                View guest statistics, check-in history and demographics.
            </p>

        </div>

        <div class="flex gap-3">

            <button
                class="px-4 py-2 border rounded-lg hover:bg-zinc-50">

                <i class="fa-solid fa-file-pdf text-red-600 mr-2"></i>
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

                <option>All Guest Types</option>
                <option>Walk In</option>
                <option>Online</option>
                <option>Corporate</option>
                <option>Travel Agent</option>

            </select>

            <select class="border rounded-lg px-3 py-2">

                <option>All Nationalities</option>
                <option>Indian</option>
                <option>Foreign</option>

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
                Total Guests
            </p>

            <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                1,280
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Check-ins
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">
                845
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Check-outs
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                810
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Repeat Guests
            </p>

            <h2 class="text-3xl font-bold text-amber-600 mt-2">
                215
            </h2>

        </div>

    </div>

    <!-- Table -->

    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="p-4 text-left">Guest Name</th>
                    <th class="text-left">Mobile</th>
                    <th class="text-left">Nationality</th>
                    <th class="text-left">Room</th>
                    <th class="text-left">Check In</th>
                    <th class="text-left">Check Out</th>
                    <th class="text-center">Status</th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="p-4">
                        John Smith
                    </td>

                    <td>
                        +1 555-123-4567
                    </td>

                    <td>
                        USA
                    </td>

                    <td>
                        201
                    </td>

                    <td>
                        25 Jul 2026
                    </td>

                    <td>
                        28 Jul 2026
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                            Checked Out
                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        Rahul Sharma
                    </td>

                    <td>
                        +91 9876543210
                    </td>

                    <td>
                        India
                    </td>

                    <td>
                        305
                    </td>

                    <td>
                        27 Jul 2026
                    </td>

                    <td>
                        30 Jul 2026
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                            Staying
                        </span>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection