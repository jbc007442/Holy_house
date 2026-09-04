let buildingsLoaded = false;

$(function () {
    loadDashboard(1);

    $("#revenueBuilding").on("change", function () {
        $("#revenueLabel").text($(this).find("option:selected").text());

        // Reset pagination when building changes
        loadDashboard(1);
    });

    $(document).on("click", ".pagination-btn", function () {
        const page = $(this).data("page");

        if (!page) {
            return;
        }

        loadDashboard(page);
    });
});

function loadDashboard(page = 1) {
    $.ajax({
        url: window.dashboard.ajaxUrl,

        type: "GET",

        data: {
            page: page,
            building_id: $("#revenueBuilding").val(),
        },

        beforeSend: function () {
            $("#loginHistoryTable").html(`
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-zinc-500">
                        Loading...
                    </td>
                </tr>
            `);
        },

        success: function (response) {
            /*
            |--------------------------------------------------------------------------
            | Buildings Dropdown
            |--------------------------------------------------------------------------
            */

            if (!buildingsLoaded && response.buildingList) {
                const select = $("#revenueBuilding");

                response.buildingList.forEach(function (building) {
                    select.append(`
                        <option value="${building.id}">
                            ${building.name}
                        </option>
                    `);
                });

                buildingsLoaded = true;
            }

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            const stats = response.stats;

            $("#totalBuildings").text(stats.buildings);

            $("#totalRooms").text(stats.rooms);

            $("#availableRooms").text(stats.available_rooms);

            $("#runningRooms").text(stats.running_rooms);

            $("#todayCheckout").text(stats.today_checkout);

            $("#totalBookings").text(stats.bookings);

            $("#totalUsers").text(stats.users);

            $("#totalRevenue").text(
                "₹" +
                    Number(stats.revenue || 0).toLocaleString("en-IN", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }),
            );

            /*
            |--------------------------------------------------------------------------
            | Login History
            |--------------------------------------------------------------------------
            */

            let html = "";

            if (!response.loginHistory || response.loginHistory.length === 0) {
                html = `
                    <tr>
                        <td
                            colspan="4"
                            class="px-6 py-8 text-center text-zinc-500"
                        >
                            No login history found.
                        </td>
                    </tr>
                `;
            } else {
                response.loginHistory.forEach(function (row) {
                    const loginTime = row.login_at
                        ? new Date(row.login_at).toLocaleString("en-IN", {
                              timeZone: "Asia/Kolkata",
                              day: "2-digit",
                              month: "short",
                              year: "numeric",
                              hour: "2-digit",
                              minute: "2-digit",
                              hour12: true,
                          })
                        : "-";

                    const logoutTime = row.logout_at
                        ? new Date(row.logout_at).toLocaleString("en-IN", {
                              timeZone: "Asia/Kolkata",
                              day: "2-digit",
                              month: "short",
                              year: "numeric",
                              hour: "2-digit",
                              minute: "2-digit",
                              hour12: true,
                          })
                        : "-";

                    html += `
                        <tr class="border-t border-zinc-200 hover:bg-zinc-50">

                            <td class="px-6 py-4">
                                ${row.user?.name ?? "-"}
                            </td>

                            <td class="px-6 py-4">
                                ${loginTime}
                            </td>

                            <td class="px-6 py-4">
                                ${logoutTime}
                            </td>

                            <td class="px-6 py-4 text-center">

                                ${
                                    row.status === "login"
                                        ? `
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                                Online
                                            </span>
                                        `
                                        : `
                                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700">
                                                Logged Out
                                            </span>
                                        `
                                }

                            </td>

                        </tr>
                    `;
                });
            }

            $("#loginHistoryTable").html(html);

            /*
            |--------------------------------------------------------------------------
            | Pagination
            |--------------------------------------------------------------------------
            */

            renderPagination(response.pagination);
        },

        error: function (xhr) {
            console.error(xhr.responseText);

            $("#loginHistoryTable").html(`
                <tr>
                    <td
                        colspan="4"
                        class="px-6 py-8 text-center text-red-500"
                    >
                        Unable to load login history.
                    </td>
                </tr>
            `);

            $("#paginationWrapper").html("");

            window.notyf.error("Unable to load dashboard.");
        },
    });
}

