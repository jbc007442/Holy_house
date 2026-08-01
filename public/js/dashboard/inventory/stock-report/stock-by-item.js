$(function () {
    loadStockItem();
});

function loadStockItem() {
    $.ajax({
        url: stockPerItemDataRoute,

        type: "GET",

        success: function (response) {
            renderSummary(response);

            renderLedger(response);
        },

        error: function () {
            $("#summaryCards").html(`
                <div class="col-span-4 text-center py-10 text-red-600">
                    Failed to load stock details.
                </div>
            `);

            $("#ledgerTable").html(`
                <tr>
                    <td colspan="7" class="py-10 text-center text-red-600">
                        Failed to load stock ledger.
                    </td>
                </tr>
            `);
        },
    });
}

function renderSummary(response) {
    const item = response.item;

    const summary = response.summary;

    $("#summaryCards").html(`

        <div class="rounded-2xl border border-zinc-200 bg-white p-5">

            <p class="text-sm text-zinc-500">
                Current Stock
            </p>

            <h3 class="mt-2 text-3xl font-bold">
                ${Number(summary.current_stock).toLocaleString()}
            </h3>

            <p class="text-sm text-zinc-500 mt-1">
                ${item.unit}
            </p>

        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5">

            <p class="text-sm text-zinc-500">
                Total Purchased
            </p>

            <h3 class="mt-2 text-3xl font-bold text-green-600">
                ${Number(summary.total_purchase).toLocaleString()}
            </h3>

            <p class="text-sm text-zinc-500 mt-1">
                ${item.unit}
            </p>

        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5">

            <p class="text-sm text-zinc-500">
                Total Issued
            </p>

            <h3 class="mt-2 text-3xl font-bold text-red-600">
                ${Number(summary.total_issue).toLocaleString()}
            </h3>

            <p class="text-sm text-zinc-500 mt-1">
                ${item.unit}
            </p>

        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5">

            <p class="text-sm text-zinc-500">
                Latest Purchase Price
            </p>

            <h3 class="mt-2 text-3xl font-bold">
                ₹${Number(summary.latest_price).toLocaleString("en-IN")}
            </h3>

        </div>

    `);
}

function renderLedger(response) {
    let html = "";

    response.purchase_history.forEach(function (purchase) {
        html += `

            <tr class="border-t">

                <td class="px-6 py-4">

                    ${formatDate(purchase.purchase_date)}

                </td>

                <td class="px-6 py-4">

                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                        Purchase

                    </span>

                </td>

                <td class="px-6 py-4 text-right">

                    ${purchase.quantity}

                </td>

                <td class="px-6 py-4 text-right">

                    -

                </td>

                <td class="px-6 py-4 text-right">

                    -

                </td>

                <td class="px-6 py-4 text-right">

                    ₹${Number(purchase.total_amount).toLocaleString("en-IN")}

                </td>

                <td class="px-6 py-4">

                    ${purchase.remarks ?? "-"}

                </td>

            </tr>

        `;
    });

    response.stock_movements.forEach(function (movement) {
        html += `

            <tr class="border-t">

                <td class="px-6 py-4">

                    ${formatDate(movement.created_at)}

                </td>

                <td class="px-6 py-4">

                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                        ${movement.type}

                    </span>

                </td>

                <td class="px-6 py-4 text-right">

                    -

                </td>

                <td class="px-6 py-4 text-right">

                    ${movement.quantity}

                </td>

                <td class="px-6 py-4 text-right">

                    -

                </td>

                <td class="px-6 py-4 text-right">

                    -

                </td>

                <td class="px-6 py-4">

                    ${movement.remarks ?? "-"}

                </td>

            </tr>

        `;
    });

    if (html === "") {
        html = `
            <tr>

                <td colspan="7" class="py-10 text-center text-zinc-500">

                    No stock history found.

                </td>

            </tr>
        `;
    }

    $("#ledgerTable").html(html);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}
