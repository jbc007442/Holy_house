@extends('dashboard.base')

@section('title', 'Buildings')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Buildings
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Manage all buildings of your property.
                </p>
            </div>

            <a href="{{ route('dashboard.property.buildings.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition">

                <i class="fa-solid fa-plus"></i>

                Add Building

            </a>

        </div>

        <!-- Stats -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">

                <p class="text-sm text-zinc-500">
                    Total Buildings
                </p>

                <h2 id="totalBuildings" class="text-3xl font-bold mt-2">
                    0
                </h2>

            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">

                <p class="text-sm text-zinc-500">
                    Active
                </p>

                <h2 id="activeBuildings" class="text-3xl font-bold text-green-600 mt-2">
                    0
                </h2>

            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-5">

                <p class="text-sm text-zinc-500">
                    Total Rooms
                </p>

                <h2 id="totalRooms" class="text-3xl font-bold text-blue-600 mt-2">
                    0
                </h2>

            </div>

        </div>

        <!-- Table -->

        <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 border-b">

                <h2 class="text-lg font-semibold">
                    Building List
                </h2>

                <input type="text" id="searchBuilding" placeholder="Search building..."
                    class="w-full md:w-72 px-4 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:ring-2 focus:ring-amber-400">

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-5 py-3 text-left font-semibold">
                                Building
                            </th>

                            <th class="px-5 py-3 text-left font-semibold">
                                Code
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Rooms
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Status
                            </th>

                            <th class="px-5 py-3 text-right font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="buildingTable">

                        <tr>

                            <td colspan="5" class="text-center py-10 text-zinc-500">

                                <i class="fa-solid fa-spinner fa-spin text-xl mb-3 block"></i>

                                Loading buildings...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        window.buildingConfig = {
            indexUrl: "{{ route('dashboard.property.buildings') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('js/dashboard/property/building/building.js') }}"></script>
@endpush
