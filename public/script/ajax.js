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
            const modal = document.getElementById("favoriteModal");
            const modalMessage = document.getElementById(
                "modalFavoriteMessage"
            );
            const closeBtn = document.getElementById("closeBtn");

            const translations = {
                fr: {
                    "favorite.message":
                        "Vous devez être connecté pour mettre une propriété en favori.",
                },
                en: {
                    "favorite.message":
                        "You need to be logged in to add a favorite property.",
                },
            };

            // Creating a function to customize the translation regarding the locale (= 'key')
            function translation(key) {
                if (key) {
                    return translations[locale][key]; // locale will be either 'FR' or 'EN', and the key will be the parameter of the translation (= modal.delete)
                }
            }

            let message = translation("favorite.message");
            modalMessage.textContent = message;
            modal.classList.remove("hides-modal");

            closeBtn.addEventListener("click", function () {
                modal.classList.add("hides-modal");
            });
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
