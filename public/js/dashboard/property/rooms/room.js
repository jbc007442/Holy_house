let currentPage = 1;

$(function () {
    loadRooms();

    $("#searchRoom").on("keyup", function () {
        loadRooms(1);
    });

    $("#buildingFilter,#statusFilter").on("change", function () {
        loadRooms(1);
    });

    $(document).on("click", ".pagination-btn", function () {
        if ($(this).prop("disabled")) return;

        loadRooms($(this).data("page"));
    });

    function loadRooms(page = 1) {
        currentPage = page;
        $.ajax({
            url: roomConfig.indexUrl,

            type: "GET",

            data: {
                page: page,

                search: $("#searchRoom").val(),

                building: $("#buildingFilter").val(),

                status: $("#statusFilter").val(),
            },

            beforeSend() {
                $("#roomTableBody").html(`
                    <tr>
                        <td colspan="7" class="text-center py-10 text-zinc-500">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                            Loading rooms...
                        </td>
                    </tr>
                `);
            },

            success(response) {
                $("#totalRooms").text(response.stats.totalRooms);
                $("#availableRooms").text(response.stats.availableRooms);
                $("#runningRooms").text(response.stats.runningRooms);
                $("#blockedRooms").text(response.stats.blockedRooms);
                $("#maintenanceRooms").text(response.stats.maintenanceRooms);

                let html = "";

                if (response.data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center py-10 text-zinc-500">
                                No rooms found.
                            </td>
                        </tr>
                    `;
                } else {
                    response.data.forEach((room) => {
                        let badge = "";

                        switch (room.status) {
                            case "available":
                                badge = "bg-green-100 text-green-700";
                                break;

                            case "running":
                                badge = "bg-blue-100 text-blue-700";
                                break;

                            case "blocked":
                                badge = "bg-red-100 text-red-700";
                                break;

                            default:
                                badge = "bg-yellow-100 text-yellow-700";
                        }

                        html += `
                        <tr class="border-t">

                            <td class="px-5 py-4">
                                ${room.building?.name ?? "-"}
                            </td>

                            <td class="px-5 py-4 font-medium">
                                ${room.room_number}
                            </td>

                            <td class="px-5 py-4">
                                ${room.floor}
                            </td>

                            <td class="px-5 py-4">
                                ${room.capacity}
                            </td>

                            <td class="px-5 py-4">

                                <span class="px-3 py-1 rounded-full text-xs ${badge}">

                                    ${room.status.charAt(0).toUpperCase() + room.status.slice(1)}

                                </span>

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="${roomConfig.viewUrl.replace(":id", room.id)}"
                                        class="w-9 h-9 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition flex items-center justify-center">

                                        <i class="fa-solid fa-eye"></i>

                                    </a>

                                    <a href="${roomConfig.editUrl.replace(":id", room.id)}"
                                        class="w-9 h-9 rounded-lg border border-zinc-200 hover:bg-zinc-100 transition flex items-center justify-center">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>

                                    <button
                                        class="deleteRoom w-9 h-9 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition"
                                        data-id="${room.id}">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>
                        `;
                    });
                }

                $("#roomTableBody").html(html);
                const current = response.pagination.current_page;
                const last = response.pagination.last_page;

                let pagination = `
<div class="flex items-center justify-between">

    <div class="text-sm text-zinc-500">
        Showing ${response.pagination.from} to ${response.pagination.to}
        of ${response.pagination.total} entries
    </div>

    <div class="flex items-center gap-3">

        <button
            class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100"
            data-page="${current - 1}"
            ${current === 1 ? "disabled" : ""}>
            Previous
        </button>

        <span class="text-sm font-medium">
            Page ${current} of ${last}
        </span>

        <button
            class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100"
            data-page="${current + 1}"
            ${current === last ? "disabled" : ""}>
            Next
        </button>

    </div>

</div>
`;

                $("#paginationWrapper").html(pagination);
            },
        });
    }

    $(document).on("click", ".deleteRoom", function () {
        if (!confirm("Delete this room?")) {
            return;
        }

        let id = $(this).data("id");

        $.ajax({
            url: roomConfig.destroyUrl.replace(":id", id),

            type: "POST",

            data: {
                _token: roomConfig.csrf,

                _method: "DELETE",
            },

            beforeSend() {
                showSaving();
            },

            success(response) {
                window.notyf.success(response.message);

                loadRooms(currentPage);
            },

            error(xhr) {
                window.notyf.error(
                    xhr.responseJSON?.message || "Failed to delete room.",
                );
            },
        });
    });
});
