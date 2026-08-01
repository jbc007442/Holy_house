$(function () {
    loadStock();

    $("#filterForm").submit(function (e) {
        e.preventDefault();

        loadStock();
    });

    $("#filterForm input").on("keyup", function () {
        loadStock();
    });

    $("#filterForm select").change(function () {
        loadStock();
    });

    $("#resetBtn").click(function () {
        $("#filterForm")[0].reset();

        loadStock();
    });

    $(document).on("click", "#paginationWrapper .pagination a", function (e) {
        e.preventDefault();

        loadStock($(this).attr("href"));
    });
});

function loadStock(url = stockRoute) {
    $("#stockTableBody").html(`
        <tr>
            <td colspan="6"
                class="px-6 py-10 text-center text-zinc-500">

                Loading...

            </td>
        </tr>
    `);

    $.ajax({
        url: url,

        type: "GET",

        data: $("#filterForm").serialize(),

        success: function (response) {
            let html = "";

            if (response.data.length === 0) {
                html = `
                    <tr>

                        <td colspan="6"
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

            $("#paginationWrapper").html(response.pagination);
        },

        error: function () {
            $("#stockTableBody").html(`
                <tr>
                    <td colspan="6"
                        class="px-6 py-10 text-center text-red-600">

                        Failed to load data.

                    </td>
                </tr>
            `);
        },
    });
}