/*
|--------------------------------------------------------------------------
| Render Pagination
|--------------------------------------------------------------------------
*/

function renderPagination(data) {
    let pagination = "";

    const current = Number(data.current_page);
    const last = Number(data.last_page);
    const total = Number(data.total);
    const perPage = Number(data.per_page);

    if (last <= 1) {
        $("#paginationWrapper").html("");
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Showing X to Y
    |--------------------------------------------------------------------------
    */

    const from = (current - 1) * perPage + 1;
    const to = Math.min(current * perPage, total);

    pagination += `
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="text-sm text-zinc-500">
                Showing
                <span class="font-medium text-zinc-700">
                    ${from}
                </span>
                to
                <span class="font-medium text-zinc-700">
                    ${to}
                </span>
                of
                <span class="font-medium text-zinc-700">
                    ${total}
                </span>
                results
            </div>

            <div class="flex items-center gap-1">
    `;

    /*
    |--------------------------------------------------------------------------
    | Previous
    |--------------------------------------------------------------------------
    */

    if (current > 1) {
        pagination += `
            <button
                type="button"
                class="pagination-btn inline-flex h-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-600 hover:bg-zinc-50"
                data-page="${current - 1}"
            >
                <i class="fa-solid fa-chevron-left mr-1 text-xs"></i>
                Previous
            </button>
        `;
    } else {
        pagination += `
            <button
                type="button"
                disabled
                class="inline-flex h-9 cursor-not-allowed items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 px-3 text-sm text-zinc-300"
            >
                <i class="fa-solid fa-chevron-left mr-1 text-xs"></i>
                Previous
            </button>
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | Page Numbers
    |--------------------------------------------------------------------------
    */

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    // First page
    if (start > 1) {
        pagination += `
            <button
                type="button"
                class="pagination-btn inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-600 hover:bg-zinc-50"
                data-page="1"
            >
                1
            </button>
        `;

        if (start > 2) {
            pagination += `
                <span class="inline-flex h-9 min-w-9 items-center justify-center text-zinc-400">
                    ...
                </span>
            `;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Current Page Range
    |--------------------------------------------------------------------------
    */

    for (let i = start; i <= end; i++) {
        if (i === current) {
            pagination += `
                <button
                    type="button"
                    class="pagination-btn inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-blue-600 bg-blue-600 px-3 text-sm font-medium text-white"
                    data-page="${i}"
                >
                    ${i}
                </button>
            `;
        } else {
            pagination += `
                <button
                    type="button"
                    class="pagination-btn inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-600 hover:bg-zinc-50"
                    data-page="${i}"
                >
                    ${i}
                </button>
            `;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Last Page
    |--------------------------------------------------------------------------
    */

    if (end < last) {
        if (end < last - 1) {
            pagination += `
                <span class="inline-flex h-9 min-w-9 items-center justify-center text-zinc-400">
                    ...
                </span>
            `;
        }

        pagination += `
            <button
                type="button"
                class="pagination-btn inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-600 hover:bg-zinc-50"
                data-page="${last}"
            >
                ${last}
            </button>
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | Next
    |--------------------------------------------------------------------------
    */

    if (current < last) {
        pagination += `
            <button
                type="button"
                class="pagination-btn inline-flex h-9 items-center justify-center rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-600 hover:bg-zinc-50"
                data-page="${current + 1}"
            >
                Next
                <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
            </button>
        `;
    } else {
        pagination += `
            <button
                type="button"
                disabled
                class="inline-flex h-9 cursor-not-allowed items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 px-3 text-sm text-zinc-300"
            >
                Next
                <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
            </button>
        `;
    }

    pagination += `
            </div>
        </div>
    `;

    $("#paginationWrapper").html(pagination);
}