const lightBtn = document.getElementById("lightTheme");
const darkBtn = document.getElementById("darkTheme");

// Decides which theme should be applied according to the choice of the user
function applyTheme(theme) {
    document.body.setAttribute("data-theme", theme);

    if (theme === "light") {
        lightBtn.classList.add("is-hidden");
        darkBtn.classList.remove("is-hidden");
    } else {
        darkBtn.classList.add("is-hidden");
        lightBtn.classList.remove("is-hidden");
    }

    // Gets the user's prefered theme memorized in LocalStorage
    localStorage.setItem("theme", theme);
}

// Listens to the switch buttons to decide which theme to apply
lightBtn.addEventListener("click", () => applyTheme("light"));
darkBtn.addEventListener("click", () => applyTheme("dark"));

// Retrieves the user's prefered theme that was memorized in LocalStorage, and applies said theme
(function initTheme() {
    const saved = localStorage.getItem("theme");
    if (saved) {
        applyTheme(saved);
    }
})();
