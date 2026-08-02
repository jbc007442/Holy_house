let currentPage = 1;
let searchTimer = null;

$(document).ready(function () {
    loadCurrentStays();

    // Search
    $("#searchBooking").on("keyup", function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            currentPage = 1;
            loadCurrentStays();
        }, 500);
    });

    // Building Filter
    $("#buildingFilter").on("change", function () {
        currentPage = 1;
        loadCurrentStays();
    });

    // Floor Filter
    $("#floorFilter").on("change", function () {
        currentPage = 1;
        loadCurrentStays();
    });

    // Pagination
    $(document).on("click", "#paginationContainer a", function (e) {
        e.preventDefault();

        let url = $(this).attr("href");

        if (!url) return;

        currentPage = new URL(url).searchParams.get("page");

        loadCurrentStays();
    });
});

function loadCurrentStays() {
    $("#currentStayTable").html(`
        <tr>
            <td colspan="9" class="text-center py-10 text-zinc-500">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                Loading...
            </td>
        </tr>
    `);

    $.ajax({
        url: window.currentStayConfig.ajaxUrl,

        type: "GET",

        data: {
            page: currentPage,

            search: $("#searchBooking").val(),

            building_id: $("#buildingFilter").val(),

            floor: $("#floorFilter").val(),
        },

        success: function (response) {
            renderStatistics(response.statistics);

            renderTable(response.bookings);

            $("#paginationContainer").html(response.pagination.links);
        },

        error: function () {
            $("#currentStayTable").html(`
                <tr>
                    <td colspan="9"
                        class="text-center py-10 text-red-500">

                        Failed to load current stays.

                    </td>
                </tr>
            `);
        },
    });
}

function renderStatistics(stat) {
    $("#guestCount").text(stat.guest_count);

    $("#runningRooms").text(stat.running_rooms);

    $("#checkoutToday").text(stat.checkout_today);

    $("#totalBalance").text(
        "₹ " + Number(stat.total_balance).toLocaleString("en-IN"),
    );
}

