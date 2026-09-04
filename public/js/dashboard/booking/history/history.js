let currentPage = 1;
let searchTimer = null;

/*
|--------------------------------------------------------------------------
| Document Ready
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
    loadHistory();

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    $("#searchHistory").on("keyup", function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            currentPage = 1;

            loadHistory();
        }, 500);
    });

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    $("#fromDate, #toDate, #buildingFilter").on("change", function () {
        currentPage = 1;

        loadHistory();
    });

    /*
    |--------------------------------------------------------------------------
    | Search Button
    |--------------------------------------------------------------------------
    */

    $("#searchBtn").on("click", function () {
        currentPage = 1;

        loadHistory();
    });

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    $("#resetBtn").on("click", function () {
        $("#searchHistory").val("");

        $("#fromDate").val("");

        $("#toDate").val("");

        $("#buildingFilter").val("");

        $("#perPage").val("10");

        currentPage = 1;

        loadHistory();
    });

    /*
    |--------------------------------------------------------------------------
    | Per Page
    |--------------------------------------------------------------------------
    */

    $("#perPage").on("change", function () {
        currentPage = 1;

        loadHistory();
    });

    /*
|--------------------------------------------------------------------------
| Export Excel
|--------------------------------------------------------------------------
*/

    $("#exportExcelBtn").on("click", function (e) {
        e.preventDefault();

        const params = new URLSearchParams();

        const search = $("#searchHistory").val().trim();
        const from = $("#fromDate").val();
        const to = $("#toDate").val();
        const buildingId = $("#buildingFilter").val();

        if (search) {
            params.append("search", search);
        }

        if (from) {
            params.append("from", from);
        }

        if (to) {
            params.append("to", to);
        }

        if (buildingId) {
            params.append("building_id", buildingId);
        }

        const url =
            window.bookingHistoryConfig.exportUrl +
            (params.toString() ? "?" + params.toString() : "");

        window.location.href = url;
    });

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "click",
        "#paginationContainer .history-page-btn",
        function (e) {
            e.preventDefault();

            const page = Number($(this).data("page"));

            if (!page || page < 1) {
                return;
            }

            currentPage = page;

            loadHistory();
        },
    );
});

/*
|--------------------------------------------------------------------------
| Load History
|--------------------------------------------------------------------------
*/

function loadHistory() {
    $("#historyTable").html(`

        <tr>

            <td
                colspan="14"
                class="py-12 text-center text-zinc-500"
            >

                <i class="fa-solid fa-spinner fa-spin mr-2"></i>

                Loading Booking History...

            </td>

        </tr>

    `);

    $.ajax({
        url: window.bookingHistoryConfig.ajaxUrl,

        type: "GET",

        data: {
            page: currentPage,

            per_page: $("#perPage").val() || 10,

            search: $("#searchHistory").val(),

            from: $("#fromDate").val(),

            to: $("#toDate").val(),

            building_id: $("#buildingFilter").val(),
        },

        success: function (response) {
            renderStatistics(response.statistics);

            renderTable(response.bookings);

            renderPagination(response.pagination);
        },

        error: function (xhr) {
            console.error(xhr.responseText);

            $("#historyTable").html(`

                <tr>

                    <td
                        colspan="14"
                        class="py-12 text-center text-red-500"
                    >

                        <i class="fa-solid fa-circle-exclamation mr-2"></i>

                        Failed to load booking history.

                    </td>

                </tr>

            `);

            $("#paginationContainer").html("");
        },
    });
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

function renderStatistics(stat) {
    if (!stat) {
        return;
    }

    $("#completedCount").text(stat.completed ?? 0);

    $("#revenueAmount").text(
        "₹ " +
            Number(stat.revenue ?? 0).toLocaleString("en-IN", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }),
    );

    $("#averageStay").text(Number(stat.average_stay ?? 0) + " Days");

    $("#todayCheckout").text(stat.checkout_today ?? 0);
}

/*
|--------------------------------------------------------------------------
| Render Table
|--------------------------------------------------------------------------
*/

