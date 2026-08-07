document.addEventListener("DOMContentLoaded", function () {
    const itemSelect = document.getElementById("item_id");
    const quantityInput = document.getElementById("quantity");
    const currentStock = document.getElementById("currentStock");
    const form = document.querySelector("form");

    //-------------------------------------------------------
    // Stock
    //-------------------------------------------------------

    function updateStock() {
        const option = itemSelect.options[itemSelect.selectedIndex];

        if (!option || !option.dataset.stock) {
            currentStock.textContent = "-";
            quantityInput.removeAttribute("max");
            return;
        }

        const current = parseInt(option.dataset.stock) || 0;
        const entered = parseInt(quantityInput.value) || 0;

        const maxAllowed = current + originalQty;
        const remaining = maxAllowed - entered;

        currentStock.textContent = remaining;
        quantityInput.max = maxAllowed;

        if (entered > maxAllowed) {
            quantityInput.setCustomValidity(
                `Only ${maxAllowed} item(s) available.`,
            );
        } else {
            quantityInput.setCustomValidity("");
        }

        quantityInput.reportValidity();
    }

    updateStock();

    itemSelect.addEventListener("change", updateStock);
    quantityInput.addEventListener("input", updateStock);

    //-------------------------------------------------------
    // Building -> Floor
    //-------------------------------------------------------

    function loadFloors() {
        let buildingId = $("#building_id").val();

        $("#building_floor_id").html("<option>Loading...</option>");

        if (!buildingId) {
            $("#building_floor_id").html(
                '<option value="">Select Floor</option>',
            );
            $("#room_id").html('<option value="">Select Room</option>');

            return;
        }

        $.ajax({
            url: buildingFloorsUrl.replace(":id", buildingId),

            type: "GET",

            dataType: "json",

            success: function (floors) {
                let html = '<option value="">Select Floor</option>';

                $.each(floors, function (i, floor) {
                    html += `
                        <option
                            value="${floor.id}"
                            data-name="${floor.name}"
                            ${selectedFloor == floor.id ? "selected" : ""}>
                            ${floor.name}
                        </option>
                    `;
                });

                $("#building_floor_id").html(html);

                loadRooms();
            },

            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    }

    //-------------------------------------------------------
    // Floor -> Room
    //-------------------------------------------------------

    function loadRooms() {
        let buildingId = $("#building_id").val();

        let floorName = $("#building_floor_id option:selected").data("name");

        $("#room_id").html("<option>Loading...</option>");

        if (!buildingId || !floorName) {
            $("#room_id").html('<option value="">Select Room</option>');

            return;
        }

        $.ajax({
            url: roomsUrl.replace(":id", buildingId),

            type: "GET",

            data: {
                floor: floorName,
            },

            dataType: "json",

            success: function (rooms) {
                let html = '<option value="">Select Room</option>';

                $.each(rooms, function (i, room) {
                    html += `
                        <option
                            value="${room.id}"
                            ${selectedRoom == room.id ? "selected" : ""}>
                            ${room.room_number}
                        </option>
                    `;
                });

                $("#room_id").html(html);
            },

            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    }

    //-------------------------------------------------------
    // Events
    //-------------------------------------------------------

    $("#building_id").on("change", function () {
        selectedFloor = "";
        selectedRoom = "";

        loadFloors();
    });

    $("#building_floor_id").on("change", function () {
        selectedRoom = "";

        loadRooms();
    });

    //-------------------------------------------------------
    // Initial Load
    //-------------------------------------------------------

    if (selectedBuilding) {
        $("#building_id").val(selectedBuilding);

        loadFloors();
    } else {
        updateStock();
    }

    //-------------------------------------------------------
    // Submit Validation
    //-------------------------------------------------------

    form.addEventListener("submit", function (e) {
        updateStock();

        if (!quantityInput.checkValidity()) {
            e.preventDefault();

            quantityInput.reportValidity();
        }
    });
});
