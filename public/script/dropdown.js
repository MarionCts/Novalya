// DROPDOWN BEHAVIOR FOR PROPERTIES

const propertiesDropdown = document.getElementById("properties-dropdown");
const propertiesMenu = document.getElementById("properties-dropdown-menu");
const propertiesOpenIcon = document.getElementById("properties-icon-open");
const propertiesCloseIcon = document.getElementById("properties-icon-close");

// Function to open or close the dropdown when the link is clicked
propertiesDropdown.addEventListener("click", (e) => {
  e.preventDefault();
  const isOpen = propertiesMenu.classList.toggle("opens");

  propertiesDropdown.setAttribute("aria-expanded", String(isOpen));
  propertiesOpenIcon.classList.toggle("hidden", isOpen);
  propertiesCloseIcon.classList.toggle("hidden", !isOpen);

});

// DROPDOWN BEHAVIOR FOR CATEGORIES

const categoriesDropdown = document.getElementById("categories-dropdown");
const categoriesMenu = document.getElementById("categories-dropdown-menu");
const categoriesOpenIcon = document.getElementById("categories-icon-open");
const categoriesCloseIcon = document.getElementById("categories-icon-close");

// Function to open or close the dropdown when the link is clicked
categoriesDropdown.addEventListener("click", (e) => {
  e.preventDefault();
  const isOpen = categoriesMenu.classList.toggle("opens");

  categoriesDropdown.setAttribute("aria-expanded", String(isOpen));
  categoriesOpenIcon.classList.toggle("hidden", isOpen);
  categoriesCloseIcon.classList.toggle("hidden", !isOpen);

});
