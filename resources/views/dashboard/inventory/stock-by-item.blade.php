@extends('dashboard.base')

@section('title', 'Stock per Item')

@section('content')

    <div class="space-y-6">

        <!-- Header -->

        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('dashboard.inventory.stock-report') }}"
                    class="inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-800">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Stock Report

                </a>

                <h1 class="mt-2 text-3xl font-bold text-zinc-800">

                    {{ $item->item_name }}

                </h1>

                <p class="mt-1 text-sm text-zinc-500">

                    {{ $item->category }}
                    •
                    {{ $item->unit }}

                </p>

            </div>

        </div>

        <!-- Summary -->

        <div id="summaryCards" class="grid grid-cols-1 md:grid-cols-4 gap-5">

            Loading...

        </div>

        <!-- Ledger -->

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b">

                <h2 class="font-semibold">

                    Stock Ledger

                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-6 py-4 text-left">Date</th>

                            <th class="px-6 py-4 text-left">Type</th>

                            <th class="px-6 py-4 text-right">In</th>

                            <th class="px-6 py-4 text-right">Out</th>

                            <th class="px-6 py-4 text-right">Balance</th>

                            <th class="px-6 py-4 text-right">Price</th>

                            <th class="px-6 py-4">Remarks</th>

                        </tr>

                    </thead>

                    <tbody id="ledgerTable">

                        <tr>

                            <td colspan="7" class="px-6 py-10 text-center">

                                Loading...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        const stockPerItemDataRoute = "{{ route('dashboard.inventory.stock-per-item.data', $item->id) }}";
    </script>
    <script src="{{ asset('js/dashboard/inventory/stock-report/stock-by-item.js') }}"></script>
@endpush
