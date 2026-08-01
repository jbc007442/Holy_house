@extends('dashboard.base')

@section('title', 'Login Histories')

@section('content')

<div class="space-y-6">

    <!-- Header -->

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-zinc-800">
                Login Histories
            </h1>

            <p class="mt-1 text-sm text-zinc-500">
                Monitor user login sessions and security activities.
            </p>

        </div>

        <button
            id="refreshHistory"
            class="rounded-xl border border-zinc-300 px-5 py-2.5 hover:bg-zinc-100">

            <i class="fa-solid fa-rotate-right mr-2"></i>

            Refresh

        </button>

    </div>

    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Today's Logins

            </p>

            <h2
                id="todayLogins"
                class="mt-2 text-4xl font-bold text-emerald-600">

                0

            </h2>

        </div>

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Online Users

            </p>

            <h2
                id="onlineUsers"
                class="mt-2 text-4xl font-bold text-blue-600">

                0

            </h2>

        </div>

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Today's Logouts

            </p>

            <h2
                id="todayLogouts"
                class="mt-2 text-4xl font-bold text-orange-600">

                0

            </h2>

        </div>

        <div class="rounded-2xl border bg-white p-6">

            <p class="text-sm text-zinc-500">

                Total Sessions

            </p>

            <h2
                id="totalLogins"
                class="mt-2 text-4xl font-bold text-zinc-800">

                0

            </h2>

        </div>

    </div>

    <!-- Filters -->

    <div class="rounded-2xl border bg-white p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

            <!-- Search -->

            <div class="xl:col-span-2">

                <label class="block text-sm font-medium text-zinc-700 mb-2">

                    Search

                </label>

                <div class="relative">

                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"></i>

                    <input
                        type="text"
                        id="search"
                        placeholder="User, email, browser, IP..."
                        class="w-full rounded-xl border border-zinc-300 py-3 pl-11 pr-4">

                </div>

            </div>

            <!-- Status -->

            <div>

                <label class="block text-sm font-medium text-zinc-700 mb-2">

                    Status

                </label>

                <select
                    id="status"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                    <option value="">All</option>
                    <option value="online">Online</option>
                    <option value="logout">Logged Out</option>

                </select>

            </div>

            <!-- Browser -->

            <div>

                <label class="block text-sm font-medium text-zinc-700 mb-2">

                    Browser

                </label>

                <select
                    id="browser"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                    <option value="">All</option>
                    <option>Chrome</option>
                    <option>Firefox</option>
                    <option>Safari</option>
                    <option>Edge</option>

                </select>

            </div>

            <!-- Platform -->

            <div>

                <label class="block text-sm font-medium text-zinc-700 mb-2">

                    Platform

                </label>

                <select
                    id="platform"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3">

                    <option value="">All</option>
                    <option>Windows</option>
                    <option>macOS</option>
                    <option>Linux</option>
                    <option>Android</option>
                    <option>iOS</option>

                </select>

            </div>

            <!-- Reset -->

            <div class="flex items-end">

                <button
                    id="resetFilters"
                    class="w-full rounded-xl border border-zinc-300 py-3 hover:bg-zinc-100">

                    <i class="fa-solid fa-filter-circle-xmark mr-2"></i>

                    Reset

                </button>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="overflow-hidden rounded-2xl border bg-white">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-zinc-50">

                    <tr>

                        <th class="px-5 py-4 text-left font-semibold">
                            User
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            IP Address
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            Browser
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            Platform
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Device
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Login Time
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Logout Time
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody id="loginHistoryTable">

                    <tr>

                        <td colspan="8" class="py-14 text-center text-zinc-500">

                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                            Loading login history...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(function () {

    loadLoginHistory();

    $("#search").on("keyup", debounce(loadLoginHistory, 400));

    $("#status").on("change", loadLoginHistory);

    $("#browser").on("change", loadLoginHistory);

    $("#platform").on("change", loadLoginHistory);

    $("#refreshHistory").on("click", loadLoginHistory);

    $("#resetFilters").on("click", function () {

        $("#search").val("");

        $("#status").val("");

        $("#browser").val("");

        $("#platform").val("");

        loadLoginHistory();

    });

});

/*
|--------------------------------------------------------------------------
| Load Login History
|--------------------------------------------------------------------------
*/

function loadLoginHistory() {

    loadingState();

    $.ajax({

        url: "{{ route('dashboard.login-history.index') }}",

        type: "GET",

        data: {

            ajax: 1,

            search: $("#search").val(),

            status: $("#status").val(),

            browser: $("#browser").val(),

            platform: $("#platform").val(),

        },

        success: function (response) {

            $("#loginHistoryTable").html(response.html);

            updateCards(response.stats);

        },

        error: function () {

            $("#loginHistoryTable").html(`

                <tr>

                    <td colspan="8"
                        class="py-16 text-center text-red-600">

                        Failed to load login history.

                    </td>

                </tr>

            `);

        }

    });

}

/*
|--------------------------------------------------------------------------
| Dashboard Cards
|--------------------------------------------------------------------------
*/

function updateCards(stats) {

    $("#todayLogins").text(stats.todayLogins);

    $("#todayLogouts").text(stats.todayLogouts);

    $("#onlineUsers").text(stats.onlineUsers);

    $("#totalLogins").text(stats.totalLogins);

}

/*
|--------------------------------------------------------------------------
| Loading
|--------------------------------------------------------------------------
*/

function loadingState() {

    $("#loginHistoryTable").html(`

        <tr>

            <td colspan="8"
                class="py-16 text-center text-zinc-500">

                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                Loading login history...

            </td>

        </tr>

    `);

}

/*
|--------------------------------------------------------------------------
| Debounce Search
|--------------------------------------------------------------------------
*/

function debounce(callback, delay = 300) {

    let timer;

    return function () {

        clearTimeout(timer);

        timer = setTimeout(callback, delay);

    };

}

</script>

@endpush