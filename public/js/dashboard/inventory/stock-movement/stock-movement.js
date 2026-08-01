$(function () {
    loadMovements();

    $("#refreshMovements").click(loadMovements);

    $("#searchFilter,#typeFilter,#itemFilter,#dateFilter").on(
        "keyup change",
        loadMovements,
    );
});

function loadMovements() {
    $.ajax({
        url: window.stockMovementConfig.indexUrl,

        type: "GET",

        data: {
            search: $("#searchFilter").val(),
            type: $("#typeFilter").val(),
            item: $("#itemFilter").val(),
            date: $("#dateFilter").val(),
        },

        success: function (response) {
            renderMovements(response.data);
        },

        error: function () {
            notyf.error("Unable to load stock movements.");
        },
    });
}

function renderMovements(data) {
    let html = "";

    if (data.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="text-center py-8 text-zinc-500">
                    No stock movements found.
                </td>
            </tr>
        `;
    } else {
        data.forEach(function (movement) {
            const viewUrl = window.stockMovementConfig.viewUrl.replace(
                ":id",
                movement.id,
            );
            const editUrl = window.stockMovementConfig.editUrl.replace(
                ":id",
                movement.id,
            );

            const badge =
                movement.type === "out"
                    ? '<span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs">Stock Out</span>'
                    : '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs">Adjustment</span>';

            html += `
<tr class="border-t border-zinc-200 hover:bg-zinc-50">

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
        <div class="flex justify-center items-center gap-4">
            <a href="${viewUrl}" class="text-sky-600 hover:text-sky-700">
                <i class="fa-solid fa-eye"></i>
            </a>

            <a href="${editUrl}" class="text-amber-600 hover:text-amber-700">
                <i class="fa-solid fa-pen"></i>
            </a>

            <button onclick="deleteMovement(${movement.id})" class="text-red-600 hover:text-red-700">
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

            loadMovements();
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
