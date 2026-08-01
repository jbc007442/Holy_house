@extends('dashboard.base')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    <!-- Header -->

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Dashboard
            </h1>

            <p class="text-zinc-500 mt-1">
                Welcome back! Here's an overview of your hotel.
            </p>

        </div>

        <div class="text-sm text-zinc-500">
            {{ now()->format('d M Y') }}
        </div>

    </div>

    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">Buildings</p>

            <h2 id="totalBuildings"
                class="text-4xl font-bold mt-2">

                0

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">Rooms</p>

            <h2 id="totalRooms"
                class="text-4xl font-bold mt-2">

                0

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">Bookings</p>

            <h2 id="totalBookings"
                class="text-4xl font-bold mt-2 text-blue-600">

                0

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">Inventory Items</p>

            <h2 id="totalItems"
                class="text-4xl font-bold mt-2 text-emerald-600">

                0

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">Current Stock</p>

            <h2 id="currentStock"
                class="text-4xl font-bold mt-2 text-orange-600">

                0

            </h2>

        </div>

    </div>

    <!-- Second Row -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">
                Purchase Amount
            </p>

            <h2 id="purchaseAmount"
                class="text-3xl font-bold mt-2">

                ₹0.00

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">
                Users
            </p>

            <h2 id="totalUsers"
                class="text-3xl font-bold mt-2">

                0

            </h2>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <p class="text-zinc-500 text-sm">
                Stock Movements
            </p>

            <h2 id="stockMovements"
                class="text-3xl font-bold mt-2">

                0

            </h2>

        </div>

    </div>

    <!-- Charts -->

    <div class="grid lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl border p-6">

            <h2 class="text-lg font-semibold mb-5">
                Booking Overview
            </h2>

            <canvas id="bookingChart" height="120"></canvas>

        </div>

        <div class="bg-white rounded-2xl border p-6">

            <h2 class="text-lg font-semibold mb-5">
                Inventory Overview
            </h2>

            <canvas id="inventoryChart" height="120"></canvas>

        </div>

    </div>

    <!-- Tables -->

    <div class="grid lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl border overflow-hidden">

            <div class="px-6 py-4 border-b">

                <h2 class="font-semibold">

                    Recent Bookings

                </h2>

            </div>

            <table class="w-full">

                <thead class="bg-zinc-50">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            Booking
                        </th>

                        <th class="px-5 py-3 text-left">
                            Customer
                        </th>

                        <th class="px-5 py-3 text-center">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody id="recentBookings">

                </tbody>

            </table>

        </div>

        <div class="bg-white rounded-2xl border overflow-hidden">

            <div class="px-6 py-4 border-b">

                <h2 class="font-semibold">

                    Low Stock Items

                </h2>

            </div>

            <table class="w-full">

                <thead class="bg-zinc-50">

                    <tr>

                        <th class="px-5 py-3 text-left">

                            Item

                        </th>

                        <th class="px-5 py-3 text-center">

                            Stock

                        </th>

                    </tr>

                </thead>

                <tbody id="lowStockTable">

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

window.dashboard = {

    ajaxUrl: "{{ route('dashboard.data') }}"

};

</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('js/dashboard/dashboard.js') }}"></script>

@endpush