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

            <!-- Invoice Details -->
            <form action="{{ route('dashboard.bookings.invoice.update', $booking) }}" method="POST">

                @csrf
                @method('PATCH')

                <div class="px-8 py-8">

                    <div class="grid grid-cols-2 gap-x-16 gap-y-5">

                        <!-- Booking No -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Booking No
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ $booking->booking_no }}
                            </span>

                        </div>

                        <!-- Room -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Room
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ $booking->room->room_number }}
                            </span>

                        </div>

                        <!-- Guest -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Guest
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ $booking->guests->first()?->guest_name }}
                            </span>

                        </div>

                        <!-- Mobile -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Mobile
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ $booking->guests->first()?->mobile }}
                            </span>

                        </div>

                        <!-- Check In -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Check In
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                            </span>

                        </div>

                        <!-- State -->
                        <div class="flex justify-between border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                State
                            </span>

                            <span class="font-semibold text-zinc-800">
                                {{ $booking->guests->first()?->state ?? '-' }}
                            </span>

                        </div>

                        <!-- Discount -->
                        <div class="flex justify-between items-center border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Discount
                            </span>

                            <input type="number" name="discount" value="{{ old('discount', $booking->discount ?? 0) }}"
                                min="0" step="0.01" placeholder="0.00"
                                class="w-44 rounded-lg border border-zinc-300 px-3 py-2 text-right">

                        </div>

                        <!-- Discount Remark -->
                        <div class="flex justify-between items-start border-b pb-2">

                            <span class="text-zinc-500 font-medium pt-2">
                                Discount Remark
                            </span>

                            <textarea name="discount_remark" rows="3" placeholder="Enter discount remark..."
                                class="w-64 rounded-lg border border-zinc-300 px-3 py-2 resize-none">{{ old('discount_remark', $booking->discount_remark) }}</textarea>

                        </div>

                        <!-- Rate Type -->
                        <div class="flex justify-between items-center border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Rate Type
                            </span>

                            <select name="rate_type" class="rounded-lg border border-zinc-300 px-3 py-2">

                                <option value="EP"
                                    {{ old('rate_type', $booking->rate_type) == 'EP' ? 'selected' : '' }}>
                                    EP (European Plan)
                                </option>

                                <option value="CP"
                                    {{ old('rate_type', $booking->rate_type) == 'CP' ? 'selected' : '' }}>
                                    CP (Continental Plan)
                                </option>

                                <option value="MAP"
                                    {{ old('rate_type', $booking->rate_type) == 'MAP' ? 'selected' : '' }}>
                                    MAP (Modified American Plan)
                                </option>

                            </select>

                        </div>

                        <!-- Bill To -->
                        <div class="flex justify-between items-center border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Bill To
                            </span>

                            <input type="text" name="bill_to" value="{{ old('bill_to', $booking->bill_to) }}"
                                placeholder="Billing Party" class="w-64 rounded-lg border border-zinc-300 px-3 py-2">

                        </div>

                        <!-- Bill To GSTIN -->
                        <div class="flex justify-between items-center border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                Bill To GSTIN
                            </span>

                            <input type="text" name="bill_to_gstin"
                                value="{{ old('bill_to_gstin', $booking->bill_to_gstin) }}" placeholder="GSTIN"
                                class="w-64 rounded-lg border border-zinc-300 px-3 py-2">

                        </div>

                        <!-- HSN Code -->
                        <div class="flex justify-between items-center border-b pb-2">

                            <span class="text-zinc-500 font-medium">
                                HSN Code
                            </span>

                            <input type="text" name="hsn_code"
                                value="{{ old('hsn_code', $booking->hsn_code ?? '998552') }}"
                                class="w-44 rounded-lg border border-zinc-300 bg-zinc-100 px-3 py-2">

                        </div>

                    </div>

                    <!-- Save Button -->
                    <div class="mt-8 flex justify-end">

                        <button type="submit"
                            class="rounded-xl bg-blue-600 px-6 py-3 font-medium text-white hover:bg-blue-700">

                            <i class="fa-solid fa-floppy-disk mr-2"></i>

                            Save Invoice Details

                        </button>

                    </div>

                </div>

            </form>

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

                                    <form action="{{ route('dashboard.bookings.services.update', $service) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <!-- Service -->
                                        <td class="px-6 py-4">
                                            {{ $service->service_name }}
                                        </td>

                                        <!-- Type -->
                                        <td class="px-6 py-4 text-center">

                                            @if ($service->type == 'chargeable')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                                    Chargeable
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                                    Complimentary
                                                </span>
                                            @endif

                                        </td>

                                        <!-- Quantity -->
                                        <td class="px-6 py-4 text-center">

                                            <input type="number" name="quantity" value="{{ $service->quantity }}"
                                                min="1"
                                                class="w-20 rounded-lg border border-zinc-300 text-center px-2 py-1">

                                        </td>

                                        <!-- Rate -->
                                        <td class="px-6 py-4 text-right">

                                            <input type="number" name="unit_price" value="{{ $service->unit_price }}"
                                                step="0.01" {{ $service->type == 'complimentary' ? 'readonly' : '' }}
                                                class="w-28 rounded-lg border border-zinc-300 text-right px-2 py-1">

                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-4 text-right font-semibold">
                                            ₹ {{ number_format($service->total_amount, 2) }}
                                        </td>

                                        <!-- Action -->
                                        <td class="px-6 py-4 text-center">

                                            <div class="flex items-center justify-center gap-3">

                                                <button type="submit" class="text-blue-600 hover:text-blue-700"
                                                    title="Save">

                                                    <i class="fa-solid fa-floppy-disk"></i>

                                                </button>

                                    </form>

                                    <form action="{{ route('dashboard.bookings.services.delete', $service) }}"
                                        method="POST" onsubmit="return confirm('Delete this service?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </form>

                </div>

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

                        <!-- Service -->
                        <td class="px-4 py-3">

                            <input type="text" name="service_name" id="add_service_name"
                                placeholder="Enter service name" class="hidden w-full rounded-lg border-zinc-300">

                            <select name="item_id" id="add_item_id" class="w-full rounded-lg border-zinc-300">

                                <option value="">Select Item</option>

                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->item_name }}
                                    </option>
                                @endforeach

                            </select>

                        </td>

                        <!-- Type -->
                        <td class="px-4 py-3">

                            <select name="type" id="add_service_type" class="w-full rounded-lg border-zinc-300">

                                <option value="chargeable" selected>
                                    Chargeable
                                </option>

                                <option value="complimentary">
                                    Complimentary
                                </option>

                                <option value="other">
                                    Other
                                </option>

                            </select>

                        </td>

                        <!-- Qty -->
                        <td class="px-4 py-3">

                            <input type="number" name="quantity" min="1" value="1"
                                class="w-20 rounded-lg border-zinc-300 text-center">

                        </td>

                        <!-- Rate -->
                        <td class="px-4 py-3">

                            <input type="number" id="add_unit_price" name="unit_price" step="0.01" value="0"
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

                            <!-- Room Rent -->
                            <tr>
                                <td class="py-3 text-zinc-600">
                                    Room Rent
                                </td>

                                <td class="py-3 text-right font-semibold">
                                    ₹ {{ number_format($roomRent, 2) }}
                                </td>
                            </tr>

                            <!-- Chargeable Services -->
                            @foreach ($booking->services->where('type', 'chargeable')->groupBy('service_name') as $serviceName => $services)
                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        {{ $serviceName }}
                                        <span class="ml-2 text-xs text-red-600">(Chargeable)</span>
                                    </td>

                                    <td class="py-3 text-right font-semibold">
                                        ₹ {{ number_format($services->sum('total_amount'), 2) }}
                                    </td>

                                </tr>
                            @endforeach

                            <!-- Complimentary Services -->
                            @foreach ($booking->services->where('type', 'complimentary')->groupBy('service_name') as $serviceName => $services)
                                <tr>

                                    <td class="py-3 text-zinc-600">
                                        {{ $serviceName }}
                                        <span class="ml-2 text-xs text-green-600">(Complimentary)</span>
                                    </td>

                                    <td class="py-3 text-right font-semibold text-green-600">
                                        FREE
                                    </td>

                                </tr>
                            @endforeach

                            <!-- Summary -->
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

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const type = document.getElementById('add_service_type');
            const serviceInput = document.getElementById('add_service_name');
            const itemSelect = document.getElementById('add_item_id');
            const rate = document.getElementById('add_unit_price');

            function toggleFields() {

                switch (type.value) {

                    case 'chargeable':

                        // Show item dropdown
                        itemSelect.classList.remove('hidden');
                        serviceInput.classList.add('hidden');

                        serviceInput.value = '';

                        rate.readOnly = false;
                        rate.classList.remove('bg-zinc-100', 'cursor-not-allowed');

                        break;

                    case 'complimentary':

                        // Show item dropdown
                        itemSelect.classList.remove('hidden');
                        serviceInput.classList.add('hidden');

                        serviceInput.value = '';

                        rate.value = 0;
                        rate.readOnly = true;
                        rate.classList.add('bg-zinc-100', 'cursor-not-allowed');

                        break;

                    case 'other':

                        // Show manual input
                        itemSelect.classList.add('hidden');
                        itemSelect.value = '';

                        serviceInput.classList.remove('hidden');

                        rate.readOnly = false;
                        rate.classList.remove('bg-zinc-100', 'cursor-not-allowed');

                        break;
                }

            }

            toggleFields();

            type.addEventListener('change', toggleFields);

        });
    </script>
@endpush
