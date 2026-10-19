/**
 * Frontend Main JavaScript
 * School Portal Scripts & Swiper Slider Initialization
 */
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Hero Swiper Slider
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: true,
            speed: 700,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.hero-swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.hero-swiper-next',
                prevEl: '.hero-swiper-prev',
            },
        });
    }
});