function renderTable(bookings) {
    let html = "";

    /*
    |--------------------------------------------------------------------------
    | No Bookings
    |--------------------------------------------------------------------------
    */

    if (!bookings || bookings.length === 0) {
        html = `

            <tr>

                <td
                    colspan="14"
                    class="py-16 text-center text-zinc-500"
                >

                    <i
                        class="fa-solid fa-clock-rotate-left text-5xl mb-4"
                    ></i>

                    <br>

                    No Booking History Found.

                </td>

            </tr>

        `;

        $("#historyTable").html(html);

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    bookings.forEach(function (booking) {
        /*
        |--------------------------------------------------------------------------
        | Guest
        |--------------------------------------------------------------------------
        */

        const guest =
            booking.guests && booking.guests.length ? booking.guests[0] : null;

        /*
        |--------------------------------------------------------------------------
        | Room
        |--------------------------------------------------------------------------
        */

        const room = booking.room ?? {};

        /*
        |--------------------------------------------------------------------------
        | Building
        |--------------------------------------------------------------------------
        */

        const building = room.building ?? {};

        /*
        |--------------------------------------------------------------------------
        | View URL
        |--------------------------------------------------------------------------
        */

        const viewUrl = window.bookingHistoryConfig.viewUrl.replace(
            ":id",
            booking.id,
        );

        /*
        |--------------------------------------------------------------------------
        | Total Days
        |--------------------------------------------------------------------------
        */

        const checkIn = new Date(booking.check_in);

        const checkOut = new Date(booking.check_out);

        let totalDays = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

        totalDays = Math.max(1, totalDays);

        /*
        |--------------------------------------------------------------------------
        | Bed Quantity
        |--------------------------------------------------------------------------
        */

        const bedQuantity = Number(booking.bed_quantity ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Bed Price
        |--------------------------------------------------------------------------
        */

        const bedPrice = Number(booking.bed_price ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Room Rent
        |--------------------------------------------------------------------------
        */

        const roomRent = Number(booking.room_rent ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Room Total
        |--------------------------------------------------------------------------
        */

        const totalAmount = Number(
            booking.total_amount ?? roomRent * totalDays,
        );

        /*
        |--------------------------------------------------------------------------
        | GST
        |--------------------------------------------------------------------------
        */

        const gst = Number(booking.gst ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        const grandTotal = Number(booking.grand_total ?? 0);

        /*
        |--------------------------------------------------------------------------
        | HTML
        |--------------------------------------------------------------------------
        */

        html += `

            <tr class="border-t hover:bg-zinc-50">

                <!-- Booking No -->

                <td class="px-5 py-4 font-semibold whitespace-nowrap">

                    ${escapeHtml(booking.booking_no ?? "-")}

                </td>


                <!-- Guest -->

                <td class="px-5 py-4 whitespace-nowrap">

                    ${guest ? escapeHtml(guest.guest_name ?? "-") : "-"}

                </td>


                <!-- Building -->

                <td class="px-5 py-4 whitespace-nowrap">

                    ${escapeHtml(building.name ?? "-")}

                </td>


                <!-- Room -->

                <td class="px-5 py-4 whitespace-nowrap">

                    ${escapeHtml(room.room_number ?? "-")}

                </td>


                <!-- Check In -->

                <td class="px-5 py-4 whitespace-nowrap">

                    ${formatDate(booking.check_in)}

                </td>


                <!-- Check Out -->

                <td class="px-5 py-4 whitespace-nowrap">

                    ${formatDate(booking.check_out)}

                </td>


                <!-- Total Days -->

                <td class="px-5 py-4 text-center font-semibold">

                    ${totalDays}

                </td>


                <!-- Beds -->

                <td class="px-5 py-4 text-center font-semibold">

                    ${bedQuantity > 0 ? bedQuantity : "-"}

                </td>


                <!-- Bed Price -->

                <td class="px-5 py-4 text-right whitespace-nowrap">

                    ${bedPrice > 0 ? formatCurrency(bedPrice) : "-"}

                </td>


                <!-- Total -->

                <td class="px-5 py-4 font-semibold text-green-600 whitespace-nowrap">

                    ${formatCurrency(totalAmount)}

                </td>


                <!-- GST -->

                <td class="px-5 py-4 text-right whitespace-nowrap">

                    ${gst > 0 ? formatCurrency(gst) : "-"}

                </td>


                <!-- Grand Total -->

                <td class="px-5 py-4 text-right font-bold text-green-700 whitespace-nowrap">

                    ${formatCurrency(grandTotal)}

                </td>


                <!-- Status -->

                <td class="px-5 py-4 text-center">

                    <span
                        class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium whitespace-nowrap"
                    >

                        Checked Out

                    </span>

                </td>


                <!-- Action -->

                <td class="px-5 py-4">

                    <div class="flex justify-end gap-2">

                        <a
                            href="${viewUrl}"
                            class="w-9 h-9 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 flex items-center justify-center"
                            title="View Booking"
                        >

                            <i class="fa-solid fa-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

        `;
    });

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    $("#historyTable").html(html);
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

function renderPagination(pagination) {
    if (!pagination) {
        $("#paginationContainer").html("");

        return;
    }

    const current = Number(pagination.current_page ?? 1);

    const last = Number(pagination.last_page ?? 1);

    const total = Number(pagination.total ?? 0);

    const from = Number(pagination.from ?? 0);

    const to = Number(pagination.to ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Pagination Info
    |--------------------------------------------------------------------------
    */

    const info = `

        <div class="text-sm text-zinc-500">

            Showing

            <span class="font-semibold text-zinc-700">
                ${from}
            </span>

            to

            <span class="font-semibold text-zinc-700">
                ${to}
            </span>

            of

            <span class="font-semibold text-zinc-700">
                ${total}
            </span>

            entries

        </div>

    `;

    /*
    |--------------------------------------------------------------------------
    | No Pagination Needed
    |--------------------------------------------------------------------------
    */

    if (total === 0) {
        $("#paginationContainer").html(info);

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Page Numbers
    |--------------------------------------------------------------------------
    */

    let pages = "";

    let startPage = Math.max(1, current - 2);

    let endPage = Math.min(last, current + 2);

    /*
    |--------------------------------------------------------------------------
    | First Page
    |--------------------------------------------------------------------------
    */

    if (startPage > 1) {
        pages += `

            <button
                type="button"
                class="history-page-btn h-9 min-w-9 rounded-lg border border-zinc-300 bg-white px-3 text-sm hover:bg-zinc-100"
                data-page="1"
            >
                1
            </button>

        `;

        if (startPage > 2) {
            pages += `

                <span class="px-1 text-zinc-400">
                    ...
                </span>

            `;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Page Numbers
    |--------------------------------------------------------------------------
    */

    for (let page = startPage; page <= endPage; page++) {
        pages += `

            <button
                type="button"
                class="history-page-btn h-9 min-w-9 rounded-lg border px-3 text-sm
                ${
                    page === current
                        ? "border-amber-500 bg-amber-500 text-white"
                        : "border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100"
                }"
                data-page="${page}"
            >

                ${page}

            </button>

        `;
    }

    /*
    |--------------------------------------------------------------------------
    | Last Page
    |--------------------------------------------------------------------------
    */

    if (endPage < last) {
        if (endPage < last - 1) {
            pages += `

                <span class="px-1 text-zinc-400">
                    ...
                </span>

            `;
        }

        pages += `

            <button
                type="button"
                class="history-page-btn h-9 min-w-9 rounded-lg border border-zinc-300 bg-white px-3 text-sm hover:bg-zinc-100"
                data-page="${last}"
            >

                ${last}

            </button>

        `;
    }

    /*
    |--------------------------------------------------------------------------
    | Previous
    |--------------------------------------------------------------------------
    */

    const previousButton = `

        <button
            type="button"
            class="history-page-btn inline-flex items-center gap-1 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 disabled:opacity-50 disabled:cursor-not-allowed"
            data-page="${current - 1}"
            ${current <= 1 ? "disabled" : ""}
        >

            <i class="fa-solid fa-chevron-left"></i>

            Previous

        </button>

    `;

    /*
    |--------------------------------------------------------------------------
    | Next
    |--------------------------------------------------------------------------
    */

    const nextButton = `

        <button
            type="button"
            class="history-page-btn inline-flex items-center gap-1 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 disabled:opacity-50 disabled:cursor-not-allowed"
            data-page="${current + 1}"
            ${current >= last ? "disabled" : ""}
        >

            Next

            <i class="fa-solid fa-chevron-right"></i>

        </button>

    `;

    /*
    |--------------------------------------------------------------------------
    | Final Pagination HTML
    |--------------------------------------------------------------------------
    */

    $("#paginationContainer").html(`

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            ${info}

            <div class="flex flex-wrap items-center justify-center gap-2">

                ${previousButton}

                ${pages}

                ${nextButton}

            </div>

        </div>

    `);
}

/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/

function formatDate(date) {
    if (!date) {
        return "-";
    }

    const parsedDate = new Date(date);

    if (isNaN(parsedDate.getTime())) {
        return "-";
    }

    return parsedDate.toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}

/*
|--------------------------------------------------------------------------
| Format Currency
|--------------------------------------------------------------------------
*/

function formatCurrency(amount) {
    amount = Number(amount ?? 0);

    return (
        "₹ " +
        amount.toLocaleString("en-IN", {
            minimumFractionDigits: 2,

            maximumFractionDigits: 2,
        })
    );
}

/*
|--------------------------------------------------------------------------
| Escape HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
