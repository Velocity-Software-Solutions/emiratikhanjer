import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const submitBtn = form.querySelector(".submit-btn");

    form.addEventListener("submit", (e) => {
        // Optional: Disable the button to prevent multiple clicks
        submitBtn.disabled = true;

        // Clear previous spinner (if any)
        const existingSpinner = submitBtn.querySelector(".spinner");
        if (existingSpinner) existingSpinner.remove();

        // Add spinner
        const spinner = document.createElement("div");
        spinner.classList.add("spinner");
        submitBtn.textContent = "";
        submitBtn.appendChild(spinner);
    });

    document.querySelectorAll(".toggle").forEach((button) => {
        button.addEventListener("click", function () {
            const icon = this.querySelector("span.material-icons:last-of-type");
            icon.classList.toggle("-rotate-90");
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle("hidden");
        });
    });

    document
        .getElementById("file-upload")
        .addEventListener("change", function () {
            const fileName =
                this.files.length > 0 ? this.files[0].name : "No file chosen";
            document.getElementById("file-name").textContent = fileName;
        });
});
