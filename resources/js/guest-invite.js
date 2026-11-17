// resources/js/guest-invite.js
window.guestInvite = async function ({ url, groom, bride, labels }) {
    const KEY = "recent_guests";

    // Heroicons (outline) paths
    const ICONS = {
        envelope:
            "M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75",
        clipboard:
            "M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75",
        send: "M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5",
        chat: "M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z",
    };
    const icon = (name, cls = "h-6 w-6") =>
        `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="${cls}"><path stroke-linecap="round" stroke-linejoin="round" d="${ICONS[name]}"/></svg>`;

    const load = () => {
        try {
            return JSON.parse(localStorage.getItem(KEY) || "[]");
        } catch {
            return [];
        }
    };
    const save = (n) => {
        if (!n) return;
        try {
            localStorage.setItem(
                KEY,
                JSON.stringify(
                    [n, ...load().filter((x) => x !== n)].slice(0, 6),
                ),
            );
        } catch {}
    };
    const esc = (s) =>
        String(s).replace(
            /[&<>"']/g,
            (c) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                })[c],
        );
    const build = (name) =>
        name
            ? url +
              (url.includes("?") ? "&" : "?") +
              "guest=" +
              encodeURIComponent(name)
            : url;

    const chips = load()
        .map(
            (n) =>
                `<button type="button" data-chip="${esc(n)}" class="rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 transition hover:bg-violet-100">${esc(n)}</button>`,
        )
        .join("");

    await Swal.fire({
        width: 440,
        padding: 0,
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: "!rounded-3xl !overflow-hidden",
            htmlContainer: "!m-0 !p-0",
        },
        showClass: {
            popup: "animate__animated animate__fadeInUp animate__faster",
        },
        html: `
        <div class="text-left">
            <div class="bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-6 text-center text-white">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 text-white">${icon("envelope", "h-6 w-6")}</div>
                <h3 class="text-lg font-bold">${esc(labels.title)}</h3>
                <p class="mt-1 text-sm text-indigo-100">${esc(groom || "—")} &amp; ${esc(bride || "—")}</p>
            </div>

            <div class="space-y-4 px-6 py-5">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">${esc(labels.guest)}</label>
                    <input id="gi-name" type="text" autocomplete="off" placeholder="${esc(labels.placeholder)}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-base text-gray-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
                </div>

                ${chips ? `<div><p class="mb-1.5 text-xs text-gray-400">${esc(labels.recent)}</p><div class="flex flex-wrap gap-1.5">${chips}</div></div>` : ""}

                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-3">
                    <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">${esc(labels.preview)}</p>
                    <p id="gi-preview" class="break-all text-xs text-gray-700"></p>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button id="gi-copy" type="button" class="flex flex-col items-center gap-1.5 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 py-3 text-xs font-semibold text-white shadow-md transition hover:brightness-110 active:scale-95">
                        ${icon("clipboard", "h-5 w-5")}${esc(labels.copy)}
                    </button>
                    <button id="gi-tg" type="button" class="flex flex-col items-center gap-1.5 rounded-xl bg-sky-500 py-3 text-xs font-semibold text-white shadow-md transition hover:bg-sky-600 active:scale-95">
                        ${icon("send", "h-5 w-5")}Telegram
                    </button>
                    <button id="gi-wa" type="button" class="flex flex-col items-center gap-1.5 rounded-xl bg-green-500 py-3 text-xs font-semibold text-white shadow-md transition hover:bg-green-600 active:scale-95">
                        ${icon("chat", "h-5 w-5")}WhatsApp
                    </button>
                </div>
            </div>
        </div>`,
        didOpen: (popup) => {
            const input = popup.querySelector("#gi-name");
            const preview = popup.querySelector("#gi-preview");
            const update = () => {
                preview.textContent = decodeURIComponent(
                    build(input.value.trim()),
                );
            };
            update();
            input.focus();
            input.addEventListener("input", update);

            popup.querySelectorAll("[data-chip]").forEach((b) =>
                b.addEventListener("click", () => {
                    input.value = b.dataset.chip;
                    update();
                    input.focus();
                }),
            );

            const done = (fn) => {
                const name = input.value.trim();
                save(name);
                fn(build(name), name);
                Swal.close();
            };

            popup.querySelector("#gi-copy").addEventListener("click", () =>
                done((link) => {
                    navigator.clipboard.writeText(link);
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: labels.copied,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }),
            );
            popup
                .querySelector("#gi-tg")
                .addEventListener("click", () =>
                    done((link, name) =>
                        window.open(
                            "https://t.me/share/url?url=" +
                                encodeURIComponent(link) +
                                "&text=" +
                                encodeURIComponent(name),
                            "_blank",
                        ),
                    ),
                );
            popup
                .querySelector("#gi-wa")
                .addEventListener("click", () =>
                    done((link, name) =>
                        window.open(
                            "https://wa.me/?text=" +
                                encodeURIComponent(
                                    (name ? name + "\n" : "") + link,
                                ),
                            "_blank",
                        ),
                    ),
                );
            input.addEventListener("keydown", (e) => {
                if (e.key === "Enter") popup.querySelector("#gi-copy").click();
            });
        },
    });
};
