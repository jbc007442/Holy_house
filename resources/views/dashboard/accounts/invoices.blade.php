@extends('dashboard.base')

@section('title','Invoices')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800 flex items-center gap-3">

                <div class="h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center">

                    <i class="fa-solid fa-file-invoice-dollar text-indigo-600 text-xl"></i>

                </div>

                Invoices

            </h1>

            <p class="text-zinc-500 mt-2">

                Manage and view all generated customer invoices.

            </p>

        </div>

        <a href="#"
            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-white font-medium hover:bg-indigo-700 transition">

            <i class="fa-solid fa-download"></i>

            Export

        </a>

    </div>


    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-zinc-500">

                        Total Invoices

                    </p>

                    <h2 id="totalInvoice"
                        class="text-3xl font-bold text-zinc-800 mt-2">

                        0

                    </h2>

                </div>

                <div class="h-14 w-14 rounded-xl bg-blue-100 flex items-center justify-center">

                    <i class="fa-solid fa-file-invoice text-blue-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-zinc-500">

                        Revenue

                    </p>

                    <h2 id="totalRevenue"
                        class="text-3xl font-bold text-emerald-600 mt-2">

                        ₹0

                    </h2>

                </div>

                <div class="h-14 w-14 rounded-xl bg-emerald-100 flex items-center justify-center">

                    <i class="fa-solid fa-wallet text-emerald-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-zinc-500">

                        GST Collected

                    </p>

                    <h2 id="totalTax"
                        class="text-3xl font-bold text-orange-600 mt-2">

                        ₹0

                    </h2>

                </div>

                <div class="h-14 w-14 rounded-xl bg-orange-100 flex items-center justify-center">

                    <i class="fa-solid fa-receipt text-orange-600 text-2xl"></i>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-zinc-500">

                        This Month

                    </p>

                    <h2 id="thisMonth"
                        class="text-3xl font-bold text-purple-600 mt-2">

                        0

                    </h2>

                </div>

                <div class="h-14 w-14 rounded-xl bg-purple-100 flex items-center justify-center">

                    <i class="fa-solid fa-calendar-days text-purple-600 text-2xl"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- Filters -->

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="relative">

                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"></i>

                <input
                    id="search"
                    class="w-full rounded-xl border border-zinc-300 pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search invoice, booking or guest">

            </div>

            <div class="relative">

                <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"></i>

                <input
                    id="from"
                    type="date"
                    class="w-full rounded-xl border border-zinc-300 pl-11 pr-4 py-3">

            </div>

            <div class="relative">

                <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"></i>

                <input
                    id="to"
                    type="date"
                    class="w-full rounded-xl border border-zinc-300 pl-11 pr-4 py-3">

            </div>

            <button
                id="resetBtn"
                class="rounded-xl bg-red-50 text-red-600 border border-red-200 font-semibold hover:bg-red-100 transition">

                <i class="fa-solid fa-rotate-right mr-2"></i>

                Reset

            </button>

        </div>

    </div>


    <!-- Table -->

    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">

        <div class="px-6 py-4 border-b bg-zinc-50">

            <h3 class="font-semibold text-zinc-700 flex items-center gap-2">

                <i class="fa-solid fa-table-list text-indigo-600"></i>

                Invoice List

            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-zinc-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs uppercase font-semibold text-zinc-600">

                            Invoice

                        </th>

                        <th class="px-6 py-4 text-left text-xs uppercase font-semibold text-zinc-600">

                            Booking

                        </th>

                        <th class="px-6 py-4 text-left text-xs uppercase font-semibold text-zinc-600">

                            Guest

                        </th>

                        <th class="px-6 py-4 text-left text-xs uppercase font-semibold text-zinc-600">

                            Date

                        </th>

                        <th class="px-6 py-4 text-right text-xs uppercase font-semibold text-zinc-600">

                            Total

                        </th>

                        <th class="px-6 py-4 text-center text-xs uppercase font-semibold text-zinc-600">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody id="invoiceTable"
                    class="divide-y divide-zinc-100"></tbody>

            </table>

        </div>

        <div id="pagination"
            class="border-t bg-zinc-50 px-6 py-4"></div>

    </div>

</div>
<script>

window.invoiceConfig = {

    ajaxUrl: "{{ route('dashboard.accounts.invoices.ajax') }}",

    showUrl: "{{ route('dashboard.accounts.invoices.show', ['invoice' => '__ID__']) }}"

};

</script>

<script src="{{ asset('js/dashboard/accounts/invoices/invoices.js') }}"></script>

@endsection