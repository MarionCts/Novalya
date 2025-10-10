// ---------------- PANEL ADMIN ----------------

// DROPDOWN BEHAVIOR FOR PROPERTIES

const propertiesDropdown = document.getElementById("properties-dropdown");
const propertiesMenu = document.getElementById("properties-dropdown-menu");
const propertiesIcon = document.getElementById("properties-icon");

if (propertiesDropdown) {
    // Function to open or close the dropdown when the link is clicked
    propertiesDropdown.addEventListener("click", (e) => {
        e.preventDefault();
        const isOpen = propertiesMenu.classList.toggle("opens");

        propertiesDropdown.setAttribute("aria-expanded", String(isOpen));
        propertiesIcon.classList.toggle("rotates", isOpen);
    });
}

// DROPDOWN BEHAVIOR FOR CATEGORIES

const categoriesDropdown = document.getElementById("categories-dropdown");
const categoriesMenu = document.getElementById("categories-dropdown-menu");
const categoriesIcon = document.getElementById("categories-icon");

if (categoriesDropdown) {
    // Function to open or close the dropdown when the link is clicked
    categoriesDropdown.addEventListener("click", (e) => {
        e.preventDefault();
        const isOpen = categoriesMenu.classList.toggle("opens");

        categoriesDropdown.setAttribute("aria-expanded", String(isOpen));
        categoriesIcon.classList.toggle("rotates", isOpen);
    });
}

// DROPDOWN BEHAVIOR FOR TAGS

const tagsDropdown = document.getElementById("tags-dropdown");
const tagsMenu = document.getElementById("tags-dropdown-menu");
const tagsIcon = document.getElementById("tags-icon");

if (tagsDropdown) {
    // Function to open or close the dropdown when the link is clicked
    tagsDropdown.addEventListener("click", (e) => {
        e.preventDefault();
        const isOpen = tagsMenu.classList.toggle("opens");

        tagsDropdown.setAttribute("aria-expanded", String(isOpen));
        tagsIcon.classList.toggle("rotates", isOpen);
    });
}

// ---------------- ACCOUNT PAGE ----------------

// DROPDOWN BEHAVIOR FOR ACCOUNT MANAGEMENT

const accountDropdown = document.getElementById("account-dropdown");
const accountMenu = document.getElementById("account-dropdown-menu");
const accountIcon = document.getElementById("account-icon");

if (accountDropdown) {
    // Function to open or close the dropdown when the link is clicked
    accountDropdown.addEventListener("click", (e) => {
        e.preventDefault();
        const isOpen = accountMenu.classList.toggle("opens");

        accountDropdown.setAttribute("aria-expanded", String(isOpen));
        accountIcon.classList.toggle("rotates", isOpen);
    });
}

// DROPDOWN BEHAVIOR FOR FAVORITES SECTION

const favoritesDropdown = document.getElementById("favorites-dropdown");
const favoritesMenu = document.getElementById("favorites-dropdown-menu");
const favoritesIcon = document.getElementById("favorites-icon");

if (favoritesDropdown) {
    // Function to open or close the dropdown when the link is clicked
    favoritesDropdown.addEventListener("click", (e) => {
        e.preventDefault();
        const isOpen = favoritesMenu.classList.toggle("opens");

        favoritesDropdown.setAttribute("aria-expanded", String(isOpen));
        favoritesIcon.classList.toggle("rotates", isOpen);
    });
}

// DISPLAY SWITCH BEHAVIOR BETWEEN THE SECTIONS OF THE DROPDOWN MENU

// Sections
const favorites = document.getElementById("favorites");
const info = document.getElementById("account-info");
const deleteAccount = document.getElementById("account-delete");

// Buttons
const infoBtn = document.getElementById("info-btn");
const deleteBtn = document.getElementById("delete-btn");
const favoriteBtn = document.getElementById("favorite-btn");

if (favorites) {
    favoriteBtn.addEventListener("click", (e) => {
        favorites.classList.remove("hidden");
        favoriteBtn.classList.add("active");
        infoBtn.classList.remove("active");
        info.classList.add("hidden");
        deleteBtn.classList.remove("active");
        deleteAccount.classList.add("hidden");
    });
}

if (info) {
    infoBtn.addEventListener("click", (e) => {
        info.classList.remove("hidden");
        infoBtn.classList.add("active");
        favorites.classList.add("hidden");
        favoriteBtn.classList.remove("active");
        deleteAccount.classList.add("hidden");
        deleteBtn.classList.remove("active");
    });
}


if (deleteAccount) {
    deleteBtn.addEventListener("click", (e) => {
        deleteAccount.classList.remove("hidden");
        deleteBtn.classList.add("active");
        info.classList.add("hidden");
        infoBtn.classList.remove("active");
        favorites.classList.add("hidden");
        favoriteBtn.classList.remove("active");
    });
}