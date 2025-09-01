const $text = document.getElementsByTagName("html")[0];
const $growBtn = document.getElementById("growBtn");

$growBtn.addEventListener('click', () => {
    $text.classList.toggle("grow-text");
})