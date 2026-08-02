@extends('dashboard.base')

@section('title', 'Rooms')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Rooms
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Manage all rooms across your buildings.
                </p>
            </div>

            <a href="{{ route('dashboard.property.rooms.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition">

                <i class="fa-solid fa-plus"></i>

                Add Room

            </a>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">

            <!-- Total Rooms -->
            <div class="bg-white border border-zinc-200 rounded-2xl p-5 flex items-center justify-between">

                <div>
                    <p class="text-sm text-zinc-500">
                        Total Rooms
                    </p>

                    <h2 id="totalRooms" class="text-3xl font-bold text-zinc-800 mt-2">
                        0
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-door-open text-2xl"></i>
                </div>

            </div>

            <!-- Available -->
            <div class="bg-white border border-zinc-200 rounded-2xl p-5 flex items-center justify-between">

                <div>
                    <p class="text-sm text-zinc-500">
                        Available
                    </p>

                    <h2 id="availableRooms" class="text-3xl font-bold text-green-600 mt-2">
                        0
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                </div>

            </div>

            <!-- Running -->
            <div class="bg-white border border-zinc-200 rounded-2xl p-5 flex items-center justify-between">

                <div>
                    <p class="text-sm text-zinc-500">
                        Running
                    </p>

                    <h2 id="runningRooms" class="text-3xl font-bold text-blue-600 mt-2">
                        0
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-bed text-2xl"></i>
                </div>

            </div>

            <!-- Blocked -->
            <div class="bg-white border border-zinc-200 rounded-2xl p-5 flex items-center justify-between">

                <div>
                    <p class="text-sm text-zinc-500">
                        Blocked
                    </p>

                    <h2 id="blockedRooms" class="text-3xl font-bold text-red-600 mt-2">
                        0
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-ban text-2xl"></i>
                </div>

            </div>

            <!-- Maintenance -->
            <div class="bg-white border border-zinc-200 rounded-2xl p-5 flex items-center justify-between">

                <div>
                    <p class="text-sm text-zinc-500">
                        Maintenance
                    </p>

                    <h2 id="maintenanceRooms" class="text-3xl font-bold text-yellow-600 mt-2">
                        0
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
                </div>

            </div>

        </div>

        <!-- Table -->

        <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

            <div class="flex items-center justify-between p-5 border-b">

                <h2 class="font-semibold">
                    Room List
                </h2>

                <div class="flex gap-3">

                    <!-- Building Filter -->

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

                    <!-- Status Filter -->

                    <select id="statusFilter" class="px-4 py-2 rounded-xl border border-zinc-300">

                        <option value="">
                            All Status
                        </option>

                        <option value="available">
                            Available
                        </option>

                        <option value="running">
                            Running
                        </option>

                        <option value="blocked">
                            Blocked
                        </option>

                        <option value="maintenance">
                            Maintenance
                        </option>

                    </select>

                    <!-- Search -->

                    <input id="searchRoom" type="text" placeholder="Search Room..."
                        class="w-72 px-4 py-2 rounded-xl border border-zinc-300 focus:outline-none focus:ring-2 focus:ring-amber-400">

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="text-left px-5 py-3 font-semibold">
                                Building
                            </th>

                            <th class="text-left px-5 py-3 font-semibold">
                                Room No
                            </th>

                            <th class="text-left px-5 py-3 font-semibold">
                                Floor
                            </th>

                            <th class="text-left px-5 py-3 font-semibold">
                                Capacity
                            </th>

                            <th class="text-left px-5 py-3 font-semibold">
                                Status
                            </th>

                            <th class="text-right px-5 py-3 font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="roomTableBody">

                        <tr>

                            <td colspan="7" class="text-center py-10 text-zinc-500">

                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                                Loading rooms...

                            </td>

                        </tr>

                    </tbody>

                </table>
                <div id="paginationWrapper" class="border-t border-zinc-200 px-5 py-4"></div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        window.roomConfig = {

            indexUrl: "{{ route('dashboard.property.rooms') }}",

            viewUrl: "{{ route('dashboard.property.rooms.show', ':id') }}",

            editUrl: "{{ route('dashboard.property.rooms.edit', ':id') }}",

            destroyUrl: "{{ route('dashboard.property.rooms.destroy', ':id') }}",

            csrf: "{{ csrf_token() }}"

        };
    </script>

    <script src="{{ asset('js/dashboard/property/rooms/room.js') }}"></script>
@endpush
