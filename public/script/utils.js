// NEWSLETTER FORM

const newsBtn = document.getElementById("newsBtn");

if (newsBtn) {
    newsBtn.addEventListener("click", function (event) {
        event.preventDefault();
    });
}

// RETURN TO TOP BUTTON SCROLLING/FADING EFFECT

const returnBtn = document.getElementById("returnBtn");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        returnBtn.classList.add("appears");
    } else if (window.scrollY < 300) {
        returnBtn.classList.remove("appears");
    }
});
