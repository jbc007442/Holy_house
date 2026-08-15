@extends('dashboard.base')

@section('title', 'Dashboard')

@section('content')

    <div class="space-y-6">

        <!-- Header -->

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-zinc-800">
                    Dashboard
                </h1>

                <p class="mt-1 text-zinc-500">
                    Welcome back! Here's an overview of your hotel.
                </p>

            </div>

            <div class="flex items-center gap-3">

                <div class="relative">

                    <i class="fa-solid fa-building absolute left-3 top-1/3  text-zinc-400"></i>

                    <select id="revenueBuilding"
                        class="w-56 rounded-xl border border-zinc-300 bg-white py-2.5 pl-10 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

                        <option value="">
                            All Buildings
                        </option>

                    </select>

                </div>

                <div class="rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-500 shadow-sm">

                    <i class="fa-solid fa-calendar-days mr-2 text-blue-500"></i>

                    {{ now()->format('d M Y') }}

                </div>

            </div>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Buildings -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Buildings
                        </p>

                        <h2 id="totalBuildings" class="mt-2 text-3xl font-bold text-zinc-900">
                            0
                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-building text-2xl"></i>
                    </div>

                </div>

            </div>

            <!-- Revenue -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Total Revenue
                        </p>

                        <h2 id="totalRevenue" class="mt-2 text-3xl font-bold text-zinc-900">
                            ₹0.00
                        </h2>

                        <p id="revenueLabel" class="mt-1 text-xs text-zinc-400">
                            All Buildings
                        </p>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-indian-rupee-sign text-2xl"></i>
                    </div>

                </div>

            </div>

            <!-- Rooms -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Total Rooms
                        </p>

                        <h2 id="totalRooms" class="mt-2 text-3xl font-bold text-zinc-900">
                            0
                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center">
                        <i class="fa-solid fa-bed text-2xl"></i>
                    </div>

                </div>

            </div>

            <!-- Bookings -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Current Guests
                        </p>

                        <h2 id="totalBookings" class="mt-2 text-3xl font-bold text-zinc-900">
                            0
                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-check text-2xl"></i>
                    </div>

                </div>

            </div>

            <!-- Users -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Users
                        </p>

                        <h2 id="totalUsers" class="mt-2 text-3xl font-bold text-zinc-900">
                            0
                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>

                </div>

            </div>

            <!-- Available rooms -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Available Rooms
                        </p>

                        <h2 id="availableRooms" class="mt-2 text-3xl font-bold text-green-600">

                            0

                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">

                        <i class="fa-solid fa-door-open text-2xl"></i>

                    </div>

                </div>

            </div>

            <!-- Running rooms -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Running Rooms
                        </p>

                        <h2 id="runningRooms" class="mt-2 text-3xl font-bold text-red-600">

                            0

                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">

                        <i class="fa-solid fa-bed text-2xl"></i>

                    </div>

                </div>

            </div>

            <!-- Check-out -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Today's Check-Out
                        </p>

                        <h2 id="todayCheckout" class="mt-2 text-3xl font-bold text-orange-600">

                            0

                        </h2>

                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">

                        <i class="fa-solid fa-right-from-bracket text-2xl"></i>

                    </div>

                </div>

            </div>

        </div>
        <!-- Login History  -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-zinc-200">

                <h2 class="text-lg font-semibold text-zinc-800">
                    Recent Login History
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-6 py-4 text-left">
                                User
                            </th>

                            <th class="px-6 py-4 text-left">
                                Login Time
                            </th>

                            <th class="px-6 py-4 text-left">
                                Logout Time
                            </th>

                            <th class="px-6 py-4 text-center">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody id="loginHistoryTable">

                        <tr>

                            <td colspan="7" class="px-6 py-8 text-center text-zinc-500">

                                Loading...

                            </td>

                        </tr>

                    </tbody>

                </table>

                <div id="paginationWrapper" class="border-t border-zinc-200 px-6 py-4">
                </div>

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
