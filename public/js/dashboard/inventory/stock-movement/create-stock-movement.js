document.addEventListener("DOMContentLoaded", function () {
    const itemSelect = document.getElementById("item_id");
    const quantityInput = document.getElementById("quantity");
    const currentStock = document.getElementById("currentStock");
    const form = document.querySelector("form");

    //-------------------------------------------------------
    // Stock
    //-------------------------------------------------------

    function getCurrentStock() {
        const option = itemSelect.options[itemSelect.selectedIndex];

        if (!option || !option.dataset.stock) {
            currentStock.textContent = "-";
            quantityInput.removeAttribute("max");

            return 0;
        }

        const stock = parseInt(option.dataset.stock) || 0;

        currentStock.textContent = stock;
        quantityInput.max = stock;

        return stock;
    }

    getCurrentStock();

    itemSelect.addEventListener("change", getCurrentStock);

    quantityInput.addEventListener("input", function () {
        const stock = getCurrentStock();
        const qty = parseInt(this.value) || 0;

        if (qty > stock) {
            this.setCustomValidity(`Only ${stock} item(s) available.`);
        } else {
            this.setCustomValidity("");
        }

        this.reportValidity();
    });

    //-------------------------------------------------------
    // Building -> Floors
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
                    html += `<option value="${floor.id}" data-name="${floor.name}">${floor.name}</option>`;
                });

                $("#building_floor_id").html(html);
            },
        });
    }

    //-------------------------------------------------------
    // Floor -> Rooms
    //-------------------------------------------------------

   function loadRooms() {
       let buildingId = $("#building_id").val();

       let selectedOption = $("#building_floor_id option:selected");

       let floorId = selectedOption.val();
       let floorName = selectedOption.data("name");

       console.log("========== LOAD ROOMS ==========");
       console.log("Building ID:", buildingId);
       console.log("Floor ID:", floorId);
       console.log("Floor Name:", floorName);
       console.log("Selected Option:", selectedOption.prop("outerHTML"));

       $("#room_id").html("<option>Loading...</option>");

       if (!buildingId || !floorId || !floorName) {
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
               console.log("Rooms:", rooms);

               let html = '<option value="">Select Room</option>';

               $.each(rooms, function (i, room) {
                   html += `
                    <option value="${room.id}">
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

    $("#building_id").on("change", loadFloors);

    $("#building_floor_id").on("change", loadRooms);

    //-------------------------------------------------------
    // Submit Validation
    //-------------------------------------------------------

    form.addEventListener("submit", function (e) {
        const stock = getCurrentStock();
        const qty = parseInt(quantityInput.value) || 0;

        if (qty > stock) {
            e.preventDefault();

            quantityInput.setCustomValidity(`Only ${stock} item(s) available.`);
            quantityInput.reportValidity();
        } else {
            quantityInput.setCustomValidity("");
        }
    });
});
