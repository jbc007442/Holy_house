$(function () {
    loadProfile();
});

function loadProfile() {
    $.ajax({
        url: window.profile.ajaxUrl,
        type: "GET",

        success: function (response) {
            const user = response.user;

            $("#avatar").attr(
                "src",
                "https://ui-avatars.com/api/?name=" +
                    encodeURIComponent(user.name) +
                    "&background=f59e0b&color=fff&size=150",
            );

            $("#userName").text(user.name);
            $("#profileName").text(user.name);

            $("#userEmail").text(user.email);
            $("#profileEmail").text(user.email);

            $("#userRole").text(capitalize(user.role));
            $("#profileRole").text(capitalize(user.role));

            $("#userStatus").html(
                user.status === "active"
                    ? '<span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Active</span>'
                    : '<span class="px-3 py-1 rounded-full bg-red-100 text-red-700">Inactive</span>',
            );

            $("#profileStatus").html(
                user.status === "active"
                    ? '<span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Active</span>'
                    : '<span class="px-3 py-1 rounded-full bg-red-100 text-red-700">Inactive</span>',
            );

            $("#createdAt").text(formatDate(user.created_at));

            $("#updatedAt").text(formatDate(user.updated_at));
        },

        error: function () {
            window.notyf.error("Unable to load profile.");
        },
    });
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
}

function capitalize(text) {
    if (!text) return "-";

    return text.charAt(0).toUpperCase() + text.slice(1);
}
