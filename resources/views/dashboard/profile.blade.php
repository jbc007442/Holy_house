@extends('dashboard.base')

@section('title', 'My Profile')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->

    <div>

        <h1 class="text-3xl font-bold text-zinc-800">

            My Profile

        </h1>

        <p class="mt-1 text-zinc-500">

            View your account information and profile details.

        </p>

    </div>

    <!-- Profile Card -->

    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">

        <!-- Cover -->

        <div class="h-40 bg-gray-100"></div>

        <!-- Profile -->

        <div class="px-8 pb-8">

            <div class="-mt-16 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5">

                    <img
                        id="avatar"
                        src="https://ui-avatars.com/api/?name=User&background=f59e0b&color=fff&size=200"
                        class="h-32 w-32 rounded-full border-4 border-white shadow-lg">

                    <div class="pb-2">

                        <h2
                            id="userName"
                            class="text-3xl font-bold text-zinc-800">

                            Loading...

                        </h2>

                        <p
                            id="userEmail"
                            class="mt-1 text-zinc-500">

                            --

                        </p>

                    </div>

                </div>

                <div id="userStatus">

                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-4 py-2 text-sm">

                        Loading...

                    </span>

                </div>

            </div>

        </div>

    </div>

    <!-- Information -->

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- Name -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Full Name

            </p>

            <h3
                id="profileName"
                class="mt-2 text-xl font-semibold text-zinc-800">

                --

            </h3>

        </div>

        <!-- Email -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Email Address

            </p>

            <h3
                id="profileEmail"
                class="mt-2 text-xl font-semibold text-zinc-800 break-all">

                --

            </h3>

        </div>

        <!-- Role -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Role

            </p>

            <h3
                id="profileRole"
                class="mt-2 text-xl font-semibold text-zinc-800">

                --

            </h3>

        </div>

        <!-- Status -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Account Status

            </p>

            <div
                id="profileStatus"
                class="mt-3">

                --

            </div>

        </div>

        <!-- Created -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Account Created

            </p>

            <h3
                id="createdAt"
                class="mt-2 text-xl font-semibold text-zinc-800">

                --

            </h3>

        </div>

        <!-- Updated -->

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Last Updated

            </p>

            <h3
                id="updatedAt"
                class="mt-2 text-xl font-semibold text-zinc-800">

                --

            </h3>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

window.profile = {

    ajaxUrl: "{{ route('dashboard.profile') }}"

};

</script>

<script src="{{ asset('js/dashboard/profile.js') }}"></script>

@endpush