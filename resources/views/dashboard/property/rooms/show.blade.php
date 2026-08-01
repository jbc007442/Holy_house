@extends('dashboard.base')

@section('title', 'Room Details')

@section('content')

@php

$statusColors = [
    'available' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
    'running' => 'bg-blue-100 text-blue-700 border border-blue-200',
    'blocked' => 'bg-red-100 text-red-700 border border-red-200',
    'maintenance' => 'bg-amber-100 text-amber-700 border border-amber-200',
];

@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-6 p-6">

    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Hero -->

        <div
            class="relative overflow-hidden rounded-3xl bg-gray-50 text-black shadow-2xl">

            <div class="relative p-10 text-black">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                    <div>

                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/15 text-black text-sm">

                            <i class="fa-solid fa-hotel"></i>

                            Property Management

                        </span>

                        <h1 class="mt-5 text-5xl font-bold text-black">

                            Room {{ $room->room_number }}

                        </h1>

                        <p class="mt-3 text-lg text-blue-700">

                            Complete room information, room status and management
                            dashboard.

                        </p>

                    </div>

                    <div class="flex flex-wrap gap-4">

                        <a href="{{ route('dashboard.property.rooms.edit', $room->id) }}"
                            class="px-6 py-3 rounded-2xl bg-white text-indigo-600 font-semibold shadow hover:scale-105 transition">

                            <i class="fa-solid fa-pen mr-2"></i>

                            Edit Room

                        </a>

                        <a href="{{ route('dashboard.property.rooms') }}"
                            class="px-6 py-3 rounded-2xl border border-black/30 text-black  hover:bg-white/10 transition">

                            <i class="fa-solid fa-arrow-left mr-2"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- Summary -->

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Building -->

            <div
                class="bg-white rounded-3xl border border-zinc-200 p-7 shadow-sm hover:shadow-xl transition duration-300">

                <div
                    class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center">

                    <i class="fa-solid fa-building text-3xl text-indigo-600"></i>

                </div>

                <p class="mt-5 text-sm uppercase tracking-wider text-zinc-500">

                    Building

                </p>

                <h3 class="mt-2 text-2xl font-bold text-zinc-800">

                    {{ $room->building?->name }}

                </h3>

            </div>

            <!-- Room -->

            <div
                class="bg-white rounded-3xl border border-zinc-200 p-7 shadow-sm hover:shadow-xl transition">

                <div
                    class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <i class="fa-solid fa-door-open text-3xl text-blue-600"></i>

                </div>

                <p class="mt-5 text-sm uppercase tracking-wider text-zinc-500">

                    Room Number

                </p>

                <h3 class="mt-2 text-2xl font-bold text-zinc-800">

                    {{ $room->room_number }}

                </h3>

            </div>

            <!-- Floor -->

            <div
                class="bg-white rounded-3xl border border-zinc-200 p-7 shadow-sm hover:shadow-xl transition">

                <div
                    class="w-16 h-16 rounded-2xl bg-violet-100 flex items-center justify-center">

                    <i class="fa-solid fa-layer-group text-3xl text-violet-600"></i>

                </div>

                <p class="mt-5 text-sm uppercase tracking-wider text-zinc-500">

                    Floor

                </p>

                <h3 class="mt-2 text-2xl font-bold text-zinc-800">

                    {{ $room->floor }}

                </h3>

            </div>

            <!-- Status -->

            <div
                class="bg-white rounded-3xl border border-zinc-200 p-7 shadow-sm hover:shadow-xl transition">

                <div
                    class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center">

                    <i class="fa-solid fa-circle-check text-3xl text-emerald-600"></i>

                </div>

                <p class="mt-5 text-sm uppercase tracking-wider text-zinc-500">

                    Status

                </p>

                <div class="mt-3">

                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$room->status] ?? 'bg-zinc-100 text-zinc-700' }}">

                        <span class="w-2 h-2 rounded-full bg-current"></span>

                        {{ ucfirst($room->status) }}

                    </span>

                </div>

            </div>

        </div>

        <!-- Content -->

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Left -->

            <div
                class="lg:col-span-2 bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">

                <!-- Card Header -->

                <div class="border-b bg-zinc-50 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                            <i class="fa-solid fa-circle-info text-2xl text-indigo-600"></i>

                        </div>

                        <div>

                            <h2 class="text-2xl font-bold text-zinc-800">

                                Room Information

                            </h2>

                            <p class="text-zinc-500 mt-1">

                                View all details related to this room.

                            </p>

                        </div>

                    </div>

                </div>

                <!-- Body -->

                <div class="p-8">

                    <div class="grid md:grid-cols-2 gap-6">

                        <!-- Building -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">

                                Building

                            </p>

                            <h4 class="mt-2 text-lg font-semibold text-zinc-800">

                                {{ $room->building?->name }}

                            </h4>

                        </div>

                        <!-- Room -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">

                                Room Number

                            </p>

                            <h4 class="mt-2 text-lg font-semibold">

                                {{ $room->room_number }}

                            </h4>

                        </div>

                        <!-- Floor -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">

                                Floor

                            </p>

                            <h4 class="mt-2 text-lg font-semibold">

                                {{ $room->floor }}

                            </h4>

                        </div>

                        <!-- Capacity -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">

                                Capacity

                            </p>

                            <h4 class="mt-2 text-lg font-semibold">

                                {{ $room->capacity }} Person(s)

                            </h4>

                        </div>
                                                <!-- Created At -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">
                                Created At
                            </p>

                            <h4 class="mt-2 text-lg font-semibold text-zinc-800">
                                {{ $room->created_at->format('d M Y') }}
                            </h4>

                            <p class="text-sm text-zinc-500 mt-1">
                                {{ $room->created_at->format('h:i A') }}
                            </p>

                        </div>

                        <!-- Updated At -->

                        <div class="rounded-2xl bg-zinc-50 border border-zinc-200 p-6">

                            <p class="text-xs uppercase tracking-widest text-zinc-500">
                                Last Updated
                            </p>

                            <h4 class="mt-2 text-lg font-semibold text-zinc-800">
                                {{ $room->updated_at->format('d M Y') }}
                            </h4>

                            <p class="text-sm text-zinc-500 mt-1">
                                {{ $room->updated_at->format('h:i A') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Right Sidebar -->

            <div class="space-y-6">

                <!-- Status Card -->

                <div
                    class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">

                    <div
                        class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white">

                        <h2 class="font-bold text-xl">

                            Room Status

                        </h2>

                        <p class="text-indigo-800 text-sm mt-1">

                            Update current room availability

                        </p>

                    </div>

                    <div class="p-6">

                        <div class="mb-5">

                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold {{ $statusColors[$room->status] ?? 'bg-zinc-100 text-zinc-700' }}">

                                <span class="w-2.5 h-2.5 rounded-full bg-current"></span>

                                {{ ucfirst($room->status) }}

                            </span>

                        </div>

                        <form action="{{ route('dashboard.rooms.change-status', $room->id) }}"
                            method="POST">

                            @csrf
                            @method('PATCH')

                            <div class="space-y-5">

                                <select
                                    name="status"
                                    class="w-full rounded-2xl border border-zinc-300 px-5 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                                    <option value="available"
                                        {{ $room->status == 'available' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="blocked"
                                        {{ $room->status == 'blocked' ? 'selected' : '' }}>
                                        Blocked
                                    </option>

                                    <option value="maintenance"
                                        {{ $room->status == 'maintenance' ? 'selected' : '' }}>
                                        Maintenance
                                    </option>

                                </select>

                                <button
                                    class="w-full rounded-2xl py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-black font-semibold border transition">

                                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                                    Update Status

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

                <!-- Description -->

                <div
                    class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b bg-zinc-50">

                        <h2 class="font-bold text-xl text-zinc-800">

                            Description

                        </h2>

                    </div>

                    <div class="p-6">

                        @if($room->description)

                            <div
                                class="rounded-2xl bg-zinc-50 border border-zinc-200 p-5 leading-8 text-zinc-700 whitespace-pre-line">

                                {{ $room->description }}

                            </div>

                        @else

                            <div class="py-10 text-center">

                                <div
                                    class="mx-auto w-20 h-20 rounded-full bg-zinc-100 flex items-center justify-center">

                                    <i
                                        class="fa-regular fa-file-lines text-4xl text-zinc-400"></i>

                                </div>

                                <h3 class="mt-5 font-semibold text-zinc-700">

                                    No Description

                                </h3>

                                <p class="text-sm text-zinc-500 mt-2">

                                    This room doesn't have a description yet.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection