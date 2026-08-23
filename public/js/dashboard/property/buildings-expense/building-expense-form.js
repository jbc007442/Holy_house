document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("building-expense-form");

    if (!form) {
        return;
    }

    const submitButton = document.getElementById("submit-expense-btn");
    const submitIcon = document.getElementById("submit-expense-icon");
    const submitText = document.getElementById("submit-expense-text");

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    const redirectUrl = form.dataset.redirectUrl;

    /*
    |--------------------------------------------------------------------------
    | Building Search
    |--------------------------------------------------------------------------
    */

    const buildingSearch = document.getElementById("building_search");
    const buildingId = document.getElementById("building_id");
    const buildingResults = document.getElementById("building-results");

    if (buildingSearch && buildingId && buildingResults) {
        const buildingOptions = Array.from(
            buildingResults.querySelectorAll(".building-option"),
        );

        /*
        |--------------------------------------------------------------------------
        | Open Building Results
        |--------------------------------------------------------------------------
        */

        buildingSearch.addEventListener("focus", function () {
            filterBuildings();
        });

        /*
        |--------------------------------------------------------------------------
        | Search Building
        |--------------------------------------------------------------------------
        */

        buildingSearch.addEventListener("input", function () {
            /*
            |--------------------------------------------------------------------------
            | Clear selected ID when user changes the text
            |--------------------------------------------------------------------------
            */

            buildingId.value = "";

            filterBuildings();
        });

        /*
        |--------------------------------------------------------------------------
        | Select Building
        |--------------------------------------------------------------------------
        */

        buildingOptions.forEach(function (option) {
            option.addEventListener("click", function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                buildingId.value = id;
                buildingSearch.value = name;

                buildingResults.classList.add("hidden");

                buildingSearch.classList.remove("border-red-400");

                removeFieldError("building_id");
                removeFieldError("building_search");
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Close Results Outside Click
        |--------------------------------------------------------------------------
        */

        document.addEventListener("click", function (event) {
            if (
                !buildingSearch.contains(event.target) &&
                !buildingResults.contains(event.target)
            ) {
                buildingResults.classList.add("hidden");
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Filter Buildings
        |--------------------------------------------------------------------------
        */

        function filterBuildings() {
            const search = buildingSearch.value.trim().toLowerCase();

            let visibleCount = 0;

            buildingOptions.forEach(function (option) {
                const name = (option.dataset.name || "").toLowerCase();

                if (search === "" || name.includes(search)) {
                    option.classList.remove("hidden");

                    visibleCount++;
                } else {
                    option.classList.add("hidden");
                }
            });

            if (visibleCount > 0) {
                buildingResults.classList.remove("hidden");
            } else {
                buildingResults.classList.add("hidden");
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        if (!submitButton || submitButton.disabled) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Building Validation
        |--------------------------------------------------------------------------
        |
        | The user may type a building name, but they must select one of the
        | existing buildings so that building_id is available.
        |
        */

        if (buildingSearch && buildingId && !buildingId.value) {
            clearErrors();

            buildingSearch.classList.add("border-red-400");

            showFieldError(
                buildingSearch,
                "Please select a building from the list.",
            );

            buildingSearch.focus();

            return;
        }

        const originalText = submitText ? submitText.textContent : "";

        const originalIcon = submitIcon ? submitIcon.className : "";

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */

        submitButton.disabled = true;

        if (submitIcon) {
            submitIcon.className = "fa-solid fa-spinner fa-spin mr-2";
        }

        if (submitText) {
            submitText.textContent = "Saving...";
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Previous Errors
        |--------------------------------------------------------------------------
        */

        clearErrors();

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: "POST",

                headers: {
                    "X-CSRF-TOKEN": csrfToken,

                    "X-Requested-With": "XMLHttpRequest",

                    Accept: "application/json",
                },

                body: formData,
            });

            let data = {};

            try {
                data = await response.json();
            } catch (error) {
                throw new Error(
                    "Invalid server response. Please check Laravel logs.",
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validation Error
            |--------------------------------------------------------------------------
            */

            if (response.status === 422) {
                displayValidationErrors(data.errors || {});

                throw new Error("Please correct the highlighted fields.");
            }

            /*
            |--------------------------------------------------------------------------
            | Server Error
            |--------------------------------------------------------------------------
            */

            if (!response.ok) {
                throw new Error(
                    data.message || "Unable to save building expense.",
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Application Error
            |--------------------------------------------------------------------------
            */

            if (!data.success) {
                throw new Error(
                    data.message || "Unable to save building expense.",
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            const message =
                data.message || "Building expense saved successfully.";

            if (!redirectUrl) {
                throw new Error("Redirect URL is missing.");
            }

            window.location.href =
                redirectUrl + "?success=" + encodeURIComponent(message);
        } catch (error) {
            console.error("Building expense error:", error);

            /*
            |--------------------------------------------------------------------------
            | Don't show generic toast for validation errors
            |--------------------------------------------------------------------------
            */

            if (error.message !== "Please correct the highlighted fields.") {
                showToast(error.message || "Something went wrong.", "error");
            }

            /*
            |--------------------------------------------------------------------------
            | Restore Button
            |--------------------------------------------------------------------------
            */

            submitButton.disabled = false;

            if (submitIcon) {
                submitIcon.className = originalIcon;
            }

            if (submitText) {
                submitText.textContent = originalText;
            }
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Display Validation Errors
    |--------------------------------------------------------------------------
    */

    function displayValidationErrors(errors) {
        Object.entries(errors).forEach(function ([field, messages]) {
            /*
            |--------------------------------------------------------------------------
            | Normal Field
            |--------------------------------------------------------------------------
            */

            const input = form.querySelector(`[name="${field}"]`);

            /*
            |--------------------------------------------------------------------------
            | Building ID
            |--------------------------------------------------------------------------
            |
            | building_id is hidden, so display the error on the visible
            | building search field instead.
            |
            */

            if (field === "building_id" && buildingSearch) {
                buildingSearch.classList.add("border-red-400");

                showFieldError(
                    buildingSearch,
                    Array.isArray(messages) ? messages[0] : messages,
                );

                return;
            }

            if (!input) {
                return;
            }

            input.classList.add("border-red-400");

            const message = Array.isArray(messages) ? messages[0] : messages;

            showFieldError(input, message);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Show Field Error
    |--------------------------------------------------------------------------
    */

    function showFieldError(input, message) {
        /*
        |--------------------------------------------------------------------------
        | Avoid Duplicate Error
        |--------------------------------------------------------------------------
        */

        const existing = input.parentNode.querySelector(".ajax-error");

        if (existing) {
            existing.remove();
        }

        const errorElement = document.createElement("p");

        errorElement.className = "ajax-error text-xs text-red-500 mt-1";

        errorElement.textContent = message;

        /*
        |--------------------------------------------------------------------------
        | Relative Wrapper
        |--------------------------------------------------------------------------
        */

        const wrapper = input.closest(".relative");

        if (wrapper) {
            wrapper.parentNode.appendChild(errorElement);
        } else {
            input.parentNode.appendChild(errorElement);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Field Error
    |--------------------------------------------------------------------------
    */

    function removeFieldError(name) {
        const input = form.querySelector(`[name="${name}"]`);

        if (!input) {
            return;
        }

        input.classList.remove("border-red-400");

        const error = input.parentNode.querySelector(".ajax-error");

        if (error) {
            error.remove();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Clear Errors
    |--------------------------------------------------------------------------
    */

    function clearErrors() {
        form.querySelectorAll(".ajax-error").forEach(function (element) {
            element.remove();
        });

        form.querySelectorAll(".border-red-400").forEach(function (element) {
            element.classList.remove("border-red-400");
        });
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
        }, 3500);
    }
});
