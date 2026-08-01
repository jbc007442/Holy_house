@extends('dashboard.base')

@section('title', 'Current Stays')

@section('content')

    <div class="space-y-6">

        <!-- Header -->

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-3xl font-bold text-zinc-800">
                    Current Stays
                </h1>

                <p class="text-zinc-500 mt-1">
                    Guests currently checked in.
                </p>

            </div>

            <a href="{{ route('dashboard.bookings.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl">

                <i class="fa-solid fa-plus"></i>

                New Booking

            </a>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

                <div>

                    <p class="text-sm text-zinc-500">
                        Guests In House
                    </p>

                    <h2 id="guestCount" class="text-3xl font-bold mt-2">
                        0
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                    <i class="fa-solid fa-users text-2xl"></i>

                </div>

            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

                <div>

                    <p class="text-sm text-zinc-500">
                        Running Rooms
                    </p>

                    <h2 id="runningRooms" class="text-3xl font-bold text-blue-600 mt-2">
                        0
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">

                    <i class="fa-solid fa-bed text-2xl"></i>

                </div>

            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

                <div>

                    <p class="text-sm text-zinc-500">
                        Check Out Today
                    </p>

                    <h2 id="checkoutToday" class="text-3xl font-bold text-red-600 mt-2">
                        0
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">

                    <i class="fa-solid fa-right-from-bracket text-2xl"></i>

                </div>

            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-6 flex items-center justify-between">

                <div>

                    <p class="text-sm text-zinc-500">
                        Total Balance
                    </p>

                    <h2 id="totalBalance" class="text-3xl font-bold text-amber-600 mt-2">
                        ₹0
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">

                    <i class="fa-solid fa-wallet text-2xl"></i>

                </div>

            </div>

        </div>

        <!-- Table -->

        <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

            <!-- Filters -->

            <div class="flex flex-wrap gap-3 items-center justify-between p-5 border-b">

                <div class="flex flex-wrap gap-3">

                    <input id="searchBooking" type="text" placeholder="Search Booking / Guest / Mobile..."
                        class="w-80 px-4 py-2 rounded-xl border border-zinc-300">

                    <select id="buildingFilter" class="px-4 py-2 rounded-xl border border-zinc-300">

                        <option value="">
                            All Buildings
                        </option>

                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}">
                                {{ $building->name }}
                            </option>
                        @endforeach

                    </select>

                    <select id="floorFilter" class="px-4 py-2 rounded-xl border border-zinc-300">

                        <option value="">
                            All Floors
                        </option>

                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">
                                Floor {{ $i }}
                            </option>
                        @endfor

                    </select>

                </div>

            </div>

            <!-- Table -->

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
                                Mobile
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
                                Balance
                            </th>

                            <th class="px-5 py-4 text-center">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="currentStayTable">

                        <tr>

                            <td colspan="9" class="py-12 text-center text-zinc-500">

                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                                Loading current stays...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->

            <div id="paginationContainer" class="border-t px-5 py-4 bg-zinc-50">

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        window.currentStayConfig = {

            ajaxUrl: "{{ route('dashboard.bookings.current-stays.ajax') }}",

            viewUrl: "{{ route('dashboard.bookings.show', ':id') }}",

            editUrl: "{{ route('dashboard.bookings.edit', ':id') }}",

            checkoutUrl: "{{ route('dashboard.bookings.checkout', ':id') }}",

            serviceUrl: "{{ route('dashboard.bookings.services', ':id') }}",

            csrf: "{{ csrf_token() }}"

        };
    </script>

    <script src="{{ asset('js/dashboard/booking/current-stays/current-stays.js') }}"></script>
@endpush
