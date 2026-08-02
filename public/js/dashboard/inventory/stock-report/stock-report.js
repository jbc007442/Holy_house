let currentPage = 1;

$(function () {
    loadStock();

    // Filter
    $("#filterForm").submit(function (e) {
        e.preventDefault();
        loadStock(1);
    });

    // Search
    $("#filterForm input").on("keyup", function () {
        loadStock(1);
    });

    // Dropdowns
    $("#filterForm select").on("change", function () {
        loadStock(1);
    });

    // Reset
    $("#resetBtn").on("click", function () {
        $("#filterForm")[0].reset();

        loadStock(1);
    });

    // Pagination
    $(document).on("click", ".page-btn", function () {
        if ($(this).prop("disabled")) return;

        loadStock($(this).data("page"));
    });
});

function loadStock(page = 1) {
    currentPage = page;

    $("#stockTableBody").html(`
        <tr>
            <td colspan="7"
                class="px-6 py-10 text-center text-zinc-500">

                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                Loading...

            </td>
        </tr>
    `);

    $.ajax({
        url: stockRoute,

        type: "GET",

        data: $("#filterForm").serialize() + "&page=" + page,

        success: function (response) {
            let html = "";

            if (!response.data.length) {
                html = `
                    <tr>
                        <td colspan="7"
                            class="px-6 py-10 text-center text-zinc-500">

                            No stock records found.

                        </td>
                    </tr>
                `;
            } else {
                response.data.forEach(function (item) {
                    let badge = "";

                    if (Number(item.opening_stock) <= 0) {
                        badge = `
                            <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                Out of Stock
                            </span>
                        `;
                    } else if (
                        Number(item.opening_stock) <= Number(item.minimum_stock)
                    ) {
                        badge = `
                            <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                Low Stock
                            </span>
                        `;
                    } else {
                        badge = `
                            <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                In Stock
                            </span>
                        `;
                    }

                    html += `
                        <tr class="border-t border-zinc-200 hover:bg-zinc-50">

                            <td class="px-6 py-4 font-medium">
                                ${item.item_name ?? ""}
                            </td>

                            <td class="px-6 py-4">
                                ${item.category ?? ""}
                            </td>

                            <td class="px-6 py-4">
                                ${item.unit ?? ""}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold">
                                ${Number(item.opening_stock).toLocaleString()}
                            </td>

                            <td class="px-6 py-4 text-right">
                                ${Number(item.minimum_stock).toLocaleString()}
                            </td>

                            <td class="px-6 py-4 text-center">
                                ${badge}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <a
                                    href="/dashboard/stock-per-item/${item.id}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-3 py-2 text-sm hover:bg-zinc-100 transition">

                                    <i class="fa-solid fa-eye"></i>

                                    View

                                </a>

                            </td>

                        </tr>
                    `;
                });
            }

            $("#stockTableBody").html(html);

            renderPagination(response.pagination);
        },

        error: function () {
            $("#stockTableBody").html(`
                <tr>
                    <td colspan="7"
                        class="px-6 py-10 text-center text-red-600">

                        Failed to load data.

                    </td>
                </tr>
            `);
        },
    });
}

function renderPagination(pagination) {
    if (!pagination) return;

    let html = "";

    const current = pagination.current_page;
    const last = pagination.last_page;

    html = `
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="text-sm text-zinc-500">

                Showing ${pagination.from ?? 0}
                to ${pagination.to ?? 0}
                of ${pagination.total} entries

            </div>

            <div class="flex items-center gap-3">

                <button
                    class="page-btn px-4 py-2 border rounded-lg hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current - 1}"
                    ${current === 1 ? "disabled" : ""}>

                    <i class="fa-solid fa-chevron-left mr-1"></i>

                    Previous

                </button>

                <span class="text-sm font-medium text-zinc-600">

                    Page ${current} of ${last}

                </span>

                <button
                    class="page-btn px-4 py-2 border rounded-lg hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current + 1}"
                    ${current === last ? "disabled" : ""}>

                    Next

                    <i class="fa-solid fa-chevron-right ml-1"></i>

                </button>

            </div>

        </div>
    `;

    $("#paginationWrapper").html(html);
}
