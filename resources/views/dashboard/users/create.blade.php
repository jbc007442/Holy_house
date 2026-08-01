@extends('dashboard.base')

@section('title', 'Create User')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Create User
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Add a new user to the system.
            </p>

        </div>

        <a href="{{ route('dashboard.users.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">

            <i class="fa-solid fa-arrow-left"></i>

            Back

        </a>

    </div>

    <!-- Card -->

    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">

        <form action="{{ route('dashboard.users.store') }}" method="POST">

            @csrf

            <div class="p-6 space-y-6">

                <!-- Name -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                        required>

                </div>

                <!-- Email -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                        required>

                </div>

                <!-- Role -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900">

                        <option value="user">
                            User
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                <!-- Status -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900">

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                </div>

                <!-- Password -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                        required>

                </div>

                <!-- Confirm Password -->

                <div>

                    <label class="block text-sm font-medium text-zinc-700 mb-2">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                        required>

                </div>

            </div>

            <!-- Footer -->

            <div class="flex justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4 rounded-b-xl">

                <a href="{{ route('dashboard.users.index') }}"
                    class="rounded-lg border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 hover:bg-white">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-zinc-800">

                    <i class="fa-solid fa-user-plus mr-2"></i>

                    Create User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection