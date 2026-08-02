$(function () {
    loadBuildings();

    let timer;

    $("#searchBuilding").keyup(function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            loadBuildings(
                window.buildingConfig.indexUrl,
                $("#searchBuilding").val(),
            );
        }, 300);
    });

    $(document).on("click", ".pagination-btn", function () {
        const page = $(this).data("page");

        loadBuildings(
            `${window.buildingConfig.indexUrl}?page=${page}`,
            $("#searchBuilding").val(),
        );
    });

    $(document).on("click", ".deleteBuilding", function () {
        if (!confirm("Delete this building?")) {
            return;
        }

        let id = $(this).data("id");

        $.ajax({
            url: "/dashboard/buildings/" + id,

            type: "DELETE",

            data: {
                _token: window.buildingConfig.csrf,
            },

            beforeSend: function () {
                showSaving();
            },

            success: function (response) {
                window.notyf.success(response.message);

                loadBuildings(
                    window.buildingConfig.indexUrl,
                    $("#searchBuilding").val(),
                );
            },

            error: function (xhr) {
                window.notyf.error(
                    xhr.responseJSON?.message ?? "Failed to delete building.",
                );
            },
        });
    });
});

function loadBuildings(url = window.buildingConfig.indexUrl, search = "") {
    $.ajax({
        url: url,

        type: "GET",

        data: {
            search: search,
        },

        beforeSend: function () {
            $("#buildingTable").html(`
                <tr>
                    <td colspan="5" class="text-center py-10 text-zinc-500">
                        <i class="fa-solid fa-spinner fa-spin text-xl mb-3 block"></i>
                        Loading buildings...
                    </td>
                </tr>
            `);
        },

        success: function (response) {
            $("#totalBuildings").text(response.stats.totalBuildings);

            $("#activeBuildings").text(response.stats.activeBuildings);

            $("#totalRooms").text(response.stats.totalRooms);

            let html = "";

            if (response.data.length === 0) {
                html = `
                    <tr>
                        <td colspan="5" class="text-center py-10 text-zinc-500">
                            No buildings found.
                        </td>
                    </tr>
                `;
            } else {
                $.each(response.data, function (index, building) {
                    html += `

                        <tr class="border-t hover:bg-zinc-50">

                            <td class="px-5 py-4 font-medium">
                                ${building.name}
                            </td>

                            <td class="px-5 py-4">
                                ${building.code}
                            </td>

                            <td class="px-5 py-4 text-center">
                                ${building.rooms_count}
                            </td>

                            <td class="px-5 py-4 text-center">

                                ${
                                    building.status === "active"
                                        ? `<span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        Active
                                    </span>`
                                        : `<span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                        Inactive
                                    </span>`
                                }

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="/dashboard/buildings/${building.id}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50">

                                        <i class="fa-solid fa-eye"></i>

                                    </a>

                                    <a href="/dashboard/buildings/${building.id}/edit"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-zinc-200 hover:bg-zinc-100">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>

                                    <button
                                        data-id="${building.id}"
                                        class="deleteBuilding w-9 h-9 flex items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-50">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    `;
                });
            }

            $("#buildingTable").html(html);

            let pagination = "";

            if (response.pagination.last_page > 1) {
                const current = response.pagination.current_page;
                const last = response.pagination.last_page;

                pagination = `
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="text-sm text-zinc-500">
                Showing ${response.pagination.from} to ${response.pagination.to} of ${response.pagination.total} entries
            </div>

            <div class="flex items-center gap-3">

                <button
                    class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current - 1}"
                    ${current === 1 ? "disabled" : ""}>
                    <i class="fa-solid fa-chevron-left mr-1"></i>
                    Previous
                </button>

                <span class="text-sm font-medium text-zinc-600">
                    Page ${current} of ${last}
                </span>

                <button
                    class="pagination-btn px-4 py-2 border rounded-lg hover:bg-zinc-100 disabled:opacity-50"
                    data-page="${current + 1}"
                    ${current === last ? "disabled" : ""}>
                    Next
                    <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>

            </div>

        </div>
    `;
            } else {
                pagination = `
        <div class="flex justify-between items-center">

            <div class="text-sm text-zinc-500">
                Showing ${response.pagination.from ?? 0}
                to ${response.pagination.to ?? 0}
                of ${response.pagination.total ?? 0} entries
            </div>

            <div class="text-sm font-medium text-zinc-600">
                Page 1 of 1
            </div>

        </div>
    `;
            }

            $("#paginationWrapper").html(pagination);
        },
    });
}
