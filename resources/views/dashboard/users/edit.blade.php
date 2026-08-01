@extends('dashboard.base')

@section('title', 'Edit User')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-zinc-800">
                Edit User
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Update user information.
            </p>

        </div>

        <a href="{{ route('dashboard.users.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">

            <i class="fa-solid fa-arrow-left"></i>

            Back

        </a>

    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">

        <form action="{{ route('dashboard.users.update', $user) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 p-6">

                <!-- Name -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                        required>

                </div>

                <!-- Email -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                        required>

                </div>

                <!-- Role -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0">

                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                            User
                        </option>

                    </select>

                </div>

                <!-- Status -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0">

                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

                <!-- Password -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        New Password
                        <span class="text-zinc-400">(Leave blank to keep current password)</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0">

                </div>

                <!-- Confirm Password -->
                <div>

                    <label class="mb-2 block text-sm font-medium text-zinc-700">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0">

                </div>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4">

                <a href="{{ route('dashboard.users.index') }}"
                    class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-zinc-900 px-5 py-2 text-sm font-medium text-white hover:bg-zinc-800">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                    Update User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')

<script>

$('form').on('submit', function () {

    if (window.notyf) {
        window.notyf.open({
            type: 'success',
            message: 'Updating user...',
            duration: 2000
        });
    }

});

</script>

@endpush