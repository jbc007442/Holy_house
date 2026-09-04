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
                            required
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

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
                            required
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Role -->
                    <div>

                        <label class="mb-2 block text-sm font-medium text-zinc-700">
                            Role
                        </label>

                        <select
                            name="role"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                            required
                        >

                            <option value="superadmin"
                                {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>
                                Super Admin
                            </option>

                            <option value="admin"
                                {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="receptionist"
                                {{ old('role', $user->role) == 'receptionist' ? 'selected' : '' }}>
                                Receptionist
                            </option>

                            <option value="housekeeping"
                                {{ old('role', $user->role) == 'housekeeping' ? 'selected' : '' }}>
                                Housekeeping
                            </option>

                            <option value="storemanager"
                                {{ old('role', $user->role) == 'storemanager' ? 'selected' : '' }}>
                                Store Manager
                            </option>

                            <option value="user"
                                {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                User
                            </option>

                        </select>

                        @error('role')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Buildings -->
                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <label class="block text-sm font-medium text-zinc-700">
                                Assign Buildings
                            </label>

                            @if($buildings->count())
                                <button
                                    type="button"
                                    id="selectAllBuildings"
                                    class="text-sm font-medium text-zinc-700 hover:text-zinc-900"
                                >
                                    Select All
                                </button>
                            @endif

                        </div>

                        <div class="overflow-hidden rounded-lg border border-zinc-300">

                            @forelse($buildings as $building)

                                @php
                                    $assignedBuildingIds = old(
                                        'building_ids',
                                        $user->buildings->pluck('id')->toArray()
                                    );
                                @endphp

                                <label
                                    class="flex cursor-pointer items-center gap-3 border-b border-zinc-200 px-4 py-3 last:border-b-0 hover:bg-zinc-50"
                                >

                                    <input
                                        type="checkbox"
                                        name="building_ids[]"
                                        value="{{ $building->id }}"
                                        class="building-checkbox h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900"
                                        {{ in_array($building->id, $assignedBuildingIds) ? 'checked' : '' }}
                                    >

                                    <div class="flex-1">

                                        <div class="text-sm font-medium text-zinc-800">
                                            {{ $building->name }}
                                        </div>

                                        @if($building->code)
                                            <div class="mt-0.5 text-xs text-zinc-500">
                                                Code: {{ $building->code }}
                                            </div>
                                        @endif

                                    </div>

                                </label>

                            @empty

                                <div class="px-4 py-6 text-center">

                                    <i class="fa-solid fa-building mb-2 text-2xl text-zinc-300"></i>

                                    <p class="text-sm text-zinc-500">
                                        No buildings available.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                        <p class="mt-2 text-xs text-zinc-500">
                            Select one or more buildings for this user.
                        </p>

                        @error('building_ids')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('building_ids.*')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Status -->
                    <div>

                        <label class="mb-2 block text-sm font-medium text-zinc-700">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                            required
                        >

                            <option value="active"
                                {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Password -->
                    <div>

                        <label class="mb-2 block text-sm font-medium text-zinc-700">
                            New Password
                            <span class="text-zinc-400">
                                (Leave blank to keep current password)
                            </span>
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                        >

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Confirm Password -->
                    <div>

                        <label class="mb-2 block text-sm font-medium text-zinc-700">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-zinc-900 focus:ring-0"
                        >

                    </div>

                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4">

                    <a
                        href="{{ route('dashboard.users.index') }}"
                        class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-zinc-900 px-5 py-2 text-sm font-medium text-white hover:bg-zinc-800"
                    >

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
    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Select / Deselect all buildings
         */
        const selectAllButton = document.getElementById('selectAllBuildings');
        const checkboxes = document.querySelectorAll('.building-checkbox');

        if (selectAllButton && checkboxes.length) {

            function updateSelectAllText() {

                const allChecked = Array.from(checkboxes).every(function (checkbox) {
                    return checkbox.checked;
                });

                selectAllButton.textContent = allChecked
                    ? 'Deselect All'
                    : 'Select All';
            }

            selectAllButton.addEventListener('click', function () {

                const allChecked = Array.from(checkboxes).every(function (checkbox) {
                    return checkbox.checked;
                });

                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = !allChecked;
                });

                updateSelectAllText();
            });

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateSelectAllText);
            });

            updateSelectAllText();
        }

        /*
         * Update notification
         */
        $('form').on('submit', function () {

            if (window.notyf) {

                window.notyf.open({
                    type: 'success',
                    message: 'Updating user...',
                    duration: 2000
                });

            }

        });

    });
</script>

@endpush