document.addEventListener("livewire:initialized", () => {
    if (!window.Echo || !window.Livewire) return;

    const userId = document
        .querySelector('meta[name="user-id"]')
        ?.getAttribute("content");

    if (!userId) return;

    window.Echo.private(`user.${userId}`).listen(".ZipGerado", (e) => {
        console.log("[Reverb] Evento Recebido:", e);
        Livewire.dispatch("zip-gerado", { url: e.download_url });
    });

    window.addEventListener("zip-finalizado", () => {
        const btn = document.getElementById("btn-gerar-zip");
        if (!btn) return;

        btn.disabled = false;
        btn.querySelector(".btn-img").classList.remove("hidden");
        btn.querySelector(".btn-spinner").classList.add("hidden");
    });

    window.startZip = function (button, cursoId, chamadaId) {
        button.disabled = true;

        button.querySelector(".btn-img").classList.add("hidden");
        button.querySelector(".btn-spinner").classList.remove("hidden");

        Livewire.dispatch("gerar-zip", {
            cursoId,
            chamadaId,
        });
    };
});
