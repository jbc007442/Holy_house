@extends('dashboard.base')

@section('title', 'Manage Users')

@section('content')

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-zinc-800">
                    Manage Users
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    View and manage all system users.
                </p>
            </div>

            <a href="{{ route('dashboard.users.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">

                <i class="fa-solid fa-user-plus"></i>

                Add User

            </a>

        </div>

        <!-- Card -->

        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">

            <div class="p-4 border-b">

                <input type="text" id="search" placeholder="Search users..."
                    class="w-full rounded-lg border border-zinc-300 px-4 py-2">

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-zinc-50">

                        <tr>

                            <th class="px-4 py-3 text-left">Name</th>

                            <th class="px-4 py-3 text-left">Email</th>

                            <th class="px-4 py-3 text-center">Role</th>

                            <th class="px-4 py-3 text-center">Status</th>

                            <th class="px-4 py-3 text-center">Actions</th>

                        </tr>

                    </thead>

                    <tbody id="usersTable">

                        <tr>

                            <td colspan="5" class="text-center py-8 text-zinc-500">

                                Loading...

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
        $(document).ready(function() {
            loadUsers();

            $("#search").on("keyup", function() {
                loadUsers($(this).val());
            });
        });

        // Load Users
        function loadUsers(search = "") {
            $("#usersTable").html(`
        <tr>
            <td colspan="5" class="text-center py-8 text-zinc-500">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                Loading...
            </td>
        </tr>
    `);

            $.ajax({
                url: "{{ route('dashboard.users.index') }}",

                type: "GET",

                data: {
                    ajax: 1,
                    search: search,
                },

                beforeSend: function() {
                    console.log("Loading users...");
                },

                success: function(response) {
                    console.log("Success");
                    console.log(response);

                    $("#usersTable").html(response);
                },

                error: function(xhr, status, error) {
                    console.log("AJAX Error");
                    console.log("Status:", xhr.status);
                    console.log("Error:", error);
                    console.log(xhr.responseText);

                    $("#usersTable").html(`
                <tr>
                    <td colspan="5" class="text-center py-8 text-red-600">
                        Failed to load users.<br>
                        HTTP ${xhr.status}
                    </td>
                </tr>
            `);
                },
            });
        }

        // Delete Confirmation
        $(document).on("submit", ".delete-form", function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: "Delete User?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc2626",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
