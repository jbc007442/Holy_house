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

            <a href="{{ route('dashboard.inventory.stock-movement.edit', $stockMovement->id) }}"
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

            <!-- Movement Type -->
            <div>

                <p class="text-sm text-zinc-500">
                    Movement Type
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    @if($stockMovement->type == 'out')
                        Stock Out
                    @else
                        Adjustment
                    @endif

                </h3>

            </div>

            <!-- Movement Date -->
            <div>

                <p class="text-sm text-zinc-500">
                    Movement Date
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->created_at->format('d M Y') }}

                </h3>

            </div>

            <!-- Item -->
            <div>

                <p class="text-sm text-zinc-500">
                    Item
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->item->item_name }}

                </h3>

            </div>

            <!-- Quantity -->
            <div>

                <p class="text-sm text-zinc-500">
                    Quantity
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->quantity }}

                </h3>

            </div>

            <!-- Building -->
            <div>

                <p class="text-sm text-zinc-500">
                    Building
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->building?->name ?? '-' }}

                </h3>

            </div>

            <!-- Floor -->
            <div>

                <p class="text-sm text-zinc-500">
                    Floor
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->buildingFloor?->name ?? '-' }}

                </h3>

            </div>

            <!-- Room -->
            <div>

                <p class="text-sm text-zinc-500">
                    Room
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->room?->room_number ?? '-' }}

                </h3>

            </div>

            <!-- Kitchen -->
            <div>

                <p class="text-sm text-zinc-500">
                    Kitchen
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->kitchen ? 'Yes' : 'No' }}

                </h3>

            </div>

            <!-- Other Property -->
            <div>

                <p class="text-sm text-zinc-500">
                    Other Property
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->other_property ?: '-' }}

                </h3>

            </div>

            <!-- Created By -->
            <div>

                <p class="text-sm text-zinc-500">
                    Created By
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->creator?->name ?? '-' }}

                </h3>

            </div>

            <!-- Created At -->
            <div>

                <p class="text-sm text-zinc-500">
                    Created At
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->created_at->format('d M Y h:i A') }}

                </h3>

            </div>

            <!-- Updated At -->
            <div>

                <p class="text-sm text-zinc-500">
                    Updated At
                </p>

                <h3 class="text-lg font-semibold mt-1">

                    {{ $stockMovement->updated_at->format('d M Y h:i A') }}

                </h3>

            </div>

        </div>

        <div class="mt-8">

            <p class="text-sm text-zinc-500 mb-2">
                Remarks
            </p>

            <div class="border rounded-lg p-4 bg-zinc-50">

                {{ $stockMovement->remarks ?: '-' }}

            </div>

        </div>

    </div>

</div>

@endsection