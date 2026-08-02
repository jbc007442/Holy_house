let currentPage = 1;

$(function () {

    loadMovements();

    $("#refreshMovements").click(function () {
        $("#searchFilter").val("");
        $("#typeFilter").val("");
        $("#itemFilter").val("");
        $("#dateFilter").val("");

        loadMovements(1);
    });

    $("#searchFilter").on("keyup", function () {
        loadMovements(1);
    });

    $("#typeFilter,#itemFilter,#dateFilter").on("change", function () {
        loadMovements(1);
    });

    $(document).on("click", ".page-btn", function () {

        if ($(this).prop("disabled")) return;

        loadMovements($(this).data("page"));
    });

});

function loadMovements(page = 1) {

    currentPage = page;

    $.ajax({

        url: window.stockMovementConfig.indexUrl,

        type: "GET",

        data: {
            page: page,
            search: $("#searchFilter").val(),
            type: $("#typeFilter").val(),
            item: $("#itemFilter").val(),
            date: $("#dateFilter").val(),
        },

        success: function (response) {

            renderMovements(response.data);

            renderPagination(response.pagination);

        },

        error: function () {

            notyf.error("Unable to load stock movements.");

        },

    });

}

function renderMovements(data) {

    let html = "";

    if (!data.length) {

        html = `
            <tr>
                <td colspan="6" class="text-center py-8 text-zinc-500">
                    No stock movements found.
                </td>
            </tr>
        `;

    } else {

        data.forEach(function (movement) {

            const badge =
                movement.type === "out"
                    ? '<span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs">Stock Out</span>'
                    : '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs">Adjustment</span>';

            html += `
            <tr class="border-t hover:bg-zinc-50">

                <td class="px-6 py-4">
                    ${formatDate(movement.created_at)}
                </td>

                <td class="px-6 py-4">
                    ${movement.item.item_name}
                </td>

                <td class="px-6 py-4">
                    ${badge}
                </td>

                <td class="px-6 py-4 text-right font-semibold text-red-600">
                    -${movement.quantity}
                </td>

                <td class="px-6 py-4">
                    ${movement.reference ?? "-"}
                </td>

                <td class="px-6 py-4">

                    <div class="flex justify-center gap-4">

                        <a href="${window.stockMovementConfig.viewUrl.replace(":id", movement.id)}"
                            class="text-sky-600 hover:text-sky-700">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="${window.stockMovementConfig.editUrl.replace(":id", movement.id)}"
                            class="text-amber-600 hover:text-amber-700">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <button onclick="deleteMovement(${movement.id})"
                            class="text-red-600 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </div>

                </td>

            </tr>
            `;

        });

    }

    $("#movementTableBody").html(html);

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

function deleteMovement(id) {

    if (!confirm("Delete this movement?")) return;

    $.ajax({

        url: window.stockMovementConfig.destroyUrl.replace(":id", id),

        type: "POST",

        data: {
            _method: "DELETE",
            _token: window.stockMovementConfig.csrf,
        },

        success: function (response) {

            notyf.success(response.message);

            loadMovements(currentPage);

        },

        error: function () {

            notyf.error("Unable to delete movement.");

        },

    });

}

function formatDate(date) {

    return new Date(date).toLocaleDateString("en-GB", {

        day: "2-digit",

        month: "short",

        year: "numeric",

    });

}
