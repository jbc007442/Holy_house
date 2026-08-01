@extends('dashboard.base')

@section('title', 'Guest Services')

@section('content')

    <div class="max-w-7xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">

            <!-- Header -->
            <div class="px-8 py-6 border-b border-zinc-200 flex items-center justify-between">

                <div>

                    <h1 class="text-3xl font-bold text-zinc-800">
                        Record
                    </h1>

                </div>

                <a href="{{ url()->previous() }}"
                    class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">

                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Back

                </a>

            </div>

            <!-- Booking Details -->
            <div class="px-8 py-8">

                <div class="grid grid-cols-2 gap-x-16 gap-y-5">

                    <div class="flex justify-between border-b pb-2">

                        <span class="text-zinc-500 font-medium">
                            Booking No
                        </span>

                        <span class="font-semibold text-zinc-800">
                            {{ $booking->booking_no }}
                        </span>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span class="text-zinc-500 font-medium">
                            Room
                        </span>

                        <span class="font-semibold text-zinc-800">
                            {{ $booking->room->room_number }}
                        </span>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span class="text-zinc-500 font-medium">
                            Guest
                        </span>

                        <span class="font-semibold text-zinc-800">
                            {{ $booking->guests->first()?->guest_name }}
                        </span>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span class="text-zinc-500 font-medium">
                            Mobile
                        </span>

                        <span class="font-semibold text-zinc-800">
                            {{ $booking->guests->first()?->mobile }}
                        </span>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span class="text-zinc-500 font-medium">
                            Check In
                        </span>

                        <span class="font-semibold text-zinc-800">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                        </span>

                    </div>

                </div>

            </div>

            <!-- Services -->
            <div class="border-t">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-zinc-100">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Service
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Qty
                                </th>

                                <th class="px-6 py-4 text-right font-semibold">
                                    Rate
                                </th>

                                <th class="px-6 py-4 text-right font-semibold">
                                    Amount
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            {{-- Existing Services --}}
                            @forelse($booking->services as $service)
                                <tr class="border-t hover:bg-zinc-50">

                                    <td class="px-6 py-4">
                                        {{ $service->service_name }}
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        @if ($service->type == 'chargeable')
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                                                Chargeable
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                                                Complimentary
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        {{ $service->quantity }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        ₹ {{ number_format($service->unit_price, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-semibold">
                                        ₹ {{ number_format($service->total_amount, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <form action="{{ route('dashboard.bookings.services.delete', $service) }}" method="POST"
                                            onsubmit="return confirm('Delete this service?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="text-red-600 hover:text-red-700">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="py-8 text-center text-zinc-500">

                                        No guest services added.

                                    </td>

                                </tr>
                            @endforelse


                            {{-- Add Service --}}
                            <form action="{{ route('dashboard.bookings.services.store', $booking) }}" method="POST">

                                @csrf

                                <tr class="border-t bg-amber-50">

                                    <td class="px-4 py-3">

                                        <input type="text" name="service_name" id="service_name"
                                            placeholder="Enter service name" class="w-full rounded-lg border-zinc-300">

                                        <select name="item_id" id="item_id"
                                            class="hidden w-full rounded-lg border-zinc-300">

                                            <option value="">
                                                Select Item
                                            </option>

                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->item_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </td>

                                    <td class="px-4 py-3">

                                        <select name="type" id="service_type" class="w-full rounded-lg border-zinc-300">

                                            <option value="chargeable">
                                                Chargeable
                                            </option>

                                            <option value="complimentary">
                                                Complimentary
                                            </option>

                                        </select>

                                    </td>

                                    <td class="px-4 py-3">

                                        <input type="number" name="quantity" min="1" value="1"
                                            class="w-20 rounded-lg border-zinc-300 text-center">

                                    </td>

                                    <td class="px-4 py-3">

                                        <input type="number" name="unit_price" step="0.01" value="0"
                                            class="w-full rounded-lg border-zinc-300 text-right">

                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold">

                                        Auto

                                    </td>

                                    <td class="px-4 py-3 text-center">

                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-white hover:bg-amber-600">

                                            <i class="fa-solid fa-plus"></i>

                                            Add

                                        </button>

                                    </td>

                                </tr>

                            </form>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Summary -->
            @php
                $roomRent = $booking->total_amount ?? 0;

                $advancePayment = $booking->paid_amount ?? 0;

                $serviceTotal = $booking->services->where('type', 'chargeable')->sum('total_amount');

                $complimentaryCount = $booking->services->where('type', 'complimentary')->count();

                $grandTotal = $roomRent + $serviceTotal;

                $balance = $grandTotal - $advancePayment;
            @endphp

            <div class="border-t">

                <div class="flex justify-end">

                    <div class="w-full max-w-md p-8">

                        <table class="w-full text-sm">

                            <tbody>

                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        Room Rent
                                    </td>

                                    <td class="py-3 text-right font-semibold">
                                        ₹ {{ number_format($roomRent, 2) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        Guest Services
                                    </td>

                                    <td class="py-3 text-right font-semibold">
                                        ₹ {{ number_format($serviceTotal, 2) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        Complimentary Services
                                    </td>

                                    <td class="py-3 text-right font-semibold text-green-600">
                                        {{ $complimentaryCount }}
                                    </td>

                                </tr>

                                <tr class="border-t">

                                    <td class="py-3 text-zinc-600 font-semibold">
                                        Total Bill
                                    </td>

                                    <td class="py-3 text-right font-semibold">
                                        ₹ {{ number_format($grandTotal, 2) }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        Advance Payment
                                    </td>

                                    <td class="py-3 text-right font-semibold text-green-600">
                                        - ₹ {{ number_format($advancePayment, 2) }}
                                    </td>

                                </tr>

                                <tr class="border-t-2">

                                    <td class="pt-5 text-xl font-bold text-zinc-800">
                                        Balance
                                    </td>

                                    <td class="pt-5 text-right text-2xl font-bold text-red-600">
                                        ₹ {{ number_format($balance, 2) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>

    </div>

@endsection

{{-- @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const type = document.getElementById('service_type');
    const serviceInput = document.getElementById('service_name');
    const itemSelect = document.getElementById('item_id');

    function toggleFields() {

        if (type.value === 'complimentary') {

            serviceInput.classList.add('hidden');
            itemSelect.classList.remove('hidden');

            serviceInput.value = '';

        } else {

            itemSelect.classList.add('hidden');
            serviceInput.classList.remove('hidden');

            itemSelect.value = '';

        }

    }

    toggleFields();

    type.addEventListener('change', toggleFields);

});
</script>
@endpush --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const type = document.getElementById('service_type');
    const serviceInput = document.getElementById('service_name');
    const itemSelect = document.getElementById('item_id');
    const rate = document.querySelector('input[name="unit_price"]');

    function toggleFields() {

        if (type.value === 'complimentary') {

            // Show Item Dropdown
            serviceInput.classList.add('hidden');
            itemSelect.classList.remove('hidden');

            serviceInput.value = '';

            // Complimentary => Rate = 0
            rate.value = 0;
            rate.readOnly = true;
            rate.classList.add('bg-zinc-100','cursor-not-allowed');

        } else {

            // Show Service Name
            itemSelect.classList.add('hidden');
            serviceInput.classList.remove('hidden');

            itemSelect.value = '';

            // Chargeable => Editable Rate
            rate.readOnly = false;
            rate.classList.remove('bg-zinc-100','cursor-not-allowed');

        }

    }

    toggleFields();

    type.addEventListener('change', toggleFields);

});
</script>
@endpush