$(function () {
    loadRooms();

    $("#buildingFilter, #floorFilter").on("change", loadRooms);

    $("#searchRoom").on("keyup", function () {
        clearTimeout(window.roomTimer);
        window.roomTimer = setTimeout(loadRooms, 300);
    });
});

function loadRooms() {
    $.ajax({
        url: roomStatusConfig.url,

        data: {
            building: $("#buildingFilter").val(),
            floor: $("#floorFilter").val(),
            search: $("#searchRoom").val(),
        },

        success: function (response) {
            $("#availableRooms").text(response.stats.available);
            $("#runningRooms").text(response.stats.running);
            $("#blockedRooms").text(response.stats.blocked);
            $("#maintenanceRooms").text(response.stats.maintenance);

            renderRooms(response.rooms);
        },
    });
}

function renderRooms(rooms) {
    let grouped = {};

    rooms.forEach((room) => {
        if (!grouped[room.floor]) {
            grouped[room.floor] = [];
        }

        grouped[room.floor].push(room);
    });

    let html = "";

    $.each(grouped, function (floor, list) {
        html += `
        <div class="mb-8">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">${floor}</h2>
                <span class="text-sm text-zinc-500">${list.length} Rooms</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-5">
        `;

        list.forEach((room) => {
            let border = "border-zinc-300";
            let bg = "bg-zinc-200";
            let text = "text-zinc-700";
            let icon = "fa-screwdriver-wrench";
            let iconColor = "text-zinc-600";

            if (room.status === "available") {
                border = "border-green-200";
                bg = "bg-green-100";
                text = "text-green-700";
                icon = "fa-door-open";
                iconColor = "text-green-500";
            }

            if (room.status === "running") {
                border = "border-blue-200";
                bg = "bg-blue-100";
                text = "text-blue-700";
                icon = "fa-user-check";
                iconColor = "text-blue-500";
            }

            if (room.status === "blocked") {
                border = "border-red-200";
                bg = "bg-red-100";
                text = "text-red-700";
                icon = "fa-ban";
                iconColor = "text-red-500";
            }

            html += `
                <a href="${roomStatusConfig.viewUrl.replace(":id", room.id)}"
                    class="block bg-white border ${border} rounded-2xl p-5 hover:shadow-xl transition">

                    <div class="flex justify-between items-center">

                        <h3 class="font-bold text-xl">${room.room_number}</h3>

                        <i class="fa-solid ${icon} ${iconColor}"></i>

                    </div>

                    <div class="mt-3 text-xs text-zinc-500">
                        ${room.building.name}
                    </div>

                    <div class="mt-5">

                        <span class="text-xs px-3 py-1 rounded-full ${bg} ${text}">
                            ${room.status.charAt(0).toUpperCase() + room.status.slice(1)}
                        </span>

                    </div>

                </a>
            `;
        });

        html += `</div></div>`;
    });

    if (rooms.length === 0) {
        html = `
            <div class="bg-white rounded-2xl border border-zinc-200 py-16 text-center">

                <i class="fa-solid fa-door-open text-5xl text-zinc-300 mb-4"></i>

                <h3 class="text-lg font-semibold text-zinc-700">
                    No Rooms Found
                </h3>

            </div>
        `;
    }

    $("#roomContainer").html(html);
}
