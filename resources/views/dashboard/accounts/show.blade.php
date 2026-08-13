@extends('dashboard.base')

@section('title', 'Invoice Details')

@section('content')

    @php
        $guest = $booking->guests->first();
    @endphp

    <div class="max-w-4xl mx-auto">

        <div class="overflow-hidden rounded-lg border border-zinc-300 bg-white shadow-sm">

            <!-- ===========================
                                                                                         HEADER
                                                                                    ============================ -->

            <div class="border-b border-zinc-300 px-6 py-5">

                <div class="text-center">

                    <!-- Logo -->
                    <div class="flex justify-center mb-4">

                        <img src="{{ asset('images/logo.png') }}" alt="Hotel Holy House Logo" class="h-20 w-auto">

                    </div>

                    <!-- Hotel Name -->
                    <h1 class="text-3xl font-bold uppercase tracking-wide text-zinc-900">
                        Hotel Holy House
                    </h1>

                    <p class="mt-1 text-sm font-semibold text-zinc-700">
                        GSTIN No. 06AJXPY2847D1ZK
                    </p>

                    <p class="mt-2 text-sm text-zinc-600">
                        Hotel Holy House Near Artemis Hospital,
                        Sector-51, Gurugram, Haryana - 122001, India
                    </p>

                    <p class="mt-1 text-sm text-zinc-600">
                        Phone : +91 8130131477
                        &nbsp;|&nbsp;
                        Email : holyhouse9898@gmail.com
                    </p>

                    <div class="mt-4">

                        <span
                            class="inline-block rounded border border-zinc-300 bg-zinc-100 px-5 py-1 text-base font-semibold tracking-widest text-zinc-700">

                            TAX INVOICE

                        </span>

                    </div>

                </div>

            </div>

            <!-- ===========================
                                                                                         CUSTOMER & INVOICE
                                                                                    ============================ -->

            <div class="grid grid-cols-2 gap-6 border-b border-zinc-300 bg-zinc-50 px-6 py-4">

                <!-- Bill To -->

                <div>

                    <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">

                        Bill To

                    </h3>

                    <table class="w-full text-xs">

                        <tr>

                            <td class="w-24 py-1 text-zinc-500">

                                Guest Name

                            </td>

                            <td class="font-medium text-zinc-800">

                                {{ $guest?->guest_name }}

                            </td>

                        </tr>

                        <tr>

                            <td class="w-24 py-1 align-top text-zinc-500">

                                Bill To

                            </td>

                            <td class="font-medium text-zinc-800">

                                {{ $booking->bill_to ?: 'Hotel Holy House Near Artemis Hospital, Sector-51, Gurugram, Haryana - 122001, India' }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Mobile

                            </td>

                            <td>

                                {{ $guest?->mobile }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Address

                            </td>

                            <td>

                                {{ $guest?->nationality }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                State

                            </td>

                            <td>

                                {{ $guest?->state }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                HSN Code

                            </td>

                            <td>

                                {{ $booking->hsn_code }}

                            </td>

                        </tr>
                        <tr>

                            <td class="py-1 text-zinc-500">
                                Bill To GSTIN
                            </td>

                            <td>
                                {{ $booking->bill_to_gstin }}
                            </td>

                        </tr>

                    </table>

                </div>

                <!-- Invoice -->

                <div>

                    <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">

                        Invoice Details

                    </h3>

                    <table class="w-full text-xs">

                        <tr>

                            <td class="w-28 py-1 text-zinc-500">

                                Invoice No

                            </td>

                            <td class="font-semibold">

                                {{ $invoice->invoice_no }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Booking No

                            </td>

                            <td>

                                {{ $booking->booking_no }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Room

                            </td>

                            <td>

                                {{ $booking->room->room_number }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">
                                Rate Type
                            </td>

                            <td>
                                {{ $booking->rate_type }}
                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Check In

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-1 text-zinc-500">

                                Check Out

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

            <!-- ===========================
                                                                                         CHARGES TABLE
                                                                                    ============================ -->

            <div class="p-5">

                <table class="w-full border border-zinc-300 text-xs">

                    <thead>

                        <tr class="bg-zinc-200 text-zinc-700 uppercase">

                            <th class="w-16 border-b border-zinc-300 px-3 py-2 text-left">
                                S.No
                            </th>

                            <th class="border-b border-zinc-300 px-3 py-2 text-left">
                                Description
                            </th>

                            <th class="w-20 border-b border-zinc-300 px-3 py-2 text-center">
                                Qty
                            </th>

                            <th class="w-28 border-b border-zinc-300 px-3 py-2 text-right">

                                Rate

                            </th>

                            <th class="w-32 border-b border-zinc-300 px-3 py-2 text-right">

                                Amount

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td class="border-b border-zinc-200 px-3 py-2">
                                1
                            </td>

                            <td class="border-b border-zinc-200 px-3 py-2">
                                Room Rent
                            </td>

                            <td class="border-b border-zinc-200 px-3 py-2 text-center">
                                {{ max(1, \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out))) }}
                            </td>

                            <td class="border-b border-zinc-200 px-3 py-2 text-right">
                                ₹{{ number_format($roomRent, 2) }}
                            </td>

                            @php
                                $stayDays = max(
                                    1,
                                    \Carbon\Carbon::parse($booking->check_in)->diffInDays(
                                        \Carbon\Carbon::parse($booking->check_out),
                                    ),
                                );
                            @endphp

                            <td class="border-b border-zinc-200 px-3 py-2 text-right font-medium">
                                ₹{{ number_format($roomRent * $stayDays, 2) }}
                            </td>

                        </tr>

                        @foreach ($booking->services->where('type', 'chargeable')->values() as $index => $service)
                            <tr>

                                <td class="border-b border-zinc-200 px-3 py-2">
                                    {{ $index + 2 }}
                                </td>

                                <td class="border-b border-zinc-200 px-3 py-2">
                                    {{ $service->service_name }}
                                </td>

                                <td class="border-b border-zinc-200 px-3 py-2 text-center">
                                    {{ $service->quantity }}
                                </td>

                                <td class="border-b border-zinc-200 px-3 py-2 text-right">
                                    ₹{{ number_format($service->unit_price, 2) }}
                                </td>

                                <td class="border-b border-zinc-200 px-3 py-2 text-right">
                                    ₹{{ number_format($service->total_amount, 2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
            <!-- ===========================
                                                    TOTALS
                                        ============================ -->

            @php
                $stayDays = max(
                    1,
                    \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)),
                );

                $roomRentTotal = $roomRent * $stayDays;

                $serviceTotal = $booking->services->where('type', 'chargeable')->sum('total_amount');

                $discount = $booking->discount ?? 0;

                $subtotal = $roomRentTotal + $serviceTotal - $discount;

                $guestState = trim($booking->guests->first()?->state ?? '');

                if (strcasecmp($guestState, 'Haryana') === 0) {
                    $cgstRate = 2.5;
                    $sgstRate = 2.5;
                    $igstRate = 0;
                } else {
                    $cgstRate = 0;
                    $sgstRate = 0;
                    $igstRate = 5;
                }

                $cgst = round($subtotal * ($cgstRate / 100), 2);
                $sgst = round($subtotal * ($sgstRate / 100), 2);
                $igst = round($subtotal * ($igstRate / 100), 2);

                $grandTotal = round($subtotal + $cgst + $sgst + $igst, 2);
            @endphp

            <div class="border-t border-zinc-300 px-5 py-4">

                <div class="ml-auto w-full max-w-sm">

                    <table class="w-full text-xs">
                        <tr>
                            <td class="py-2 text-zinc-600">
                                Discount
                            </td>
                            <td class="py-2 text-right">
                                - ₹{{ number_format($discount, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-zinc-600">
                                Sub Total
                            </td>
                            <td class="py-2 text-right font-medium">
                                ₹{{ number_format($subtotal, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-zinc-600">
                                CGST ({{ $cgstRate }}%)
                            </td>
                            <td class="py-2 text-right">
                                ₹{{ number_format($cgst, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-zinc-600">
                                SGST ({{ $sgstRate }}%)
                            </td>
                            <td class="py-2 text-right">
                                ₹{{ number_format($sgst, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-zinc-600">
                                IGST ({{ $igstRate }}%)
                            </td>
                            <td class="py-2 text-right">
                                ₹{{ number_format($igst, 2) }}
                            </td>
                        </tr>

                        <tr class="border-t border-zinc-300 bg-zinc-100">
                            <td class="py-3 text-sm font-bold uppercase">
                                Grand Total
                            </td>
                            <td class="py-3 text-right text-sm font-bold">
                                ₹{{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

            <!-- ===========================
                                                                                         AMOUNT IN WORDS
                                                                                    ============================ -->

            <div class="border-t border-zinc-300 px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wide text-zinc-500">
                            Amount in Words
                        </h4>

                        <p class="mt-2 text-sm font-semibold text-zinc-800 leading-6">
                            {{ ucfirst(\Illuminate\Support\Number::spell((int) round($grandTotal))) }} Rupees Only
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs uppercase tracking-wide text-zinc-500">
                            Total Amount
                        </p>

                        <p class="mt-2 text-lg font-bold text-zinc-900">
                            ₹{{ number_format($grandTotal, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ===========================
                                                                                         NOTES & SIGNATURE
                                                                                    ============================ -->

            <div class="grid grid-cols-2 gap-8 border-t border-zinc-300 px-5 py-5">

                <!-- Notes -->

                <div>

                    <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-500">

                        Notes

                    </h4>

                    <ul class="space-y-1 text-xs leading-6 text-zinc-600">

                        <li>• Thank you for staying with Hotel Holy House.</li>

                        <li>• GST included wherever applicable.</li>

                        <li>• Please retain this invoice for future reference.</li>

                        <li>• Subject to Gurugram jurisdiction.</li>

                    </ul>

                </div>

                <!-- Signature -->

                <div class="flex flex-col justify-end items-end">

                    <div class="w-52 border-t border-zinc-400 pt-2 text-center">

                        <p class="text-xs font-semibold uppercase text-zinc-700">

                            Authorized Signatory

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================
                                                                                     ACTION BUTTONS
                                                                                ============================ -->

        <div class="mt-5 flex justify-end gap-3 print:hidden">

            <a href="{{ url()->previous() }}"
                class="rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">

                Back

            </a>

            <button onclick="window.print()"
                class="rounded-md bg-zinc-800 px-4 py-2 text-sm font-medium text-white hover:bg-black">

                <i class="fa-solid fa-print mr-2"></i>

                Print Invoice

            </button>

            <a href="" class="rounded-md bg-zinc-700 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-900">

                <i class="fa-solid fa-file-pdf mr-2"></i>

                Download PDF

            </a>

        </div>

    </div>

@endsection


@push('styles')
    <style>
        @media print {

            body {
                background: #fff !important;
            }

            aside,
            nav,
            header,
            footer,
            .print\:hidden {
                display: none !important;
            }

            main {
                margin: 0 !important;
                padding: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            .rounded-lg {
                border-radius: 0 !important;
            }

            .max-w-4xl {
                max-width: 100% !important;
            }

            table {
                page-break-inside: avoid;
            }

        }
    </style>
@endpush
