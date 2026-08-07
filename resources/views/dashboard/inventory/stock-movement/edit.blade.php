@extends('dashboard.base')

@section('title', 'Edit Stock Movement')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Edit Stock Movement
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Update stock movement details.
                </p>
            </div>

            <a href="{{ route('dashboard.inventory.stock-movement') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                Back
            </a>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

            <form action="{{ route('dashboard.inventory.stock-movement.update', $stockMovement->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Item -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Item <span class="text-red-500">*</span>
                        </label>

                        <select id="item_id" name="item_id" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            <option value="">Select Item</option>

                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->current_stock }}"
                                    {{ old('item_id', $stockMovement->item_id) == $item->id ? 'selected' : '' }}>

                                    {{ $item->item_name }}

                                </option>
                            @endforeach

                        </select>

                        @error('item_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 flex items-center gap-2">

                            <span class="text-sm text-zinc-500">
                                Available Stock:
                            </span>

                            <span id="currentStock"
                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                -
                            </span>

                        </div>

                    </div>

                    <!-- Movement Type -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Movement Type <span class="text-red-500">*</span>
                        </label>

                        <select name="type" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            <option value="out" {{ old('type', $stockMovement->type) == 'out' ? 'selected' : '' }}>
                                Stock Out
                            </option>

                            <option value="adjustment"
                                {{ old('type', $stockMovement->type) == 'adjustment' ? 'selected' : '' }}>
                                Adjustment
                            </option>

                        </select>

                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Quantity -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>

                        <input id="quantity" type="number" min="1" name="quantity"
                            value="{{ old('quantity', $stockMovement->quantity) }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Building -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Building
                        </label>

                        <select id="building_id" name="building_id"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            <option value="">Select Building</option>

                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}"
                                    {{ old('building_id', $stockMovement->building_id) == $building->id ? 'selected' : '' }}>

                                    {{ $building->name }}

                                </option>
                            @endforeach

                        </select>

                        @error('building_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Floor -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Floor
                        </label>

                        <select id="building_floor_id" name="building_floor_id"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            <option value="">
                                Select Floor
                            </option>

                        </select>

                        @error('building_floor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Room -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Room
                        </label>

                        <select id="room_id" name="room_id" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            <option value="">
                                Select Room
                            </option>

                        </select>

                        @error('room_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Kitchen -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Kitchen
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-zinc-300 px-4 py-3 cursor-pointer">

                            <input type="checkbox" id="kitchen" name="kitchen" value="1"
                                {{ old('kitchen', $stockMovement->kitchen) ? 'checked' : '' }}
                                class="rounded border-zinc-300">

                            <span class="text-sm text-zinc-700">
                                Issue to Kitchen
                            </span>

                        </label>

                        @error('kitchen')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Other Property -->
                    <div>

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Other Property
                        </label>

                        <input type="text" id="other_property" name="other_property"
                            value="{{ old('other_property', $stockMovement->other_property) }}"
                            placeholder="Enter Other Property" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                        @error('other_property')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Remarks
                        </label>

                        <textarea rows="4" name="remarks" class="w-full rounded-xl border border-zinc-300 px-4 py-3">{{ old('remarks', $stockMovement->remarks) }}</textarea>

                        @error('remarks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                <!-- Footer -->
                <div class="border-t border-zinc-200 px-8 py-5 flex justify-end gap-3">

                    <a href="{{ route('dashboard.inventory.stock-movement') }}"
                        class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                        Cancel

                    </a>

                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">

                        Update Movement

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        const originalQty = {{ $stockMovement->quantity }};

        let selectedBuilding = "{{ old('building_id', $stockMovement->building_id) }}";
        let selectedFloor = "{{ old('building_floor_id', $stockMovement->building_floor_id) }}";
        let selectedRoom = "{{ old('room_id', $stockMovement->room_id) }}";

        const buildingFloorsUrl = "{{ route('dashboard.property.buildings.get-floors', ':id') }}";
        const roomsUrl = "{{ route('dashboard.inventory.stock-movement.rooms', ':id') }}";
    </script>

    <script src="{{ asset('js/dashboard/inventory/stock-movement/edit-stock-movement.js') }}?v={{ time() }}">
    </script>
@endpush
