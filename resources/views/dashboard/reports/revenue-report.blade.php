@extends('dashboard.base')

@section('title', 'Revenue Report')

@section('content')

<div class="p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Revenue Report
            </h1>

            <p class="text-zinc-500 mt-1">
                Analyze revenue generated from bookings and payments.
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

                <option>All Payment Methods</option>
                <option>Cash</option>
                <option>UPI</option>
                <option>Credit Card</option>
                <option>Debit Card</option>
                <option>Bank Transfer</option>

            </select>

            <select class="border rounded-lg px-3 py-2">

                <option>All Status</option>
                <option>Paid</option>
                <option>Partial</option>
                <option>Pending</option>

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
                Total Revenue
            </p>

            <h2 class="text-3xl font-bold text-emerald-600 mt-2">
                $245,800
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Paid Amount
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                $228,500
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Pending Amount
            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-2">
                $17,300
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Total Bookings
            </p>

            <h2 class="text-3xl font-bold text-violet-600 mt-2">
                312
            </h2>

        </div>

    </div>

    <!-- Revenue Table -->

    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="p-4 text-left">
                        Invoice No.
                    </th>

                    <th class="text-left">
                        Guest
                    </th>

                    <th class="text-left">
                        Check In
                    </th>

                    <th class="text-left">
                        Check Out
                    </th>

                    <th class="text-left">
                        Payment Method
                    </th>

                    <th class="text-right">
                        Amount
                    </th>

                    <th class="text-center">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="p-4">
                        INV-1001
                    </td>

                    <td>
                        John Smith
                    </td>

                    <td>
                        25 Jul 2026
                    </td>

                    <td>
                        28 Jul 2026
                    </td>

                    <td>
                        UPI
                    </td>

                    <td class="text-right font-semibold">
                        $450.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                            Paid

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        INV-1002
                    </td>

                    <td>
                        Emily Johnson
                    </td>

                    <td>
                        26 Jul 2026
                    </td>

                    <td>
                        29 Jul 2026
                    </td>

                    <td>
                        Cash
                    </td>

                    <td class="text-right font-semibold">
                        $320.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">

                            Partial

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        INV-1003
                    </td>

                    <td>
                        Michael Brown
                    </td>

                    <td>
                        27 Jul 2026
                    </td>

                    <td>
                        30 Jul 2026
                    </td>

                    <td>
                        Bank Transfer
                    </td>

                    <td class="text-right font-semibold">
                        $680.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">

                            Pending

                        </span>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection