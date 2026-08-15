let buildingsLoaded = false;

$(function () {
    loadDashboard();

    $("#revenueBuilding").on("change", function () {
        $("#revenueLabel").text($(this).find("option:selected").text());

        loadDashboard();
    });

    $(document).on("click", ".pagination-btn", function () {
        loadDashboard($(this).data("page"));
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

            if (response.loginHistory.length === 0) {
                html = `

                <tr>

                    <td colspan="4"
                        class="px-6 py-8 text-center text-zinc-500">

                        No login history found.

                    </td>

                </tr>

                `;
            } else {
                response.loginHistory.forEach(function (row) {
                    const loginTime = new Date(row.login_at).toLocaleString(
                        "en-IN",

                        {
                            timeZone: "Asia/Kolkata",

                            day: "2-digit",

                            month: "short",

                            year: "numeric",

                            hour: "2-digit",

                            minute: "2-digit",

                            hour12: true,
                        },
                    );

                    const logoutTime = row.logout_at
                        ? new Date(row.logout_at).toLocaleString(
                              "en-IN",

                              {
                                  timeZone: "Asia/Kolkata",

                                  day: "2-digit",

                                  month: "short",

                                  year: "numeric",

                                  hour: "2-digit",

                                  minute: "2-digit",

                                  hour12: true,
                              },
                          )
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
                                    ? `<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Online
                                       </span>`
                                    : `<span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700">
                                            Logged Out
                                       </span>`
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

            let pagination = "";

            if (response.pagination.last_page > 1) {
                pagination += `<div class="flex justify-end items-center gap-2">`;

                if (response.pagination.current_page > 1) {
                    pagination += `
                        <button
                            class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100"
                            data-page="${response.pagination.current_page - 1}">

                            Previous

                        </button>
                    `;
                }

                const current = response.pagination.current_page;
                const last = response.pagination.last_page;

                const start = Math.max(1, current - 2);
                const end = Math.min(last, current + 2);

                for (let i = start; i <= end; i++) {
                    pagination += `
                        <button
                            class="pagination-btn px-4 py-2 border rounded-lg ${
                                i === current
                                    ? "bg-blue-600 text-white"
                                    : "hover:bg-zinc-100"
                            }"
                            data-page="${i}">

                            ${i}

                        </button>
                    `;
                }

                if (current < last) {
                    pagination += `
                        <button
                            class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100"
                            data-page="${current + 1}">

                            Next

                        </button>
                    `;
                }

                pagination += `</div>`;
            }

            $("#paginationWrapper").html(pagination);
        },

        error: function (xhr) {
            console.error(xhr.responseText);

            window.notyf.error("Unable to load dashboard.");
        },
    });
}
