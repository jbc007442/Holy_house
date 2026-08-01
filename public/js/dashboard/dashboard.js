$(function () {
    loadDashboard();
});

function loadDashboard() {
    $.ajax({
        url: window.dashboard.ajaxUrl,
        type: "GET",

        success: function (response) {
            const stats = response.stats;

            $("#totalBuildings").text(stats.buildings);
            $("#totalRooms").text(stats.rooms);
            $("#totalBookings").text(stats.bookings);
            $("#totalUsers").text(stats.users);
            $("#totalItems").text(stats.items);
            $("#currentStock").text(stats.currentStock);
            $("#stockMovements").text(stats.stockMovements);

            $("#purchaseAmount").text(
                "₹" +
                    Number(stats.purchaseAmount).toLocaleString("en-IN", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }),
            );

            loadCharts();

            loadEmptyTables();
        },

        error: function () {
            window.notyf.error("Unable to load dashboard.");
        },
    });
}

function loadCharts() {
    const bookingCtx = document.getElementById("bookingChart");

    if (bookingCtx) {
        new Chart(bookingCtx, {
            type: "line",

            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],

                datasets: [
                    {
                        label: "Bookings",
                        data: [2, 5, 3, 6, 4, 8, 7],
                        borderWidth: 3,
                        tension: 0.4,
                        fill: false,
                    },
                ],
            },

            options: {
                responsive: true,

                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    const inventoryCtx = document.getElementById("inventoryChart");

    if (inventoryCtx) {
        new Chart(inventoryCtx, {
            type: "bar",

            data: {
                labels: ["Kitchen", "Housekeeping", "Laundry", "Restaurant"],

                datasets: [
                    {
                        label: "Stock",

                        data: [12, 20, 8, 15],

                        borderWidth: 1,
                    },
                ],
            },

            options: {
                responsive: true,

                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }
}

function loadEmptyTables() {
    $("#recentBookings").html(`
        <tr>
            <td colspan="3" class="py-8 text-center text-zinc-500">
                No recent bookings found.
            </td>
        </tr>
    `);

    $("#lowStockTable").html(`
        <tr>
            <td colspan="2" class="py-8 text-center text-zinc-500">
                No low stock items.
            </td>
        </tr>
    `);
}
