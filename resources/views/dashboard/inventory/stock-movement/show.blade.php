@extends('dashboard.base')

@section('title', 'Stock Movement Details')

@section('content')

<div class="p-6 max-w-5xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Stock Movement Details
            </h1>

            <p class="text-zinc-500 mt-1">
                View stock movement information.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('dashboard.inventory.stock-movement.edit', 1) }}"
                class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">

                <i class="fa-solid fa-pen mr-2"></i>
                Edit

            </a>

            <a href="{{ route('dashboard.inventory.stock-movement') }}"
                class="px-4 py-2 border rounded-lg hover:bg-zinc-50">

                Back

            </a>

        </div>

    </div>

    <div class="bg-white border rounded-xl p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-zinc-500">Movement Type</p>
                <h3 class="text-lg font-semibold mt-1">
                    Stock In
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Movement Date</p>
                <h3 class="text-lg font-semibold mt-1">
                    28 Jul 2026
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Item</p>
                <h3 class="text-lg font-semibold mt-1">
                    Mineral Water
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Quantity</p>
                <h3 class="text-lg font-semibold mt-1">
                    100 Bottles
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Reference No.</p>
                <h3 class="text-lg font-semibold mt-1">
                    PO-0001
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Supplier</p>
                <h3 class="text-lg font-semibold mt-1">
                    ABC Supplier
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Created By</p>
                <h3 class="text-lg font-semibold mt-1">
                    Admin
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Created At</p>
                <h3 class="text-lg font-semibold mt-1">
                    28 Jul 2026, 10:15 AM
                </h3>
            </div>

        </div>

        <div class="mt-8">

            <p class="text-sm text-zinc-500 mb-2">
                Remarks
            </p>

            <div class="border rounded-lg p-4 bg-zinc-50">

                Monthly purchase of mineral water bottles for guest rooms.

            </div>

        </div>

    </div>

</div>

@endsection