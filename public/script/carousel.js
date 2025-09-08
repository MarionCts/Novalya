// PHOTOS CAROUSEL FOR HOMEPAGE & INDIVDUAL PROPERTY PAGE

document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.carousel-btn.prev');
    const nextBtn = document.querySelector('.carousel-btn.next');
    let current = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }

    prevBtn.addEventListener('click', () => {
        current = (current === 0) ? slides.length - 1 : current - 1;
        showSlide(current);
    });

    nextBtn.addEventListener('click', () => {
        current = (current === slides.length - 1) ? 0 : current + 1;
        showSlide(current);
    });

    // Optionnel : auto-slide toutes les 5s
    setInterval(() => {
        current = (current === slides.length - 1) ? 0 : current + 1;
        showSlide(current);
    }, 5000);
});
