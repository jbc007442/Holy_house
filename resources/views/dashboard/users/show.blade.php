@extends('dashboard.base')

@section('title', 'View User')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                User Details
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                View complete information about this user.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard.users.edit', $user) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">

                <i class="fa-solid fa-pen-to-square"></i>

                Edit

            </a>

            <a href="{{ route('dashboard.users.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    <!-- User Card -->

    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-zinc-200 flex items-center gap-4">

            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-zinc-900 text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>

                <h2 class="text-xl font-semibold text-zinc-800">
                    {{ $user->name }}
                </h2>

                <p class="text-zinc-500">
                    {{ $user->email }}
                </p>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Full Name
                </label>

                <p class="text-zinc-800 font-medium">
                    {{ $user->name }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Email Address
                </label>

                <p class="text-zinc-800 font-medium">
                    {{ $user->email }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Role
                </label>

                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Status
                </label>

                @if($user->status === 'active')
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                        Active
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">
                        Inactive
                    </span>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Created At
                </label>

                <p class="text-zinc-800">
                    {{ $user->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-500 mb-1">
                    Last Updated
                </label>

                <p class="text-zinc-800">
                    {{ $user->updated_at->format('d M Y, h:i A') }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection