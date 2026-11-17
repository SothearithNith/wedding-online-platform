import Swal from "sweetalert2";
import axios from "axios";
import "./bootstrap";
import "./guest-invite";

window.Swal = Swal;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.swalToast = (type, title, text = "") => {
    Swal.fire({
        icon: type,
        title,
        text,
        toast: true,
        position: "top-end",
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false,
    });
};

document.addEventListener("livewire:init", () => {
    // Single global listener. Supports both dispatch styles:
    //   dispatch('swal', icon: 'error', ...)   -> modal (login page)
    //   dispatch('swal', type: 'success', ...) -> toast (admin pages)
    Livewire.on("swal", (event) => {
        const d = Array.isArray(event) ? event[0] : (event.detail ?? event);

        if (d.type) {
            window.swalToast(d.type, d.title ?? "", d.text ?? "");
            return;
        }

        Swal.fire({
            icon: d.icon ?? "info",
            title: d.title ?? "Notification",
            text: d.text ?? "",
            confirmButtonColor: "#7c3aed",
        });
    });

    Livewire.hook("request", ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                preventDefault();
                window.location.reload();
            }
        });
    });
});
