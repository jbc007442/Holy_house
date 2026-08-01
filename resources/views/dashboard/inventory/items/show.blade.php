@extends('dashboard.base')

@section('title', 'Item Details')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Item Details
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                View inventory item information.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('dashboard.inventory.items') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-300 hover:bg-zinc-100 transition">
                Back
            </a>

            <a href="{{ route('dashboard.inventory.items.edit', $item->id) }}"
                class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white transition">
                Edit
            </a>

        </div>

    </div>

    <!-- Details -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

            <div>
                <p class="text-sm text-zinc-500 mb-1">Item Name</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->item_name }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Category</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->category }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Unit</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->unit }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Purchase Price</p>
                <h3 class="font-semibold text-zinc-800">
                    ₹ {{ number_format($item->purchase_price, 2) }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Opening Stock</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->opening_stock }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Minimum Stock</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->minimum_stock }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Status</p>

                @if($item->status)
                    <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                        Active
                    </span>
                @else
                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                        Inactive
                    </span>
                @endif
            </div>

            <div>
                <p class="text-sm text-zinc-500 mb-1">Created At</p>
                <h3 class="font-semibold text-zinc-800">
                    {{ $item->created_at->format('d M Y h:i A') }}
                </h3>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-zinc-500 mb-2">Remarks</p>

                <div class="rounded-xl border border-zinc-200 p-4 bg-zinc-50 text-zinc-700">
                    {{ $item->remarks ?: 'No remarks available.' }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection