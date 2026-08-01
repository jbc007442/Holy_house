let currentPage = 1;
let searchTimer = null;

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

    $("#searchBtn").on("click", function () {
        currentPage = 1;

        loadHistory();
    });

    $("#resetBtn").on("click", function () {
        $("#searchHistory").val("");

        $("#fromDate").val("");

        $("#toDate").val("");

        $("#buildingFilter").val("");

        currentPage = 1;

        loadHistory();
    });

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $(document).on("click", "#paginationContainer a", function (e) {
        e.preventDefault();

        let url = $(this).attr("href");

        if (!url) return;

        currentPage = new URL(url).searchParams.get("page");

        loadHistory();
    });
});

function loadHistory() {
    $("#historyTable").html(`

<tr>

<td colspan="9" class="py-12 text-center text-zinc-500">

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

            search: $("#searchHistory").val(),

            from: $("#fromDate").val(),

            to: $("#toDate").val(),

            building_id: $("#buildingFilter").val(),
        },

        success: function (response) {
            renderStatistics(response.statistics);

            renderTable(response.bookings);

            $("#paginationContainer").html(response.pagination.links);
        },

        error: function () {
            $("#historyTable").html(`

<tr>

<td colspan="9" class="text-center py-12 text-red-500">

Failed to load booking history.

</td>

</tr>

`);
        },
    });
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

function renderStatistics(stat) {
    $("#completedCount").text(stat.completed);

    $("#revenueAmount").text(
        "₹ " + Number(stat.revenue).toLocaleString("en-IN"),
    );

    $("#averageStay").text(stat.average_stay + " Days");

    $("#todayCheckout").text(stat.checkout_today);
}

/*
|--------------------------------------------------------------------------
| Table
|--------------------------------------------------------------------------
*/

function renderTable(bookings) {
    let html = "";

    if (bookings.length === 0) {
        html = `

<tr>

<td colspan="9" class="py-16 text-center text-zinc-500">

<i class="fa-solid fa-clock-rotate-left text-5xl mb-4"></i>

<br>

No Booking History Found.

</td>

</tr>

`;

        $("#historyTable").html(html);

        return;
    }

    bookings.forEach(function (booking) {
        let guest = booking.guests.length ? booking.guests[0] : null;

        let room = booking.room ?? {};

        let building = room.building ?? {};

        let viewUrl = window.bookingHistoryConfig.viewUrl.replace(
            ":id",
            booking.id,
        );

        html += `

<tr class="border-t hover:bg-zinc-50">

<td class="px-5 py-4 font-semibold">

${booking.booking_no}

</td>

<td class="px-5 py-4">

${guest ? guest.guest_name : "-"}

</td>

<td class="px-5 py-4">

${building.name ?? "-"}

</td>

<td class="px-5 py-4">

${room.room_number ?? "-"}

</td>

<td class="px-5 py-4">

${formatDate(booking.check_in)}

</td>

<td class="px-5 py-4">

${formatDate(booking.check_out)}

</td>

<td class="px-5 py-4 font-semibold text-green-600">

₹ ${Number(booking.total_amount).toLocaleString("en-IN")}

</td>

<td class="px-5 py-4 text-center">

<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">

Checked Out

</span>

</td>

<td class="px-5 py-4">

<div class="flex justify-end gap-2">

<a href="${viewUrl}"
class="w-9 h-9 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 flex items-center justify-center">

<i class="fa-solid fa-eye"></i>

</a>

<button
class="w-9 h-9 rounded-lg border border-zinc-200 hover:bg-zinc-100 flex items-center justify-center">

<i class="fa-solid fa-print"></i>

</button>

<button
class="w-9 h-9 rounded-lg border border-green-200 text-green-600 hover:bg-green-50 flex items-center justify-center">

<i class="fa-solid fa-download"></i>

</button>

</div>

</td>

</tr>

`;
    });

    $("#historyTable").html(html);
}

/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/

function formatDate(date) {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("en-IN", {
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
