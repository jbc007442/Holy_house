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

            <!-- Rooms -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Rooms
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
