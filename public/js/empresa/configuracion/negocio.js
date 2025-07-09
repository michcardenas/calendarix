document.addEventListener("DOMContentLoaded", function () {
    const btnEdit = document.getElementById("btn-edit-neg-info");
    const btnCancel = document.getElementById("btn-cancel-neg-info");
    const form = document.getElementById("form-edit-neg-info");
    const display = document.getElementById("neg-info-display");

    btnEdit?.addEventListener("click", () => {
        form.classList.remove("d-none");
        display.classList.add("d-none");
    });

    btnCancel?.addEventListener("click", () => {
        form.classList.add("d-none");
        display.classList.remove("d-none");
    });
});

