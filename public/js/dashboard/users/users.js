console.log("users.js loaded");
console.log(window.userConfig.indexUrl);
$(document).ready(function () {
    loadUsers();

    $("#search").on("keyup", function () {
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

        beforeSend: function () {
            console.log("Loading users...");
        },

        success: function (response) {
            console.log("Success");
            console.log(response);

            $("#usersTable").html(response);
        },

        error: function (xhr, status, error) {
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
$(document).on("submit", ".delete-form", function (e) {
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
