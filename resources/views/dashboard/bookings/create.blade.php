@extends('dashboard.base')

@section('title', 'New Booking')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">

                        <i class="fa-solid fa-calendar-plus text-xl text-amber-600"></i>

                    </div>

                    <div>

                        <h1 class="text-3xl font-bold text-zinc-800">
                            New Booking
                        </h1>

                        <p class="text-zinc-500 mt-1">
                            Create a new reservation or check in a guest.
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex items-center gap-3">

                <a href="{{ route('dashboard.bookings.history') }}"
                    class="px-5 py-3 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                    <i class="fa-solid fa-arrow-left mr-2"></i>

                    Back

                </a>

            </div>

        </div>

        <form action="{{ route('dashboard.bookings.store') }}" method="POST">

            @csrf
            <!-- ========================================================= -->
            <!-- Guest Information -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-users text-blue-600"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-zinc-800">
                            Guest Details
                        </h2>

                        <p class="text-sm text-zinc-500">
                            Select the total number of guests and enter their information.
                        </p>
                    </div>

                </div>

                <!-- Body -->
                <div class="p-6">

                    <!-- Guest Count -->
                    <div class="max-w-sm mb-8">

                        <label class="block mb-2 text-sm font-medium text-zinc-700">
                            Total Guests (PAX)
                        </label>

                        <select id="guestCount"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">

                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">
                                    {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}
                                </option>
                            @endfor

                        </select>

                    </div>

                    <!-- Dynamic Guest Cards -->
                    <div id="guestContainer" class="space-y-5"></div>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Room Information -->
            <!-- ========================================================= -->

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm mt-6">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-zinc-200 flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-bed text-indigo-600"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-zinc-800">
                            Room Information
                        </h2>

                        <p class="text-sm text-zinc-500">
                            Select building, floor and available room.
                        </p>
                    </div>

                </div>

                <!-- Body -->
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Building -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Building
                            </label>

                            <select id="building_id" name="building_id"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="">Select Building</option>

                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}">
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
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="">Select Floor</option>

                            </select>

                        </div>

                        <!-- Room -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Room
                            </label>

                            <select id="room_id" name="room_id"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="">Select Room</option>

                            </select>

                        </div>

                    </div>

                    <!-- Room Details -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                        <!-- Rent -->
                        <div class="rounded-xl border border-zinc-200 p-5 bg-zinc-50">

                            <p class="text-sm text-zinc-500">
                                Room Rent
                            </p>

                            <h3 id="roomRent" class="text-xl font-semibold mt-2">
                                ₹0
                            </h3>

                        </div>

                        <!-- Status -->
                        <div class="rounded-xl border border-zinc-200 p-5 bg-zinc-50">

                            <p class="text-sm text-zinc-500">
                                Room Status
                            </p>

                            <span id="roomStatus"
                                class="inline-flex mt-2 px-3 py-1 rounded-full text-sm font-medium bg-zinc-100 text-zinc-700">

                                Select Room

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- Payment Information -->
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
                            Enter payment details for this booking.
                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Room Rent -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Room Rent
                            </label>

                            <input type="number" name="room_rent" id="room_rent" min="0" step="0.01"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3" placeholder="Enter room rent">

                        </div>

                        <!-- Payment Amount -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Payment Amount
                            </label>

                            <input type="number" name="amount" id="amount" value="0" min="0" step="0.01"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3"
                                placeholder="Enter payment amount">

                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

                        <!-- Payment Type -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Payment Type
                            </label>

                            <select name="payment_type" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="advance">Advance</option>
                                <option value="partial">Partial</option>
                                <option value="final">Final</option>

                            </select>

                        </div>

                        <!-- Payment Method -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Payment Method
                            </label>

                            <select name="payment_method" class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>

                            </select>

                        </div>

                        <!-- Transaction Number -->
                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Transaction No.
                            </label>

                            <input type="text" name="transaction_no"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3" placeholder="Optional">

                        </div>

                    </div>

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

                        <h2 class="text-lg font-semibold text-zinc-800">
                            Remarks
                        </h2>

                        <p class="text-sm text-zinc-500">
                            Add any special instructions or internal notes.
                        </p>

                    </div>

                </div>

                <div class="p-6">

                    <textarea name="remarks" rows="5" class="w-full rounded-xl border border-zinc-300 px-4 py-3"
                        placeholder="Write remarks here..."></textarea>

                </div>

            </div>



            <!-- ========================================================= -->
            <!-- Footer -->
            <!-- ========================================================= -->

            <div class="flex flex-wrap justify-end gap-3 mt-8">

                <button type="submit" name="status" value="checked_in"
                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                    <i class="fa-solid fa-right-to-bracket mr-2"></i>

                    Save & Check In

                </button>

            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(function() {

            /*
            |--------------------------------------------------------------------------
            | Building -> Floors
            |--------------------------------------------------------------------------
            */

            function loadFloors() {

                let buildingId = $('#building_id').val();

                $('#floor').html('<option value="">Loading...</option>');
                $('#room_id').html('<option value="">Select Room</option>');

                if (!buildingId) {
                    $('#floor').html('<option value="">Select Floor</option>');
                    return;
                }

                $.ajax({
                    url: "{{ route('dashboard.property.buildings.floors', ':id') }}".replace(':id',
                        buildingId),
                    type: "GET",
                    dataType: "json",

                    success: function(floors) {

                        let html = '<option value="">Select Floor</option>';

                        $.each(floors, function(index, floor) {

                            html += `
                        <option value="${floor.name}">
                            ${floor.name}
                        </option>
                    `;

                        });

                        $('#floor').html(html);

                    },

                    error: function() {

                        $('#floor').html('<option value="">No Floors Found</option>');

                    }

                });

            }

            /*
            |--------------------------------------------------------------------------
            | Floor -> Rooms
            |--------------------------------------------------------------------------
            */

            function loadRooms() {

                let buildingId = $('#building_id').val();
                let floor = $('#floor').val();

                $('#room_id').html('<option value="">Loading...</option>');

                if (!buildingId || !floor) {

                    $('#room_id').html('<option value="">Select Room</option>');

                    return;

                }

                $.ajax({

                    url: "{{ route('dashboard.bookings.rooms', ':id') }}".replace(':id', buildingId),

                    type: "GET",

                    data: {
                        floor: floor
                    },

                    dataType: "json",

                    success: function(rooms) {
                        console.log(rooms);

                        let html = '<option value="">Select Room</option>';

                        $.each(rooms, function(index, room) {

                            html += `
                        <option
                            value="${room.id}"
                            data-rent="${room.base_price}"
                            data-status="${room.status}">
                            ${room.room_number}
                        </option>
                    `;

                        });

                        $('#room_id').html(html);

                    },

                    error: function() {

                        $('#room_id').html('<option value="">No Rooms Found</option>');

                    }

                });

            }

            /*
            |--------------------------------------------------------------------------
            | Events
            |--------------------------------------------------------------------------
            */

            $('#building_id').on('change', function() {

                $('#roomRent').text('₹0');

                $('#roomStatus')
                    .text('Select Room')
                    .removeClass(
                        'bg-green-100 text-green-700 bg-red-100 text-red-700 bg-yellow-100 text-yellow-700')
                    .addClass('bg-zinc-100 text-zinc-700');

                loadFloors();

            });

            $('#floor').on('change', function() {

                loadRooms();

            });

            $('#room_id').on('change', function() {

                let selected = $(this).find(':selected');

                let rent = selected.data('rent') || 0;
                let status = selected.data('status') || 'N/A';

                $('#roomRent').text('₹' + rent);

                $('input[name="room_rent"]').val(rent);

                let badge = $('#roomStatus');

                badge.text(status);

                badge.removeClass(
                    'bg-green-100 text-green-700 bg-red-100 text-red-700 bg-yellow-100 text-yellow-700 bg-zinc-100 text-zinc-700'
                );

                if (status === 'available') {

                    badge.addClass('bg-green-100 text-green-700');

                } else if (status === 'blocked') {

                    badge.addClass('bg-red-100 text-red-700');

                } else if (status === 'maintenance') {

                    badge.addClass('bg-yellow-100 text-yellow-700');

                } else {

                    badge.addClass('bg-zinc-100 text-zinc-700');

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Guest Form
        |--------------------------------------------------------------------------
        */

        document.addEventListener('DOMContentLoaded', function() {

            const guestCount = document.getElementById('guestCount');
            const guestContainer = document.getElementById('guestContainer');
            const indianStates = [
                "Andhra Pradesh",
                "Arunachal Pradesh",
                "Assam",
                "Bihar",
                "Chhattisgarh",
                "Goa",
                "Gujarat",
                "Haryana",
                "Himachal Pradesh",
                "Jharkhand",
                "Karnataka",
                "Kerala",
                "Madhya Pradesh",
                "Maharashtra",
                "Manipur",
                "Meghalaya",
                "Mizoram",
                "Nagaland",
                "Odisha",
                "Punjab",
                "Rajasthan",
                "Sikkim",
                "Tamil Nadu",
                "Telangana",
                "Tripura",
                "Uttar Pradesh",
                "Uttarakhand",
                "West Bengal",

                // Union Territories
                "Andaman and Nicobar Islands",
                "Chandigarh",
                "Dadra and Nagar Haveli and Daman and Diu",
                "Delhi",
                "Jammu and Kashmir",
                "Ladakh",
                "Lakshadweep",
                "Puducherry"
            ];

            const stateOptions = indianStates
                .map(state => `<option value="${state}">${state}</option>`)
                .join('');

            function renderGuests() {

                guestContainer.innerHTML = '';

                let total = parseInt(guestCount.value);

                for (let i = 1; i <= total; i++) {

                    guestContainer.innerHTML += `
                <div class="border rounded-2xl p-5">

                    <h3 class="font-semibold text-zinc-800 mb-5">
                        Guest ${i}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Guest Name
                            </label>

                            <input
                                type="text"
                                name="guests[${i}][guest_name]"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Mobile Number
                            </label>

                            <input
                                type="text"
                                name="guests[${i}][mobile]"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                ID Type
                            </label>

                            <select
                                name="guests[${i}][id_type]"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                <option value="">Select ID</option>
                                <option>Aadhaar Card</option>
                                <option>OCI</option>
                                <option>Driving Licence</option>
                                <option>Passport</option>
                                <option>Voter ID</option>

                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                ID Number
                            </label>

                            <input
                                type="text"
                                name="guests[${i}][id_number]"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Nationality
                            </label>

                            <input
                                type="text"
                                name="guests[${i}][nationality]"
                                id="nationality_${i}"
                                class="w-full rounded-xl border border-zinc-300 px-4 py-3">
                        </div>

                        <div>
                             <label class="block mb-2 text-sm font-medium">
                                 State
                             </label>

                             <select
                               name="guests[${i}][state]"
                                 class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                                  <option value="">Select State / UT</option>
                                   ${stateOptions}

                              </select>
                         </div>

                    </div>

                </div>
            `;
                }

                for (let i = 1; i <= total; i++) {

                    $(`#nationality_${i}`).countrySelect({
                        defaultCountry: "in",
                        preferredCountries: ['in', 'us', 'gb', 'ae']
                    });

                }

            }

            renderGuests();

            guestCount.addEventListener('change', renderGuests);

        });
    </script>
@endpush
