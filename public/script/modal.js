// MODAL WINDOWS

// Fetching the elements for the Delete Modal

const deleteButtons = document.querySelectorAll(".button__delete");
const modal = document.getElementById("deleteConfirmationModal");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const modalTitle = document.getElementById("modalDeleteTitle");
const modalMessage = document.getElementById("modalDeleteMessage");
let formToSubmit = null;

// Translations FR & EN

const translations = {
    fr: {
        "delete.title": "Confirmer la suppression",
        "delete.message":
            "Êtes-vous sûr de vouloir continuer ? Cette action est irréversible.",
    },
    en: {
        "modal.title": "Confirm deletion",
        "delete.message":
            "Are you sure you want to keep going? This action is irreversible.",
    },
};

// Fetching the value of the local language that is set (= 'FR' or 'EN', by default 'fr')
const locale = document.documentElement.dataset.lang || window.currentLocale || "fr";

// Creating a function to customize the translation regarding the locale (= 'key')
function translation(key) {

    if (key) {
    return translations[locale][key]; // locale will be either 'FR' or 'EN', and the key will be the parameter of the translation (= modal.delete)
    } 
}

// Fetches all the existing delete buttons, and prevents the default behavior of the submit button for all
deleteButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
        event.preventDefault(); 
        formToSubmit = this.closest("form"); 

        // Fetches the dataset ID of each button
        const propertyId = this.dataset.propertyId;
        const categoryId = this.dataset.categoryId;
        const tagId = this.dataset.tagId;
        const userId = this.dataset.userId;

        // Writes the custom translations of the title and the message for each case (deletion, newsletter, etc)
        if (propertyId || categoryId || tagId || userId) {
            let title = translation("delete.title");
            modalTitle.textContent = title;
            let message = translation("delete.message");
            modalMessage.textContent = message;
        }

        modal.classList.remove("hides-modal");
    });
});

confirmDeleteBtn.addEventListener("click", function () {
    if (formToSubmit) {
        formToSubmit.submit(); // Submits the form
    }
    modal.classList.add("hides-modal");
});

cancelDeleteBtn.addEventListener("click", function () {
    modal.classList.add("hides-modal");
    formToSubmit = null; // Resets the form
});
