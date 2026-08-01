@extends('dashboard.base')

@section('title', 'Add Room')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Add Room
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Create a new room for your property.
            </p>
        </div>

        <a href="{{ route('dashboard.property.rooms') }}"
            class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
            Back
        </a>

    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

        <form action="{{ route('dashboard.property.rooms.store') }}" method="POST">

            @csrf

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Building -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Building <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="building_id"
                        id="building_id"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        <option value="">Select Building</option>

                        @foreach($buildings as $building)
                            <option
                                value="{{ $building->id }}"
                                data-floors="{{ $building->floors }}"
                                {{ old('building_id') == $building->id ? 'selected' : '' }}>
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

                    <input
                        type="text"
                        name="room_number"
                        value="{{ old('room_number') }}"
                        placeholder="101"
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

                    <select
                        name="floor"
                        id="floor"
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

                    <input
                        type="number"
                        name="capacity"
                        value="{{ old('capacity') }}"
                        placeholder="2"
                        min="1"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('capacity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Base Price (Per Night) <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="base_price"
                        value="{{ old('base_price') }}"
                        placeholder="1500"
                        step="0.01"
                        min="0"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                    @error('base_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">

                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="running" {{ old('status') == 'running' ? 'selected' : '' }}>Running</option>
                        <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>

                    </select>

                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Room description..."
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>

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

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-medium transition">

                    Save Room

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>
$(function () {

    function loadFloors() {

        let floors = $('#building_id option:selected').data('floors');
        let selectedFloor = @json(old('floor'));

        $('#floor').html('<option value="">Select Floor</option>');

        if (!floors) {
            return;
        }

        $('#floor').append(
            `<option value="Ground Floor" ${selectedFloor === 'Ground Floor' ? 'selected' : ''}>
                Ground Floor
            </option>`
        );

        for (let i = 1; i <= floors; i++) {

            let floorName = i + ' Floor';

            $('#floor').append(
                `<option value="${floorName}" ${selectedFloor === floorName ? 'selected' : ''}>
                    ${floorName}
                </option>`
            );
        }
    }

    $('#building_id').on('change', loadFloors);

    loadFloors();

});
</script>
@endpush