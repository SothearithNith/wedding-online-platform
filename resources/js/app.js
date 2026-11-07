import Swal from "sweetalert2";
import axios from "axios";

window.Swal = Swal;
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

document.addEventListener("livewire:init", () => {
    Livewire.on("swal", (event) => {
        const data = event.detail ?? event;

        Swal.fire({
            icon: data.icon ?? "info",
            title: data.title ?? "Notification",
            text: data.text ?? "",
            confirmButtonColor: "#7c3aed",
        });
    });
});
