import "./bootstrap";

const sidebarToggle = document.querySelector(".sidebar-toggle");
const sidebar = document.querySelector(".app-sidebar");

sidebarToggle?.addEventListener("click", () => {
    const isOpen = sidebar?.classList.toggle("hidden") === false;
    sidebar?.classList.toggle("flex", isOpen);
    sidebarToggle.setAttribute("aria-expanded", String(isOpen));
});

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
