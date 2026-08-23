document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */

    const config = window.BuildingExpenseConfig || {};

    const indexUrl = config.indexUrl;

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const tableContainer = document.getElementById("expense-table");

    const filterForm = document.getElementById("expense-filter-form");

    const filterButton = document.getElementById("filter-expense-btn");

    const filterIcon = document.getElementById("filter-expense-icon");

    const filterText = document.getElementById("filter-expense-text");

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (!indexUrl) {
        console.error("BuildingExpenseConfig.indexUrl is missing.");

        return;
    }

    if (!tableContainer) {
        console.error("#expense-table not found.");

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Current Filter URL
    |--------------------------------------------------------------------------
    */

    function getFilterUrl() {
        if (!filterForm) {
            return indexUrl;
        }

        const formData = new FormData(filterForm);

        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== null && value !== "") {
                params.append(key, value);
            }
        }

        const queryString = params.toString();

        return queryString ? `${indexUrl}?${queryString}` : indexUrl;
    }

    /*
    |--------------------------------------------------------------------------
    | Set Filter Loading
    |--------------------------------------------------------------------------
    */

    function setFilterLoading(loading) {
        if (!filterButton) {
            return;
        }

        filterButton.disabled = loading;

        if (loading) {
            if (filterIcon) {
                filterIcon.className = "fa-solid fa-spinner fa-spin mr-1";
            }

            if (filterText) {
                filterText.textContent = "Loading...";
            }
        } else {
            if (filterIcon) {
                filterIcon.className = "fa-solid fa-magnifying-glass mr-1";
            }

            if (filterText) {
                filterText.textContent = "Apply Filter";
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Load Expenses
    |--------------------------------------------------------------------------
    */

    async function fetchExpenses(url = getFilterUrl()) {
        tableContainer.classList.add("opacity-50", "pointer-events-none");

        setFilterLoading(true);

        try {
            const response = await fetch(url, {
                method: "GET",

                headers: {
                    "X-Requested-With": "XMLHttpRequest",

                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Unable to load expenses.");
            }

            /*
            |--------------------------------------------------------------------------
            | Update Table
            |--------------------------------------------------------------------------
            */

            tableContainer.innerHTML = data.html;

            /*
            |--------------------------------------------------------------------------
            | Update Summary
            |--------------------------------------------------------------------------
            */

            updateSummary(data.summary);

            /*
            |--------------------------------------------------------------------------
            | Bind New Pagination
            |--------------------------------------------------------------------------
            */

            bindPagination();

            /*
            |--------------------------------------------------------------------------
            | Bind New Delete Buttons
            |--------------------------------------------------------------------------
            */

            bindDeleteButtons();
        } catch (error) {
            console.error("Expense loading error:", error);

            showToast(error.message || "Unable to load expenses.", "error");
        } finally {
            tableContainer.classList.remove(
                "opacity-50",
                "pointer-events-none",
            );

            setFilterLoading(false);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Summary
    |--------------------------------------------------------------------------
    */

    function updateSummary(summary) {
        if (!summary) {
            return;
        }

        const totalElement = document.getElementById("total-expenses");

        const monthElement = document.getElementById("this-month");

        const entriesElement = document.getElementById("expense-entries");

        if (totalElement) {
            totalElement.textContent = "₹" + summary.total_expenses;
        }

        if (monthElement) {
            monthElement.textContent = "₹" + summary.this_month;
        }

        if (entriesElement) {
            entriesElement.textContent = summary.expense_entries;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Submit
    |--------------------------------------------------------------------------
    */

    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();

            fetchExpenses(getFilterUrl());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    function bindPagination() {
        const links = tableContainer.querySelectorAll("nav a, .pagination a");

        links.forEach(function (link) {
            link.addEventListener("click", function (event) {
                event.preventDefault();

                fetchExpenses(this.href);
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Buttons
    |--------------------------------------------------------------------------
    */

    function bindDeleteButtons() {
        const buttons = tableContainer.querySelectorAll(".delete-expense");

        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                deleteExpense(this);
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Expense
    |--------------------------------------------------------------------------
    */

    async function deleteExpense(button) {
        const url = button.dataset.url;

        if (!url) {
            showToast("Delete URL is missing.", "error");

            return;
        }

        const confirmed = confirm(
            "Are you sure you want to delete this expense?",
        );

        if (!confirmed) {
            return;
        }

        const originalHTML = button.innerHTML;

        button.disabled = true;

        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(url, {
                method: "DELETE",

                headers: {
                    "X-CSRF-TOKEN": csrfToken,

                    "X-Requested-With": "XMLHttpRequest",

                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Unable to delete expense.");
            }

            /*
            |--------------------------------------------------------------------------
            | Reload Current Filter
            |--------------------------------------------------------------------------
            */

            await fetchExpenses(getFilterUrl());

            showToast(
                data.message || "Building expense deleted successfully.",
                "success",
            );
        } catch (error) {
            console.error("Delete expense error:", error);

            button.disabled = false;

            button.innerHTML = originalHTML;

            showToast(error.message || "Unable to delete expense.", "error");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Toast
    |--------------------------------------------------------------------------
    */

    function showToast(message, type = "success") {
        const existingToast = document.getElementById("building-expense-toast");

        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement("div");

        toast.id = "building-expense-toast";

        toast.className =
            "fixed top-5 right-5 z-[9999] " +
            "max-w-sm px-4 py-3 rounded-xl " +
            "shadow-lg text-sm font-medium " +
            "flex items-center gap-3 " +
            (type === "success"
                ? "bg-green-600 text-white"
                : "bg-red-600 text-white");

        const icon = document.createElement("i");

        icon.className =
            "fa-solid " +
            (type === "success" ? "fa-circle-check" : "fa-circle-exclamation");

        const text = document.createElement("span");

        text.textContent = message;

        toast.appendChild(icon);

        toast.appendChild(text);

        document.body.appendChild(toast);

        setTimeout(function () {
            if (toast) {
                toast.remove();
            }
        }, 3000);
    }

    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    */

    const successMessage = document.getElementById("success-message");

    if (successMessage) {
        setTimeout(function () {
            successMessage.remove();
        }, 3000);
    }

    /*
    |--------------------------------------------------------------------------
    | Initial Bindings
    |--------------------------------------------------------------------------
    */

    bindPagination();

    bindDeleteButtons();
});
