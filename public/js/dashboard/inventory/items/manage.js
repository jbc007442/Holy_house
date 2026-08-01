$(function () {
    loadPurchaseHistory();

    $("#refreshHistory").on("click", loadPurchaseHistory);

    $("#addPurchaseBtn").on("click", function () {
        $("#purchaseModal").removeClass("hidden").addClass("flex");
    });

    $("#closePurchaseModal, #cancelPurchase").on("click", closePurchaseModal);

    $("#purchaseModal").on("click", function (e) {
        if (e.target === this) {
            closePurchaseModal();
        }
    });

    $("#purchaseForm").on("submit", savePurchase);
});

function loadPurchaseHistory() {
    $.ajax({
        url: window.manageItem.ajaxUrl,

        type: "GET",

        success: function (response) {
            console.log("Full Response:", response);

            console.log("Item:", response.item);

            console.log("Purchase History:", response.data);

            updateItemSummary(response.item);

            renderPurchaseHistory(response.data);
        },

        error: function () {
            window.notyf.error("Unable to load purchase history.");
        },
    });
}

$("#quantity, #total_amount").on("input", function () {
    const qty = parseFloat($("#quantity").val()) || 0;
    const amount = parseFloat($("#total_amount").val()) || 0;

    let price = 0;

    if (qty > 0) {
        price = amount / qty;
    }

    $("#calculatedPrice").text(
        "₹" +
            price.toLocaleString("en-IN", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }) +
            " / " +
            window.manageItem.unit,
    );
});

function updateItemSummary(item) {
    $("#itemName").text(item.item_name);

    $("#itemCategory").text(item.category);

    $("#itemStock").text(item.opening_stock);

    $("#purchasePrice").text(
        "₹" +
            Number(item.purchase_price).toLocaleString("en-IN", {
                minimumFractionDigits: 2,
            }),
    );
}

function closePurchaseModal() {
    $("#purchaseModal").removeClass("flex").addClass("hidden");

    $("#purchaseForm")[0].reset();

    $("#purchase_date").val(new Date().toISOString().slice(0, 10));
}
function renderPurchaseHistory(data) {
    const tbody = $("#purchaseHistoryTable");

    tbody.empty();

    if (data.length === 0) {
        tbody.html(`

<tr>

<td colspan="6" class="py-16 text-center">

<div class="flex flex-col items-center">

<div class="h-16 w-16 rounded-full bg-zinc-100 flex items-center justify-center">

<i class="fa-solid fa-box-open text-3xl text-zinc-400"></i>

</div>

<h3 class="mt-5 text-lg font-semibold text-zinc-700">

No Purchase History

</h3>

<p class="mt-2 text-zinc-500">

Click <strong>Add Purchase</strong> to create the first purchase.

</p>

</div>

</td>

</tr>

        `);

        return;
    }

    data.forEach(function (purchase) {
        const unitPrice =
            Number(purchase.total_amount) / Number(purchase.quantity);

        tbody.append(`

<tr class="border-t hover:bg-zinc-50">

    <td class="px-5 py-4">

        ${formatDate(purchase.purchase_date)}

    </td>

    <td class="px-5 py-4 text-center font-semibold">

        ${purchase.quantity}

    </td>

    <td class="px-5 py-4 text-right font-semibold">

        ₹${Number(purchase.total_amount).toLocaleString("en-IN", {
            minimumFractionDigits: 2,
        })}

    </td>

    <td class="px-5 py-4 text-center">

        <button
            class="editPurchase text-amber-600 hover:text-amber-700 mr-3"
            data-id="${purchase.id}">

            <i class="fa-solid fa-pen"></i>

        </button>

        <button
            class="deletePurchase text-red-600 hover:text-red-700"
            data-id="${purchase.id}">

            <i class="fa-solid fa-trash"></i>

        </button>

    </td>

</tr>

`);
    });
}

function savePurchase(e) {
    e.preventDefault();

    $.ajax({
        url: window.manageItem.purchaseUrl,

        type: "POST",

        data: {
            _token: window.manageItem.csrf,

            purchase_date: $("#purchase_date").val(),

            quantity: $("#quantity").val(),

            total_amount: $("#total_amount").val(),

            remarks: $("#remarks").val(),
        },

        success: function (response) {
            window.notyf.success(response.message);

            closePurchaseModal();

            loadPurchaseHistory();
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    window.notyf.error(value[0]);
                });
            } else {
                window.notyf.error("Unable to save purchase.");
            }
        },
    });
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-IN", {
        day: "2-digit",

        month: "short",

        year: "numeric",
    });
}