function renderTable(bookings) {
    let html = "";

    if (bookings.length === 0) {
        html = `
            <tr>

                <td colspan="9"
                    class="text-center py-16 text-zinc-500">

                    <i class="fa-solid fa-bed text-4xl mb-3"></i>

                    <br>

                    No current stays found.

                </td>

            </tr>
        `;

        $("#currentStayTable").html(html);

        return;
    }

    bookings.forEach(function (booking) {
        let guest = booking.guests.length ? booking.guests[0] : null;
        console.log(booking);
        console.log(booking.guests);

        let room = booking.room ?? {};

        let building = room.building ?? {};

        // let viewUrl = window.currentStayConfig.viewUrl.replace(
        //     ":id",
        //     booking.id,
        // );

        let editUrl = window.currentStayConfig.editUrl.replace(
            ":id",
            booking.id,
        );

        let checkoutUrl = window.currentStayConfig.checkoutUrl.replace(
            ":id",
            booking.id,
        );
        let serviceUrl = window.currentStayConfig.serviceUrl.replace(
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

${guest ? guest.mobile : "-"}

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

<td class="px-5 py-4 font-semibold text-red-600">

₹ ${Number(booking.balance_amount).toLocaleString("en-IN")}

</td>

<td class="px-5 py-4 text-center">

<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">

Checked In

</span>

</td>

<td class="px-5 py-4">

<div class="flex justify-end items-center gap-2">

<a href="${editUrl}"
class="w-9 h-9 rounded-lg border border-zinc-200 text-zinc-700 hover:bg-zinc-100 flex items-center justify-center">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="${serviceUrl}"
class="w-9 h-9 rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 flex items-center justify-center">

<i class="fa-solid fa-bell-concierge"></i>

</a>

<form
action="${checkoutUrl}"
method="POST">

<input
type="hidden"
name="_token"
value="${window.currentStayConfig.csrf}">

<input
type="hidden"
name="_method"
value="PATCH">

<button
class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">

Check Out

</button>

</form>

</div>

</td>

</tr>

`;
    });

    $("#currentStayTable").html(html);
}
/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/

function formatDate(date) {

    if (!date) return '-';

    return new Date(date).toLocaleDateString('en-IN', {

        day: '2-digit',
        month: 'short',
        year: 'numeric'

    });

}

/*
|--------------------------------------------------------------------------
| Currency Formatter
|--------------------------------------------------------------------------
*/

function formatCurrency(amount) {

    amount = Number(amount ?? 0);

    return '₹ ' + amount.toLocaleString('en-IN', {

        minimumFractionDigits: 2,
        maximumFractionDigits: 2

    });

}

/*
|--------------------------------------------------------------------------
| Service Button
|--------------------------------------------------------------------------
*/

$(document).on('click', '.serviceBtn', function () {

    let bookingId = $(this).data('id');

    openServicesModal(bookingId);

});

/*
|--------------------------------------------------------------------------
| Open Services Modal
|--------------------------------------------------------------------------
*/

function openServicesModal(bookingId) {

    $('#servicesModal')
        .removeClass('hidden')
        .addClass('flex');

    $('#servicesModal')
        .attr('data-booking-id', bookingId);

}

/*
|--------------------------------------------------------------------------
| Close Services Modal
|--------------------------------------------------------------------------
*/

function closeServicesModal() {

    $('#servicesModal')
        .removeClass('flex')
        .addClass('hidden');

}

/*
|--------------------------------------------------------------------------
| Close Modal On Outside Click
|--------------------------------------------------------------------------
*/

$(document).on('click', function (e) {

    if ($(e.target).attr('id') === 'servicesModal') {

        closeServicesModal();

    }

});

/*
|--------------------------------------------------------------------------
| Close Modal On ESC
|--------------------------------------------------------------------------
*/

$(document).keyup(function (e) {

    if (e.key === 'Escape') {

        closeServicesModal();

    }

});

/*
|--------------------------------------------------------------------------
| Checkout Confirmation
|--------------------------------------------------------------------------
*/

$(document).on('submit', '.checkoutForm', function (e) {

    if (!confirm('Are you sure you want to check out this guest?')) {

        e.preventDefault();

    }

});

/*
|--------------------------------------------------------------------------
| Reset Filters
|--------------------------------------------------------------------------
*/

function resetFilters() {

    $('#searchBooking').val('');

    $('#buildingFilter').val('');

    $('#floorFilter').val('');

    currentPage = 1;

    loadCurrentStays();

}

/*
|--------------------------------------------------------------------------
| Refresh List
|--------------------------------------------------------------------------
*/

function refreshCurrentStays() {

    loadCurrentStays();

}

/*
|--------------------------------------------------------------------------
| Auto Refresh Every 60 Seconds
|--------------------------------------------------------------------------
*/

setInterval(function () {

    loadCurrentStays();

}, 60000);

/*
|--------------------------------------------------------------------------
| Loading State
|--------------------------------------------------------------------------
*/

function showLoading() {

    $('#currentStayTable').html(`

        <tr>

            <td colspan="9" class="text-center py-12 text-zinc-500">

                <i class="fa-solid fa-spinner fa-spin text-xl mr-2"></i>

                Loading Current Stays...

            </td>

        </tr>

    `);

}

/*
|--------------------------------------------------------------------------
| Empty State
|--------------------------------------------------------------------------
*/

function showEmptyState() {

    $('#currentStayTable').html(`

        <tr>

            <td colspan="9" class="py-16 text-center">

                <i class="fa-solid fa-bed text-5xl text-zinc-300 mb-4"></i>

                <div class="text-zinc-500 font-medium">

                    No Current Stays Found

                </div>

            </td>

        </tr>

    `);

}

/*
|--------------------------------------------------------------------------
| AJAX Error
|--------------------------------------------------------------------------
*/

function showAjaxError() {

    $('#currentStayTable').html(`

        <tr>

            <td colspan="9" class="py-16 text-center text-red-500">

                <i class="fa-solid fa-circle-exclamation text-4xl mb-3"></i>

                <br>

                Failed to load current stays.

            </td>

        </tr>

    `);

}