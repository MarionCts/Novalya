const openBtn = document.getElementById("hamburger");
const overlay = document.getElementById("mainmenu");
const closeBtn = document.getElementById("closemenu");

// Function to open the hamburger menu
function openMenu() {
    overlay.classList.add("is-open");
    document.body.classList.add("scroll-back");
    openBtn.setAttribute("aria-expanded", "true");
}

// Function to close the menu
function closeMenu() {
    overlay.classList.remove("is-open");
    document.body.classList.remove("scroll-back");
    openBtn.setAttribute("aria-expanded", "false");
}

// listening to the click on the buttons to either open or close the menu
openBtn.addEventListener("click", openMenu);
closeBtn.addEventListener("click", closeMenu);