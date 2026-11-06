import Alpine from "alpinejs";
import Swal from "sweetalert2";

// Alpine.js
window.Alpine = Alpine;
Alpine.start();

// SweetAlert2
window.Swal = Swal;

window.addEventListener("swal", (event) => {
    Swal.fire({
        icon: event.detail.icon ?? "info",
        title: event.detail.title ?? "Notification",
        text: event.detail.text ?? "",
        confirmButtonColor: "#7c3aed",
    });
});

window.addEventListener("validation-error", (event) => {
    Swal.fire({
        icon: "warning",
        title: "Validation Error",
        text: event.detail.message,
        confirmButtonColor: "#7c3aed",
    });
});
