@extends('dashboard.base')

@section('title', 'Booking Details')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Booking Details
            </h1>

            <p class="text-zinc-500 mt-1">
                Booking No :
                <span class="font-semibold text-zinc-700">
                    BK-100001
                </span>
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('dashboard.bookings.edit') }}"
                class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">

                <i class="fa-solid fa-pen mr-2"></i>
                Edit

            </a>

            <a href="{{ route('dashboard.bookings.check-in') }}"
                class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white">

                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                Check In

            </a>

            <button
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100">

                <i class="fa-solid fa-print mr-2"></i>
                Print

            </button>

        </div>

    </div>

    <!-- Status -->

    <div class="grid md:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl border p-5">
            <p class="text-sm text-zinc-500">Status</p>

            <span
                class="inline-flex mt-3 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                Confirmed
            </span>
        </div>

        <div class="bg-white rounded-2xl border p-5">
            <p class="text-sm text-zinc-500">Booking Date</p>
            <h3 class="font-semibold text-lg mt-2">
                28 Jul 2026
            </h3>
        </div>

        <div class="bg-white rounded-2xl border p-5">
            <p class="text-sm text-zinc-500">Check In</p>
            <h3 class="font-semibold text-lg mt-2">
                30 Jul 2026
            </h3>
        </div>

        <div class="bg-white rounded-2xl border p-5">
            <p class="text-sm text-zinc-500">Check Out</p>
            <h3 class="font-semibold text-lg mt-2">
                02 Aug 2026
            </h3>
        </div>

    </div>

    <!-- Guest -->

    <div class="bg-white rounded-2xl border">

        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-lg">
                Guest Information
            </h2>
        </div>

        <div class="p-6 grid md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-zinc-500">Guest Name</p>
                <h4 class="font-semibold mt-1">Tarun Kumar</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Mobile</p>
                <h4 class="font-semibold mt-1">
                    +91 99999 99999
                </h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Email</p>
                <h4 class="font-semibold mt-1">
                    tarun@email.com
                </h4>
            </div>

        </div>

    </div>

    <!-- Stay -->

    <div class="bg-white rounded-2xl border">

        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-lg">
                Stay Information
            </h2>
        </div>

        <div class="p-6 grid md:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-zinc-500">Adults</p>
                <h4 class="font-semibold mt-1">2</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Children</p>
                <h4 class="font-semibold mt-1">1</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Nights</p>
                <h4 class="font-semibold mt-1">3</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Booking Source</p>
                <h4 class="font-semibold mt-1">Walk-In</h4>
            </div>

        </div>

    </div>

    <!-- Room -->

    <div class="bg-white rounded-2xl border">

        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-lg">
                Room Information
            </h2>
        </div>

        <div class="p-6 grid md:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-zinc-500">Building</p>
                <h4 class="font-semibold mt-1">
                    Main Building
                </h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Room No.</p>
                <h4 class="font-semibold mt-1">
                    101
                </h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Room Type</p>
                <h4 class="font-semibold mt-1">
                    Deluxe
                </h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Floor</p>
                <h4 class="font-semibold mt-1">
                    1st Floor
                </h4>
            </div>

        </div>

    </div>

    <!-- Payment -->

    <div class="bg-white rounded-2xl border">

        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-lg">
                Payment Summary
            </h2>
        </div>

        <div class="p-6 grid md:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-zinc-500">Room Rent</p>
                <h4 class="font-semibold mt-1">$250</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Advance</p>
                <h4 class="font-semibold mt-1">$100</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Balance</p>
                <h4 class="font-semibold mt-1 text-red-600">$150</h4>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Payment Method</p>
                <h4 class="font-semibold mt-1">
                    Cash
                </h4>
            </div>

        </div>

    </div>

    <!-- Remarks -->

    <div class="bg-white rounded-2xl border">

        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-lg">
                Remarks
            </h2>
        </div>

        <div class="p-6 text-zinc-600">

            Guest requested an early morning wake-up call and a non-smoking room.

        </div>

    </div>

</div>

@endsection