$(function () {
    let currentPage = 1;

    loadItems();

    // Search & Filters
    $("#searchFilter").on("keyup", function () {
        loadItems(1);
    });

    $("#categoryFilter").on("change", function () {
        loadItems(1);
    });

    $("#statusFilter").on("change", function () {
        loadItems(1);
    });

    $("#refreshItems").on("click", function () {
        $("#searchFilter").val("");
        $("#categoryFilter").val("");
        $("#statusFilter").val("");

        loadItems(1);
    });

    // Delete Item
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

                loadItems(currentPage);
            },
            error: function () {
                window.notyf.error("Unable to delete item.");
            },
        });
    });

    // Pagination Click
    $(document).on("click", ".page-btn", function () {
        if ($(this).prop("disabled")) return;

        const page = $(this).data("page");
        loadItems(page);
    });

    function loadItems(page = 1) {
        currentPage = page;

        $.ajax({
            url: window.itemConfig.indexUrl,
            type: "GET",
            data: {
                page: page,
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
                renderPagination(response.pagination);
            },
            error: function () {
                window.notyf.error("Failed to load items.");
            },
        });
    }

    function renderTable(items) {
        const tbody = $("#itemTableBody");

        tbody.empty();

        if (!items.length) {
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

                    <td class="px-4 py-3 font-medium">
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
                            class="text-emerald-600 hover:text-emerald-700 mr-3"
                            title="Manage Item">
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

    function renderPagination(pagination) {
        const container = $("#pagination");
        const info = $("#paginationInfo");

        container.empty();

        if (!pagination) return;

        info.text(
            `Showing ${pagination.from ?? 0} to ${pagination.to ?? 0} of ${pagination.total} entries`,
        );

        const current = pagination.current_page;
        const last = pagination.last_page;

        container.html(`
        <button
            class="page-btn px-4 py-2 rounded-lg border hover:bg-zinc-100 disabled:opacity-50"
            data-page="${current - 1}"
            ${current === 1 ? "disabled" : ""}>
            <i class="fa-solid fa-chevron-left mr-1"></i>
            Previous
        </button>

        <span class="text-sm font-medium text-zinc-600 px-2">
            Page ${current} of ${last}
        </span>

        <button
            class="page-btn px-4 py-2 rounded-lg border hover:bg-zinc-100 disabled:opacity-50"
            data-page="${current + 1}"
            ${current === last ? "disabled" : ""}>
            Next
            <i class="fa-solid fa-chevron-right ml-1"></i>
        </button>
    `);
    }
});
