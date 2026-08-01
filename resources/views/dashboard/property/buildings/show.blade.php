@extends('dashboard.base')

@section('title', 'View Building')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Building Details
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                View building information.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('dashboard.property.buildings.edit', $building->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">

                <i class="fa-solid fa-pen"></i>

                Edit

            </a>

            <a href="{{ route('dashboard.property.buildings') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 border border-zinc-300 rounded-xl hover:bg-zinc-50 transition">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    <!-- Building Information -->

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden">

        <div class="px-6 py-5 border-b border-zinc-200">

            <h2 class="text-lg font-semibold">
                Building Information
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6 p-6">

            <div>
                <p class="text-sm text-zinc-500">Building Name</p>
                <p class="mt-1 font-semibold text-lg">
                    {{ $building->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Building Code</p>
                <p class="mt-1 font-semibold">
                    {{ $building->code }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Total Floors</p>
                <p class="mt-1 font-semibold">
                    {{ $building->floors }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Status</p>

                @if($building->status == 'active')

                    <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        Active
                    </span>

                @else

                    <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        Inactive
                    </span>

                @endif

            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-zinc-500">Address</p>
                <p class="mt-1">
                    {{ $building->address ?: '-' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-zinc-500">Description</p>
                <p class="mt-1 whitespace-pre-line">
                    {{ $building->description ?: '-' }}
                </p>
            </div>

        </div>

    </div>

    <!-- Audit Information -->

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden">

        <div class="px-6 py-5 border-b border-zinc-200">

            <h2 class="text-lg font-semibold">
                Audit Information
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

            <div>
                <p class="text-sm text-zinc-500">Created At</p>
                <p class="mt-1 font-medium">
                    {{ $building->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-zinc-500">Last Updated</p>
                <p class="mt-1 font-medium">
                    {{ $building->updated_at->format('d M Y, h:i A') }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection