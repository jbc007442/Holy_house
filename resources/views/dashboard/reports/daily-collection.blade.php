@extends('dashboard.base')

@section('title', 'Daily Collection Report')

@section('content')

<div class="p-6">

    <!-- Header -->

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Daily Collection Report
            </h1>

            <p class="text-zinc-500 mt-1">
                View today's payment collection and transaction summary.
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

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input
                type="date"
                class="border rounded-lg px-3 py-2">

            <select
                class="border rounded-lg px-3 py-2">

                <option>All Payment Methods</option>
                <option>Cash</option>
                <option>UPI</option>
                <option>Credit Card</option>
                <option>Debit Card</option>
                <option>Bank Transfer</option>

            </select>

            <select
                class="border rounded-lg px-3 py-2">

                <option>All Status</option>
                <option>Paid</option>
                <option>Refunded</option>

            </select>

            <button
                class="bg-zinc-800 text-white rounded-lg">

                Generate Report

            </button>

        </div>

    </div>

    <!-- Summary Cards -->

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">Cash</p>

            <h2 class="text-2xl font-bold text-green-600 mt-2">
                $1,250.00
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">UPI</p>

            <h2 class="text-2xl font-bold text-blue-600 mt-2">
                $980.00
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">Card</p>

            <h2 class="text-2xl font-bold text-indigo-600 mt-2">
                $1,720.00
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">Bank Transfer</p>

            <h2 class="text-2xl font-bold text-amber-600 mt-2">
                $650.00
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">Total Collection</p>

            <h2 class="text-2xl font-bold text-violet-600 mt-2">
                $4,600.00
            </h2>

        </div>

    </div>

    <!-- Collection Table -->

    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="p-4 text-left">
                        Receipt No.
                    </th>

                    <th class="text-left">
                        Guest
                    </th>

                    <th class="text-left">
                        Invoice No.
                    </th>

                    <th class="text-left">
                        Payment Method
                    </th>

                    <th class="text-left">
                        Payment Time
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
                        RCPT-1001
                    </td>

                    <td>
                        John Smith
                    </td>

                    <td>
                        INV-1001
                    </td>

                    <td>
                        Cash
                    </td>

                    <td>
                        09:15 AM
                    </td>

                    <td class="text-right font-semibold">
                        $320.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                            Paid

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        RCPT-1002
                    </td>

                    <td>
                        Rahul Sharma
                    </td>

                    <td>
                        INV-1002
                    </td>

                    <td>
                        UPI
                    </td>

                    <td>
                        11:45 AM
                    </td>

                    <td class="text-right font-semibold">
                        $540.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                            Paid

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        RCPT-1003
                    </td>

                    <td>
                        Emily Johnson
                    </td>

                    <td>
                        INV-1003
                    </td>

                    <td>
                        Credit Card
                    </td>

                    <td>
                        03:20 PM
                    </td>

                    <td class="text-right font-semibold">
                        $780.00
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">

                            Refunded

                        </span>

                    </td>

                </tr>

            </tbody>

            <tfoot class="bg-zinc-50 border-t">

                <tr>

                    <td colspan="5" class="p-4 text-right font-bold">
                        Grand Total
                    </td>

                    <td class="text-right font-bold text-green-700">
                        $4,600.00
                    </td>

                    <td></td>

                </tr>

            </tfoot>

        </table>

    </div>

</div>

@endsection