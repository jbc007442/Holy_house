$(function () {
    loadBooking();
});

function loadBooking() {
    $.ajax({
        url: window.bookingDetailsConfig.url,
        type: "GET",

        success: function (response) {
            renderBooking(response.booking);
        },

        error: function () {
            $("#bookingDetails").html(`
                <div class="rounded-xl border border-red-200 bg-red-50 p-8 text-center text-red-600">
                    Failed to load booking details.
                </div>
            `);
        },
    });
}

function renderBooking(booking) {
    const guest = booking.guests.length ? booking.guests[0] : null;

    let services = "";

    if (booking.services.length) {
        booking.services.forEach(function (service) {
            services += `
                <tr class="border-t">

                    <td class="px-4 py-3">
                        ${service.service_name ?? "-"}
                    </td>

                    <td class="px-4 py-3 text-center">

                        <span class="px-2 py-1 rounded-full text-xs ${
                            service.type === "chargeable"
                                ? "bg-red-100 text-red-700"
                                : "bg-green-100 text-green-700"
                        }">

                            ${service.type}

                        </span>

                    </td>

                    <td class="px-4 py-3 text-center">
                        ${service.quantity}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₹ ${Number(service.unit_price).toFixed(2)}
                    </td>

                    <td class="px-4 py-3 text-right font-semibold">
                        ₹ ${Number(service.total_amount).toFixed(2)}
                    </td>

                </tr>
            `;
        });
    } else {
        services = `
            <tr>

                <td colspan="5"
                    class="px-4 py-8 text-center text-zinc-500">

                    No services found.

                </td>

            </tr>
        `;
    }

    let payments = "";

    if (booking.payments.length) {
        booking.payments.forEach(function (payment) {
            payments += `
                <tr class="border-t">

                    <td class="px-4 py-3">
                        ${formatDate(payment.created_at)}
                    </td>

                    <td class="px-4 py-3">
                        ${payment.payment_method}
                    </td>

                    <td class="px-4 py-3 text-right">
                        ₹ ${Number(payment.amount).toFixed(2)}
                    </td>

                    <td class="px-4 py-3">
                        ${payment.reference_no ?? "-"}
                    </td>

                </tr>
            `;
        });
    } else {
        payments = `
            <tr>

                <td colspan="4"
                    class="px-4 py-8 text-center text-zinc-500">

                    No payments found.

                </td>

            </tr>
        `;
    }

    $("#bookingDetails").html(`

<div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between border-b bg-zinc-50 px-6 py-5">

        <div>

            <h2 class="text-2xl font-bold text-zinc-800">
                Booking Details
            </h2>

            <p class="text-sm text-zinc-500">
                Complete booking information
            </p>

        </div>

        <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

            ${booking.status}

        </span>

    </div>

    <!-- Booking Information -->

    <div class="border-b px-6 py-6">

        <table class="w-full text-sm">

            <tbody>

                <tr>

                    <td class="w-40 py-2 font-medium text-zinc-500">
                        Booking No
                    </td>

                    <td class="font-semibold">
                        ${booking.booking_no}
                    </td>

                    <td class="w-40 py-2 font-medium text-zinc-500">
                        Room
                    </td>

                    <td class="font-semibold">
                        ${booking.room.room_number}
                    </td>

                </tr>

                <tr>

                    <td class="py-2 font-medium text-zinc-500">
                        Building
                    </td>

                    <td>
                        ${booking.room.building?.name ?? "-"}

                    </td>

                    <td class="py-2 font-medium text-zinc-500">
                        Guest
                    </td>

                    <td>

                        ${guest ? guest.guest_name : "-"}

                    </td>

                </tr>

                <tr>

                    <td class="py-2 font-medium text-zinc-500">
                        Mobile
                    </td>

                    <td>

                        ${guest ? guest.mobile : "-"}

                    </td>

                    <td class="py-2 font-medium text-zinc-500">
                        Address
                    </td>

                    <td>

                        ${guest?.nationality ?? "-"}

                    </td>

                </tr>

                <tr>

                    <td class="py-2 font-medium text-zinc-500">
                        Check In
                    </td>

                    <td>

                        ${formatDate(booking.check_in)}

                    </td>

                    <td class="py-2 font-medium text-zinc-500">
                        Check Out
                    </td>

                    <td>

                        ${booking.check_out ? formatDate(booking.check_out) : "-"}

                    </td>

                </tr>

                <tr>

                    <td class="py-2 font-medium text-zinc-500">
                        Payment Status
                    </td>

                    <td>

                        ${booking.payment_status}

                    </td>

                    <td class="py-2 font-medium text-zinc-500">
                        Guests
                    </td>

                    <td>

                        ${booking.guest_count}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- Services -->

    <div class="px-6 py-5">

        <h3 class="mb-4 text-lg font-semibold">
            Guest Services
        </h3>

        <table class="w-full text-sm">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Service
                    </th>

                    <th class="px-4 py-3 text-center">
                        Type
                    </th>

                    <th class="px-4 py-3 text-center">
                        Qty
                    </th>

                    <th class="px-4 py-3 text-right">
                        Rate
                    </th>

                    <th class="px-4 py-3 text-right">
                        Amount
                    </th>

                </tr>

            </thead>

            <tbody>

                ${services}

            </tbody>

        </table>

    </div>

    <!-- Payments -->

    <div class="border-t px-6 py-5">

        <h3 class="mb-4 text-lg font-semibold">
            Payment History
        </h3>

        <table class="w-full text-sm">

            <thead class="bg-zinc-100">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Date
                    </th>

                    <th class="px-4 py-3">
                        Method
                    </th>

                    <th class="px-4 py-3 text-right">
                        Amount
                    </th>

                    <th class="px-4 py-3">
                        Reference
                    </th>

                </tr>

            </thead>

            <tbody>

                ${payments}

            </tbody>

        </table>

    </div>
</div>

`);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}
