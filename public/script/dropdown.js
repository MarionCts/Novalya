const dropdown = document.getElementById("admin-dropdown");
const menu = document.getElementById("dropdown-menu");
const dropdownOpenIcon = document.getElementById("dropdown-icon-open");
const dropdownCloseIcon = document.getElementById("dropdown-icon-close");

// Function to open or close the dropdown when the link is clicked
dropdown.addEventListener("click", (e) => {
  e.preventDefault();
  const isOpen = menu.classList.toggle("opens");

  dropdown.setAttribute("aria-expanded", String(isOpen));
  dropdownOpenIcon.classList.toggle("hidden", isOpen);
  dropdownCloseIcon.classList.toggle("hidden", !isOpen);

});
