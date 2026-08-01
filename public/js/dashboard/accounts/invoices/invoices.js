let currentPage = 1;

document.addEventListener("DOMContentLoaded", function () {
    loadInvoices();

    $("#search").on(
        "keyup",
        debounce(function () {
            currentPage = 1;
            loadInvoices();
        }, 400),
    );

    $("#from, #to").on("change", function () {
        currentPage = 1;
        loadInvoices();
    });

    $("#resetBtn").on("click", function () {
        $("#search").val("");
        $("#from").val("");
        $("#to").val("");

        currentPage = 1;

        loadInvoices();
    });
});

function loadInvoices(page = 1) {
    currentPage = page;

    $.ajax({
        url: window.invoiceConfig.ajaxUrl,

        method: "GET",

        data: {
            page: page,

            search: $("#search").val(),

            from: $("#from").val(),

            to: $("#to").val(),
        },

        success: function (response) {
            renderStatistics(response.statistics);

            renderTable(response.invoices);

            $("#pagination").html(response.pagination.links);

            bindPagination();
        },

        error: function (xhr) {
            console.error(xhr.responseText);
        },
    });
}

function renderStatistics(stats) {
    $("#totalInvoice").text(stats.total_invoice);

    $("#totalRevenue").text(
        "₹" +
            Number(stats.revenue).toLocaleString("en-IN", {
                minimumFractionDigits: 2,
            }),
    );

    $("#totalTax").text(
        "₹" +
            Number(stats.tax).toLocaleString("en-IN", {
                minimumFractionDigits: 2,
            }),
    );

    $("#thisMonth").text(stats.this_month);
}

function renderTable(invoices) {
    let html = "";

    if (!invoices || invoices.length === 0) {
        html = `
        <tr>
            <td colspan="6" class="text-center py-10 text-zinc-500">
                No invoices found.
            </td>
        </tr>`;

        $("#invoiceTable").html(html);

        return;
    }

    invoices.forEach(function (invoice) {
        const booking = invoice.booking ?? {};

        const roomRent = Number(booking.room_rent ?? 0);

        let chargeable = 0;

        if (booking.services) {
            booking.services.forEach(function (service) {
                if (service.type === "chargeable") {
                    chargeable += Number(service.total_amount);
                }
            });
        }

        const subtotal = roomRent + chargeable;

        const gst = subtotal * 0.05;

        const grandTotal = subtotal + gst;

        const guest =
            booking.guests && booking.guests.length
                ? booking.guests[0].guest_name
                : "-";

        const showUrl = window.invoiceConfig.showUrl.replace(
            "__ID__",
            invoice.id,
        );

        html += `

<tr class="border-t hover:bg-zinc-50">

    <td class="px-4 py-4 font-semibold">
        ${invoice.invoice_no}
    </td>

    <td>
        ${booking.booking_no ?? "-"}
    </td>

    <td>
        ${guest}
    </td>

    <td>
        ${formatDate(invoice.created_at)}
    </td>

    <td class="text-right font-semibold">
        ₹${grandTotal.toLocaleString("en-IN", {
            minimumFractionDigits: 2,
        })}
    </td>

    <td>

        <div class="flex justify-center gap-2">

    <a href="${showUrl}"
       class="h-9 w-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition"
       title="View">

        <i class="fa-solid fa-eye"></i>

    </a>

</div>

    </td>

</tr>`;
    });

    $("#invoiceTable").html(html);
}

function bindPagination() {
    $("#pagination")
        .find("a")
        .click(function (e) {
            e.preventDefault();

            const href = $(this).attr("href");

            if (!href) return;

            const page = new URL(href).searchParams.get("page");

            loadInvoices(page);
        });
}

function formatDate(date) {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}

function debounce(fn, delay) {
    let timer;

    return function () {
        clearTimeout(timer);

        timer = setTimeout(fn, delay);
    };
}
