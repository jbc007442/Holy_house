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

            renderPagination(response.pagination);
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

    $(document).on("click", ".page-btn", function () {
        if ($(this).prop("disabled")) return;

        loadInvoices($(this).data("page"));
    });

    invoices.forEach(function (invoice) {
        const booking = invoice.booking ?? {};

        const grandTotal = Number(invoice.grand_total ?? 0);

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

function formatDate(date) {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}

function renderPagination(pagination) {
    if (!pagination) return;

    const current = pagination.current_page;
    const last = pagination.last_page;

    $("#pagination").html(`
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="text-sm text-zinc-500">
                Showing ${pagination.from ?? 0}
                to ${pagination.to ?? 0}
                of ${pagination.total} entries
            </div>

            <div class="flex items-center gap-3">

                <button
                    class="page-btn px-4 py-2 rounded-lg border hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current - 1}"
                    ${current === 1 ? "disabled" : ""}>

                    <i class="fa-solid fa-chevron-left mr-1"></i>

                    Previous

                </button>

                <span class="text-sm font-medium text-zinc-600">

                    Page ${current} of ${last}

                </span>

                <button
                    class="page-btn px-4 py-2 rounded-lg border hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current + 1}"
                    ${current === last ? "disabled" : ""}>

                    Next

                    <i class="fa-solid fa-chevron-right ml-1"></i>

                </button>

            </div>

        </div>
    `);
}

function debounce(fn, delay) {
    let timer;

    return function () {
        clearTimeout(timer);

        timer = setTimeout(fn, delay);
    };
}
