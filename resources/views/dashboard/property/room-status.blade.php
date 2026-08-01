@extends('dashboard.base')

@section('title', 'Room Status')

@section('content')

    <div class="space-y-6">

        <!-- Header -->

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-bold text-zinc-800">
                    Room Status
                </h1>

                <p class="text-zinc-500 mt-1">
                    Monitor room availability across all buildings.
                </p>

            </div>

            <div class="flex flex-wrap gap-3">

                <select id="buildingFilter" class="px-4 py-2.5 rounded-xl border border-zinc-300 bg-white">

                    <option value="">
                        All Buildings
                    </option>

                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}">
                            {{ $building->name }}
                        </option>
                    @endforeach

                </select>

                <select id="floorFilter" class="px-4 py-2.5 rounded-xl border border-zinc-300 bg-white">

                    <option value="">
                        All Floors
                    </option>

                    @foreach ($floors as $floor)
                        <option value="{{ $floor }}">
                            {{ $floor }}
                        </option>
                    @endforeach

                </select>

                <input id="searchRoom" type="text" placeholder="Search Room..."
                    class="w-72 px-4 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">

            </div>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Available -->

            <div class="bg-white rounded-2xl border border-green-100 p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500 font-medium">
                            Available
                        </p>

                        <h2 id="availableRooms" class="text-4xl font-bold text-zinc-900 mt-3">

                            {{ $stats['available'] }}

                        </h2>

                        <p class="text-sm text-green-600 mt-2">
                            Ready to Assign
                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center">

                        <i class="fa-solid fa-circle-check text-3xl text-green-600"></i>

                    </div>

                </div>

            </div>

            <!-- Running -->

            <div class="bg-white rounded-2xl border border-blue-100 p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500 font-medium">
                            Running
                        </p>

                        <h2 id="runningRooms" class="text-4xl font-bold text-zinc-900 mt-3">

                            {{ $stats['running'] }}

                        </h2>

                        <p class="text-sm text-blue-600 mt-2">
                            Currently Occupied
                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center">

                        <i class="fa-solid fa-user-check text-3xl text-blue-600"></i>

                    </div>

                </div>

            </div>

            <!-- Blocked -->

            <div class="bg-white rounded-2xl border border-red-100 p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500 font-medium">
                            Blocked
                        </p>

                        <h2 id="blockedRooms" class="text-4xl font-bold text-zinc-900 mt-3">

                            {{ $stats['blocked'] }}

                        </h2>

                        <p class="text-sm text-red-600 mt-2">
                            Not Available
                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center">

                        <i class="fa-solid fa-ban text-3xl text-red-600"></i>

                    </div>

                </div>

            </div>

            <!-- Maintenance -->

            <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500 font-medium">
                            Maintenance
                        </p>

                        <h2 id="maintenanceRooms" class="text-4xl font-bold text-zinc-900 mt-3">

                            {{ $stats['maintenance'] }}

                        </h2>

                        <p class="text-sm text-zinc-600 mt-2">
                            Under Maintenance
                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-zinc-100 flex items-center justify-center">

                        <i class="fa-solid fa-screwdriver-wrench text-3xl text-zinc-600"></i>

                    </div>

                </div>

            </div>

        </div>
        <!-- Rooms -->

        <div id="roomContainer">

            <div class="bg-white rounded-2xl border border-zinc-200 py-16 text-center">

                <i class="fa-solid fa-spinner fa-spin text-5xl text-indigo-500 mb-4"></i>

                <h3 class="text-lg font-semibold text-zinc-700">
                    Loading Rooms...
                </h3>

                <p class="text-zinc-500 mt-2">
                    Please wait while we load room status.
                </p>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        window.roomStatusConfig = {

            url: "{{ route('dashboard.property.room-status') }}",

            viewUrl: "{{ route('dashboard.property.rooms.show', ':id') }}"

        };
    </script>

    <script src="{{ asset('js/dashboard/property/rooms/room-status.js') }}"></script>
@endpush
