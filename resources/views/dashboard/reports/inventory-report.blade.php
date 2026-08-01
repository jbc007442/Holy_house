@extends('dashboard.base')

@section('title', 'Inventory Report')

@section('content')

<div class="p-6">

    <!-- Header -->

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Inventory Report
            </h1>

            <p class="text-zinc-500 mt-1">
                Monitor inventory levels, stock movement and low stock items.
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
                type="text"
                placeholder="Search Item..."
                class="border rounded-lg px-3 py-2">

            <select
                class="border rounded-lg px-3 py-2">

                <option>All Categories</option>
                <option>Housekeeping</option>
                <option>Kitchen</option>
                <option>Laundry</option>
                <option>Maintenance</option>
                <option>Office</option>

            </select>

            <select
                class="border rounded-lg px-3 py-2">

                <option>All Status</option>
                <option>In Stock</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>

            </select>

            <input
                type="date"
                class="border rounded-lg px-3 py-2">

            <button
                class="bg-zinc-800 text-white rounded-lg">

                Generate Report

            </button>

        </div>

    </div>

    <!-- Summary -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Total Items
            </p>

            <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                245
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                In Stock
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">
                218
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Low Stock
            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                19
            </h2>

        </div>

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-zinc-500">
                Out of Stock
            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-2">
                8
            </h2>

        </div>

    </div>

    <!-- Inventory Table -->

    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="p-4 text-left">
                        Item
                    </th>

                    <th class="text-left">
                        Category
                    </th>

                    <th class="text-right">
                        Opening
                    </th>

                    <th class="text-right">
                        Stock In
                    </th>

                    <th class="text-right">
                        Stock Out
                    </th>

                    <th class="text-right">
                        Available
                    </th>

                    <th class="text-center">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="p-4">
                        Bath Towel
                    </td>

                    <td>
                        Housekeeping
                    </td>

                    <td class="text-right">
                        120
                    </td>

                    <td class="text-right text-green-600">
                        +30
                    </td>

                    <td class="text-right text-red-600">
                        -18
                    </td>

                    <td class="text-right font-semibold">
                        132
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                            In Stock

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        Shampoo Bottle
                    </td>

                    <td>
                        Housekeeping
                    </td>

                    <td class="text-right">
                        60
                    </td>

                    <td class="text-right text-green-600">
                        +10
                    </td>

                    <td class="text-right text-red-600">
                        -62
                    </td>

                    <td class="text-right font-semibold">
                        8
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">

                            Low Stock

                        </span>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="p-4">
                        Mineral Water
                    </td>

                    <td>
                        Kitchen
                    </td>

                    <td class="text-right">
                        100
                    </td>

                    <td class="text-right text-green-600">
                        +20
                    </td>

                    <td class="text-right text-red-600">
                        -120
                    </td>

                    <td class="text-right font-semibold">
                        0
                    </td>

                    <td class="text-center">

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">

                            Out of Stock

                        </span>

                    </td>

                </tr>

            </tbody>

            <tfoot class="bg-zinc-50 border-t">

                <tr>

                    <td colspan="5" class="p-4 text-right font-bold">
                        Total Available Items
                    </td>

                    <td class="text-right font-bold text-indigo-600">
                        140
                    </td>

                    <td></td>

                </tr>

            </tfoot>

        </table>

    </div>

    <!-- Low Stock Alert -->

    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-5">

        <h2 class="text-lg font-semibold text-amber-700 mb-3">

            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            Low Stock Alert

        </h2>

        <ul class="space-y-2 text-sm text-zinc-700">

            <li>• Shampoo Bottle — Remaining: 8 Units</li>
            <li>• Hand Wash — Remaining: 5 Units</li>
            <li>• Laundry Detergent — Remaining: 3 Units</li>
            <li>• Tissue Roll — Remaining: 7 Units</li>

        </ul>

    </div>

</div>

@endsection