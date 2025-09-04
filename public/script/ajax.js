// ------------- AJAX BEHAVIOR FOR FAVORITE BUTTON -------------

document.addEventListener("DOMContentLoaded", () => {
    const favorite = async (form) => {
        const btn = form.querySelector("button[type='submit']");
        const formData = new FormData(form);
        const id = form.dataset.id;

        // Disables the 'submit' button on the form to prevent spam actions
        btn?.setAttribute("disabled", "true");

        try {
            const res = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            });

            // Non authentifié → redirige proprement
            if (res.status === 401) {
                window.location.href = "/login";
                return;
            }

            // Mauvais type de réponse → erreur lisible
            const ct = res.headers.get("Content-Type") || "";
            if (!ct.includes("application/json")) {
                throw new Error("Réponse non-JSON du serveur.");
            }

            if (!res.ok) {
                const text = await res.text();
                throw new Error(`Erreur serveur: ${res.status} - ${text}`);
            }

            // Here, 'data' fetches the controller parameters 'propertyId' & 'favorited'
            const data = await res.json();

            const favoriteNoneIcon = document.getElementById(
                `favorite-none-${id}`
            );
            const favoriteDoneIcon = document.getElementById(
                `favorite-done-${id}`
            );

            if (!favoriteNoneIcon || !favoriteDoneIcon) {
                console.warn("Icônes de favori introuvables pour id", id);
                return;
            }

            // Idempotent: on force l'état au lieu de toggle à l’aveugle
            if (data.favorited) {
                favoriteNoneIcon.classList.remove("favorite-toggle"); // the empty heart icon is hidden
                favoriteDoneIcon.classList.add("favorite-toggle"); // and the full heart icon shows
                form.setAttribute("aria-pressed", "true");
            } else {
                favoriteNoneIcon.classList.add("favorite-toggle"); // the empty heart icon shows again
                favoriteDoneIcon.classList.remove("favorite-toggle"); // and the full heart icon is hidden
                form.setAttribute("aria-pressed", "false");
            }
        } catch (error) {
            alert("Une erreur est survenue lors du like.");
            console.error("Erreur lors du like:", error);
        } finally {
            btn?.removeAttribute("disabled"); // The 'submit' button that was disabled because of the spam is abled again
        }
    };

    document.querySelectorAll(".favorite-form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            favorite(form);
        });
    });
});
