@extends('dashboard.base')

@section('title', 'Housekeeping Alerts')

@section('content')


<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900">
            Housekeeping Alerts
        </h1>

        <p class="mt-1 text-sm text-zinc-500">
            Rooms requiring housekeeping after checkout.
        </p>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alerts --}}
    @if ($messages->isEmpty())

        <div class="rounded-xl border border-zinc-200 bg-white p-8 text-center shadow-sm">

            <div class="mb-3 text-4xl">
                🧹
            </div>

            <h2 class="text-lg font-semibold text-zinc-800">
                No housekeeping alerts
            </h2>

            <p class="mt-1 text-sm text-zinc-500">
                There are currently no rooms waiting for housekeeping.
            </p>

        </div>

    @else

        <div class="space-y-4">

            @foreach ($messages as $message)

                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between gap-4">

                        {{-- Room Information --}}
                        <div class="flex items-start gap-4">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                                <i class="fa-solid fa-bed text-xl text-amber-600"></i>
                            </div>

                            <div>

                                <h2 class="text-lg font-semibold text-zinc-900">
                                    Room {{ $message->room->room_number }}
                                </h2>

                                <p class="mt-1 text-sm text-zinc-600">
                                    {{ $message->message }}
                                </p>

                                @if ($message->booking)
                                    <p class="mt-2 text-xs text-zinc-400">
                                        Booking #{{ $message->booking->id }}
                                    </p>
                                @endif

                                <p class="mt-1 text-xs text-zinc-400">
                                    {{ $message->created_at->format('d M Y, h:i A') }}
                                </p>

                            </div>

                        </div>

                        {{-- Current Status --}}
                        <span class="shrink-0 rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase text-zinc-700">
                            {{ $message->room->status }}
                        </span>

                    </div>

                    {{-- Status Actions --}}
                    <div class="mt-5 border-t border-zinc-100 pt-4">

                        <p class="mb-3 text-sm font-medium text-zinc-700">
                            Update Room Status
                        </p>

                        <form
                            method="POST"
                            action="{{ route(
                                'dashboard.property.housekeeping.room-status',
                                $message->room->id
                            ) }}"
                            class="flex gap-3"
                        >

                            @csrf
                            @method('PATCH')

                            {{-- Available --}}
                            <button
                                type="submit"
                                name="status"
                                value="available"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                            >
                                <i class="fa-solid fa-check"></i>
                                Available
                            </button>

                            {{-- Blocked --}}
                            <button
                                type="submit"
                                name="status"
                                value="blocked"
                                class="inline-flex items-center gap-2 rounded-lg bg-zinc-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800"
                            >
                                <i class="fa-solid fa-ban"></i>
                                Block
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>


@endsection

@push('scripts')

@endpush
