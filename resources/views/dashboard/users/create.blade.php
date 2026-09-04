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

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
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

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Role
                        </label>

                        <select
                            name="role"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                            required
                        >

                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>
                                Super Admin
                            </option>

                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="receptionist" {{ old('role') === 'receptionist' ? 'selected' : '' }}>
                                Receptionist
                            </option>

                            <option value="housekeeping" {{ old('role') === 'housekeeping' ? 'selected' : '' }}>
                                Housekeeping
                            </option>

                            <option value="storemanager" {{ old('role') === 'storemanager' ? 'selected' : '' }}>
                                Store Manager
                            </option>

                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
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

                        <div class="flex items-center justify-between mb-2">

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

                        <div class="border border-zinc-300 rounded-lg overflow-hidden">

                            @forelse($buildings as $building)

                                <label
                                    class="flex items-center gap-3 px-4 py-3 border-b last:border-b-0 border-zinc-200 hover:bg-zinc-50 cursor-pointer"
                                >

                                    <input
                                        type="checkbox"
                                        name="building_ids[]"
                                        value="{{ $building->id }}"
                                        class="building-checkbox h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900"
                                        {{ in_array($building->id, old('building_ids', [])) ? 'checked' : '' }}
                                    >

                                    <div class="flex-1">

                                        <div class="text-sm font-medium text-zinc-800">
                                            {{ $building->name }}
                                        </div>

                                        @if($building->code)
                                            <div class="text-xs text-zinc-500 mt-0.5">
                                                Code: {{ $building->code }}
                                            </div>
                                        @endif

                                    </div>

                                </label>

                            @empty

                                <div class="px-4 py-6 text-center">

                                    <i class="fa-solid fa-building text-2xl text-zinc-300 mb-2"></i>

                                    <p class="text-sm text-zinc-500">
                                        No buildings available.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                        <p class="text-xs text-zinc-500 mt-2">
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

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                            required
                        >

                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
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

                        <label class="block text-sm font-medium text-zinc-700 mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                            required
                        >

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

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
                            required
                        >

                    </div>

                </div>

                <!-- Footer -->

                <div class="flex justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4 rounded-b-xl">

                    <a
                        href="{{ route('dashboard.users.index') }}"
                        class="rounded-lg border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 hover:bg-white"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-zinc-800"
                    >

                        <i class="fa-solid fa-user-plus mr-2"></i>

                        Create User

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const selectAllButton = document.getElementById('selectAllBuildings');
        const checkboxes = document.querySelectorAll('.building-checkbox');

        if (!selectAllButton || !checkboxes.length) {
            return;
        }

        selectAllButton.addEventListener('click', function () {

            const allChecked = Array.from(checkboxes)
                .every(function (checkbox) {
                    return checkbox.checked;
                });

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = !allChecked;
            });

            selectAllButton.textContent = allChecked
                ? 'Select All'
                : 'Deselect All';
        });

    });
</script>

@endpush