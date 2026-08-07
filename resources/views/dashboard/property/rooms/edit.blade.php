@extends('dashboard.base')

@section('title', 'Edit Room')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Edit Room
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Update room information.
                </p>
            </div>

            <a href="{{ route('dashboard.property.rooms') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                Back
            </a>

        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

            <form action="{{ route('dashboard.property.rooms.update', $room->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Building -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Building <span class="text-red-500">*</span>
                        </label>

                        <select name="building_id" id="building_id"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                            <option value="">Select Building</option>

                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}"
                                    {{ old('building_id', $room->building_id) == $building->id ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('building_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Room Number <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        @error('room_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Floor -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Floor <span class="text-red-500">*</span>
                        </label>

                        <select name="floor" id="floor"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                            <option value="">Select Floor</option>

                        </select>

                        @error('floor')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Capacity <span class="text-red-500">*</span>
                        </label>

                        <input type="number" name="capacity" min="1" value="{{ old('capacity', $room->capacity) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        @error('capacity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Base Price -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Base Price (Rent Per Night) <span class="text-red-500">*</span>
                        </label>

                        <input type="number" name="base_price" step="0.01" min="0"
                            value="{{ old('base_price', $room->base_price) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        @error('base_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Status
                        </label>

                        <select disabled
                            class="w-full rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-3 cursor-not-allowed">

                            <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>
                                Available
                            </option>

                            <option value="running" {{ $room->status == 'running' ? 'selected' : '' }}>
                                Running
                            </option>

                            <option value="blocked" {{ $room->status == 'blocked' ? 'selected' : '' }}>
                                Blocked
                            </option>

                            <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>

                        </select>

                        <input type="hidden" name="status" value="{{ $room->status }}">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Description
                        </label>

                        <textarea name="description" rows="4"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">{{ old('description', $room->description) }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Footer -->
                <div class="border-t border-zinc-200 px-8 py-5 flex justify-end gap-3">

                    <a href="{{ route('dashboard.property.rooms') }}"
                        class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium transition">

                        Update Room

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(function() {

            let selectedFloor = @json(old('floor', $room->floor));

            function loadFloors() {

                let buildingId = $('#building_id').val();

                $('#floor').html('<option value="">Select Floor</option>');

                if (!buildingId) {
                    return;
                }

                $.ajax({
                    url: "{{ url('/dashboard/buildings') }}/" + buildingId + "/floors",
                    type: "GET",
                    success: function(floors) {

                        $.each(floors, function(index, floor) {

                            $('#floor').append(
                                `<option value="${floor.name}" ${selectedFloor == floor.name ? 'selected' : ''}>
                            ${floor.name}
                        </option>`
                            );

                        });

                    }
                });
            }

            $('#building_id').on('change', function() {

                selectedFloor = null;

                loadFloors();

            });

            loadFloors();

        });
    </script>
@endpush
