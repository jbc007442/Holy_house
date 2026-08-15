@extends('dashboard.base')

@section('title', 'Edit Booking')

@section('content')

    <div class="space-y-6">

        <!-- ========================================================= -->
        <!-- Header -->
        <!-- ========================================================= -->

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">

                        <i class="fa-solid fa-pen-to-square text-xl text-amber-600"></i>

                    </div>

                    <div>

                        <h1 class="text-3xl font-bold text-zinc-800">
                            Edit Booking
                        </h1>

                        <p class="text-zinc-500 mt-1">

                            Booking No :

                            <span class="font-semibold text-zinc-700">

                                {{ $booking->booking_no }}

                            </span>

                        </p>

                    </div>

                </div>

            </div>

            <div>

                <a href="{{ route('dashboard.bookings.current-stays') }}"
                    class="px-5 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                    <i class="fa-solid fa-arrow-left mr-2"></i>

                    Back

                </a>

            </div>

        </div>

        <!-- ========================================================= -->
        <!-- Form -->
        <!-- ========================================================= -->

        <form action="{{ route('dashboard.bookings.update', $booking->id) }}" method="POST">

            @csrf
            @method('PUT')

            <!-- ========================================================= -->
            <!-- Guest Information -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">

                        <i class="fa-solid fa-users text-blue-600"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-zinc-800">

                            Guest Details

                        </h2>

                        <p class="text-sm text-zinc-500">

                            Update guest information.

                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <div class="max-w-sm mb-8">

                        <label class="block mb-2 text-sm font-medium">

                            Total Guests

                        </label>

                        <select id="guestCount" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $booking->guest_count == $i ? 'selected' : '' }}>

                                    {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}

                                </option>
                            @endfor

                        </select>

                    </div>

                    <div id="guestContainer">

                        @foreach ($booking->guests as $index => $guest)
                            <div class="border rounded-2xl p-5 mb-5">

                                <h3 class="font-semibold text-zinc-800 mb-5">

                                    Guest {{ $loop->iteration }}

                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                                    <div>

                                        <label class="block mb-2 text-sm font-medium">

                                            Guest Name

                                        </label>

                                        <input type="text" name="guests[{{ $index }}][guest_name]"
                                            value="{{ $guest->guest_name }}"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                    </div>

                                    <div>

                                        <label class="block mb-2 text-sm font-medium">

                                            Mobile

                                        </label>

                                        <input type="text" name="guests[{{ $index }}][mobile]"
                                            value="{{ $guest->mobile }}"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                    </div>

                                    <div>

                                        <label class="block mb-2 text-sm font-medium">

                                            ID Type

                                        </label>

                                        <select name="guests[{{ $index }}][id_type]"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                            <option value="">Select ID</option>

                                            <option value="Aadhaar Card"
                                                {{ $guest->id_type == 'Aadhaar Card' ? 'selected' : '' }}>
                                                Aadhaar Card
                                            </option>

                                            <option value="Passport" {{ $guest->id_type == 'Passport' ? 'selected' : '' }}>
                                                Passport
                                            </option>

                                            <option value="Driving Licence"
                                                {{ $guest->id_type == 'Driving Licence' ? 'selected' : '' }}>
                                                Driving Licence
                                            </option>

                                            <option value="OCI" {{ $guest->id_type == 'OCI' ? 'selected' : '' }}>
                                                OCI
                                            </option>

                                            <option value="Voter ID" {{ $guest->id_type == 'Voter ID' ? 'selected' : '' }}>
                                                Voter ID
                                            </option>

                                        </select>

                                    </div>

                                    <div>

                                        <label class="block mb-2 text-sm font-medium">

                                            ID Number

                                        </label>

                                        <input type="text" name="guests[{{ $index }}][id_number]"
                                            value="{{ $guest->id_number }}"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium">
                                            Nationality
                                        </label>

                                        <input type="text" id="nationality_{{ $index }}"
                                            name="guests[{{ $index }}][nationality]"
                                            value="{{ $guest->nationality }}"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                                    </div>

                                    <!-- State -->
                                    <div id="state_wrapper_{{ $index }}">
                                        <label class="block mb-2 text-sm font-medium">
                                            State
                                        </label>

                                        <select name="guests[{{ $index }}][state]"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                            <option value="">Select State / UT</option>

                                            @php
                                                $indianStates = [
                                                    'Andhra Pradesh',
                                                    'Arunachal Pradesh',
                                                    'Assam',
                                                    'Bihar',
                                                    'Chhattisgarh',
                                                    'Goa',
                                                    'Gujarat',
                                                    'Haryana',
                                                    'Himachal Pradesh',
                                                    'Jharkhand',
                                                    'Karnataka',
                                                    'Kerala',
                                                    'Madhya Pradesh',
                                                    'Maharashtra',
                                                    'Manipur',
                                                    'Meghalaya',
                                                    'Mizoram',
                                                    'Nagaland',
                                                    'Odisha',
                                                    'Punjab',
                                                    'Rajasthan',
                                                    'Sikkim',
                                                    'Tamil Nadu',
                                                    'Telangana',
                                                    'Tripura',
                                                    'Uttar Pradesh',
                                                    'Uttarakhand',
                                                    'West Bengal',
                                                    'Andaman and Nicobar Islands',
                                                    'Chandigarh',
                                                    'Dadra and Nagar Haveli and Daman and Diu',
                                                    'Delhi',
                                                    'Jammu and Kashmir',
                                                    'Ladakh',
                                                    'Lakshadweep',
                                                    'Puducherry',
                                                ];
                                            @endphp

                                            @foreach ($indianStates as $state)
                                                <option value="{{ $state }}"
                                                    {{ $guest->state == $state ? 'selected' : '' }}>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- C Form -->
                                    <div id="cform_wrapper_{{ $index }}">
                                        <label class="block mb-2 text-sm font-medium">
                                            C Form
                                        </label>

                                        <input type="text" name="guests[{{ $index }}][c_form]"
                                            value="{{ $guest->c_form }}" placeholder="Enter C Form Number"
                                            class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- Room Information -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">

                        <i class="fa-solid fa-bed text-indigo-600"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold">

                            Room Information

                        </h2>

                        <p class="text-sm text-zinc-500">

                            Update room assignment.

                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Building

                            </label>

                            <select id="building_id" name="building_id"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3 cursor-not-allowed" disabled>

                                <option value="">Select Building</option>

                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        {{ $booking->room->building_id == $building->id ? 'selected' : '' }}>

                                        {{ $building->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <!-- Floor -->
                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Floor
                            </label>

                            <select id="floor" name="floor"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3 cursor-not-allowed" disabled>

                                <option value="">Select Floor</option>

                            </select>
                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Room

                            </label>

                            <select id="room_id" name="room_id"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="{{ $booking->room->id }}" data-rent="{{ $booking->room->base_price }}"
                                    data-status="{{ $booking->room->status }}" selected>

                                    {{ $booking->room->room_number }}

                                </option>

                            </select>

                        </div>

                    </div>
                    <!-- ========================================================= -->
                    <!-- Room Summary -->
                    <!-- ========================================================= -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                        <div class="rounded-xl border border-zinc-200 p-5 bg-zinc-50">

                            <p class="text-sm text-zinc-500">
                                Room Rent
                            </p>

                            <h3 id="roomRent" class="text-xl font-semibold mt-2">
                                ₹{{ number_format($booking->room_rent, 2) }}
                            </h3>

                        </div>

                        <div class="rounded-xl border border-zinc-200 p-5 bg-zinc-50">

                            <p class="text-sm text-zinc-500">
                                Room Status
                            </p>

                            @php

                                $badgeClass = match ($booking->room->status) {
                                    'available' => 'bg-green-100 text-green-700',

                                    'running' => 'bg-red-100 text-red-700',

                                    'maintenance' => 'bg-yellow-100 text-yellow-700',

                                    default => 'bg-zinc-100 text-zinc-700',
                                };

                            @endphp

                            <span id="roomStatus"
                                class="inline-flex mt-2 px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">

                                {{ ucfirst($booking->room->status) }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Payment -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">

                        <i class="fa-solid fa-wallet text-emerald-600"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-zinc-800">

                            Payment Information

                        </h2>

                        <p class="text-sm text-zinc-500">

                            Update payment information.

                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Room Rent

                            </label>

                            <input type="number" name="room_rent" id="room_rent" value="{{ $booking->room_rent }}"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Paid Amount

                            </label>

                            <input type="number" value="{{ number_format($booking->paid_amount, 2, '.', '') }}" readonly
                                class="w-full rounded-xl bg-zinc-100 border border-zinc-300 px-4 py-3">

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Balance

                            </label>

                            <input type="number" value="{{ number_format($booking->balance_amount, 2, '.', '') }}"
                                readonly
                                class="w-full rounded-xl bg-red-50 border border-red-300 px-4 py-3 text-red-700 font-semibold">

                        </div>

                    </div>

                    @if ($booking->payments->count())

                        <div class="mt-8">

                            <h3 class="font-semibold text-zinc-800 mb-4">

                                Payment History

                            </h3>

                            <div class="overflow-x-auto">

                                <table class="min-w-full text-sm">

                                    <thead class="bg-zinc-100">

                                        <tr>

                                            <th class="px-4 py-3 text-left">
                                                Date
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Amount
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Type
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Method
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Transaction
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($booking->payments as $payment)
                                            <tr class="border-t">

                                                <td class="px-4 py-3">

                                                    {{ $payment->paid_at?->format('d M Y h:i A') }}

                                                </td>

                                                <td class="px-4 py-3 font-medium">

                                                    ₹{{ number_format($payment->amount, 2) }}

                                                </td>

                                                <td class="px-4 py-3">

                                                    {{ ucfirst($payment->payment_type) }}

                                                </td>

                                                <td class="px-4 py-3">

                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}

                                                </td>

                                                <td class="px-4 py-3">

                                                    {{ $payment->transaction_no ?: '-' }}

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Remarks -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">

                        <i class="fa-solid fa-note-sticky text-orange-600"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold">

                            Remarks

                        </h2>

                        <p class="text-sm text-zinc-500">

                            Update internal remarks.

                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <textarea name="remarks" rows="5" class="w-full rounded-xl border border-zinc-300 px-4 py-3"
                        placeholder="Write remarks here...">{{ old('remarks', $booking->remarks) }}</textarea>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- Footer -->
            <!-- ========================================================= -->

            <div class="flex flex-wrap justify-between items-center gap-3 mt-8">

                <a href="{{ route('dashboard.bookings.current-stays') }}"
                    class="px-6 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                    <i class="fa-solid fa-arrow-left mr-2"></i>

                    Cancel

                </a>

                <div class="flex gap-3">

                    <button type="submit" class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>

                        Update Booking

                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        let selectedFloor = "{{ $booking->room->floor }}";
        let selectedRoom = "{{ $booking->room_id }}";
        $(function() {

            //-------------------------------------------------------
            // Floor Change
            //-------------------------------------------------------

            function loadFloors() {

                let buildingId = $('#building_id').val();

                $('#floor').html('<option value="">Loading...</option>');

                if (!buildingId) {
                    $('#floor').html('<option value="">Select Floor</option>');
                    return;
                }

                $.ajax({

                    url: "{{ route('dashboard.property.buildings.get-floors', ':id') }}"
                        .replace(':id', buildingId),

                    type: "GET",

                    dataType: "json",

                    success: function(floors) {

                        let html = '<option value="">Select Floor</option>';

                        $.each(floors, function(i, floor) {

                            html += `
                    <option value="${floor.name}"
                        ${selectedFloor == floor.name ? 'selected' : ''}>
                        ${floor.name}
                    </option>
                `;

                        });

                        $('#floor').html(html);

                        loadRooms();

                    }

                });

            }

            //-------------------------------------------------------
            // Building Change
            //-------------------------------------------------------

            function loadRooms() {

                let buildingId = $('#building_id').val();
                let floor = $('#floor').val();

                $('#room_id').html('<option>Loading...</option>');

                if (!buildingId || !floor) {

                    $('#room_id').html('<option value="">Select Room</option>');

                    return;

                }

                $.ajax({

                    url: "{{ route('dashboard.bookings.rooms', ':id') }}"
                        .replace(':id', buildingId),

                    type: "GET",

                    data: {
                        floor: floor,
                        selected_room: selectedRoom
                    },

                    dataType: "json",

                    success: function(rooms) {

                        let html = '<option value="">Select Room</option>';

                        $.each(rooms, function(i, room) {

                            html += `
                    <option
                        value="${room.id}"
                        data-rent="${room.base_price}"
                        data-status="${room.status}"
                        ${selectedRoom == room.id ? 'selected' : ''}>
                        ${room.room_number}
                    </option>
                `;

                        });

                        $('#room_id').html(html);
                        $('#room_id').trigger('change');

                    }

                });

            }

            //-------------------------------------------------------
            // Room Change
            //-------------------------------------------------------

            $('#room_id').on('change', function() {

                let selected = $(this).find(':selected');

                let rent = selected.data('rent') || 0;

                let status = selected.data('status') || 'N/A';

                $('#roomRent').text('₹' + rent);

                let badge = $("#roomStatus");

                badge
                    .removeClass(
                        'bg-green-100 text-green-700 bg-red-100 text-red-700 bg-yellow-100 text-yellow-700 bg-zinc-100 text-zinc-700'
                    )
                    .text(status);

                if (status == "available") {

                    badge.addClass('bg-green-100 text-green-700');

                } else if (status == "running") {

                    badge.addClass('bg-red-100 text-red-700');

                } else if (status == "maintenance") {

                    badge.addClass('bg-yellow-100 text-yellow-700');

                } else {

                    badge.addClass('bg-zinc-100 text-zinc-700');

                }

            });

            //-------------------------------------------------------
            // Trigger Building
            //-------------------------------------------------------

            loadFloors();

        });
    </script>

 <script>
document.addEventListener('DOMContentLoaded', function () {

    @foreach ($booking->guests as $index => $guest)

        const nationalityInput{{ $index }} = $("#nationality_{{ $index }}");

        nationalityInput{{ $index }}.countrySelect({
            preferredCountries: ['in', 'us', 'gb', 'ae']
        });

        // Set saved nationality
        nationalityInput{{ $index }}.countrySelect(
            "setCountry",
            @json($guest->nationality)
        );

        function toggleFields{{ $index }}() {

            const countryData = nationalityInput{{ $index }}
                .countrySelect("getSelectedCountryData");

            console.log("Guest {{ $index }}", {
                stored: @json($guest->nationality),
                countryData: countryData
            });

            if (countryData && countryData.iso2 === "in") {

                $("#state_wrapper_{{ $index }}").removeClass("hidden");
                $("#cform_wrapper_{{ $index }}").addClass("hidden");

            } else {

                $("#state_wrapper_{{ $index }}").addClass("hidden");
                $("#cform_wrapper_{{ $index }}").removeClass("hidden");
            }
        }

        toggleFields{{ $index }}();

        nationalityInput{{ $index }}.on("countrychange", function () {
            toggleFields{{ $index }}();
        });

    @endforeach

});
</script>
@endpush
