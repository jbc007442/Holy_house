$(function () {
    loadItems();

    $("#searchFilter").on("keyup", loadItems);
    $("#categoryFilter").on("change", loadItems);
    $("#statusFilter").on("change", loadItems);
    $("#refreshItems").on("click", loadItems);

    $(document).on("click", ".delete-item", function () {
        if (!confirm("Delete this item?")) return;

        const id = $(this).data("id");

        $.ajax({
            url: window.itemConfig.destroyUrl.replace(":id", id),
            type: "POST",
            data: {
                _token: window.itemConfig.csrf,
                _method: "DELETE",
            },
            success: function (response) {
                window.notyf.success(response.message);

                loadItems();
            },
            error: function () {
                window.notyf.error("Unable to delete item.");
            },
        });
    });
});

function loadItems() {
    $.ajax({
        url: window.itemConfig.indexUrl,

        type: "GET",

        data: {
            search: $("#searchFilter").val(),
            category: $("#categoryFilter").val(),
            status: $("#statusFilter").val(),
        },

        success: function (response) {
            $("#totalItems").text(response.stats.totalItems);
            $("#activeItems").text(response.stats.activeItems);
            $("#inactiveItems").text(response.stats.inactiveItems);
            $("#lowStockItems").text(response.stats.lowStockItems);

            renderTable(response.data);
        },

        error: function () {
            window.notyf.error("Failed to load items.");
        },
    });
}

function renderTable(items) {
    const tbody = $("#itemTableBody");

    tbody.empty();

    if (items.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-zinc-500">
                    No items found.
                </td>
            </tr>
        `);

        return;
    }

    items.forEach((item) => {
        const status = item.status
            ? `<span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Active</span>`
            : `<span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Inactive</span>`;

        tbody.append(`
            <tr class="border-t hover:bg-zinc-50">

                <td class="px-4 py-3 font-medium text-zinc-800">
                    ${item.item_name}
                </td>

                <td class="px-4 py-3">
                    ${item.category}
                </td>

                <td class="px-4 py-3">
                    ${item.unit}
                </td>

                <td class="px-4 py-3 text-center">
                    ${item.opening_stock}
                </td>

                <td class="px-4 py-3 text-center">
                    ${item.minimum_stock}
                </td>

                <td class="px-4 py-3 text-center">
                    ${status}
                </td>

                <td class="px-4 py-3 text-center">

                    <a href="${window.itemConfig.viewUrl.replace(":id", item.id)}"
                        class="text-sky-600 hover:text-sky-700 mr-3">

                        <i class="fa-solid fa-eye"></i>

                    </a>

                    <a href="${window.itemConfig.editUrl.replace(":id", item.id)}"
                        class="text-amber-600 hover:text-amber-700 mr-3">

                        <i class="fa-solid fa-pen"></i>

                    </a>

                    <a href="${window.itemConfig.manageUrl.replace(":id", item.id)}"
                        class="text-emerald-600 hover:text-emerald-700 mr-3" title="Manage Item">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>

                    <button
                        class="delete-item text-red-600 hover:text-red-700"
                        data-id="${item.id}">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </td>

            </tr>
        `);
    });
}
