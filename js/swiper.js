const current = document.querySelector('.pagination-slider__counter--current');
const total = document.querySelector('.pagination-slider__counter--total');

const swiper = new Swiper('.mySwiper', {
    slidesPerView: 4,
    spaceBetween: 20,

    breakpoints: {
        320: {
            slidesPerView: 1,
        },
        576: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        1200: {
            slidesPerView: 4,
        },
    },

    pagination: {
        el: '.pagination-slider__progress',
        type: 'progressbar',
    },

    navigation: {
        nextEl: '.control-slider__button--right',
        prevEl: '.control-slider__button--left',
    },

    on: {
        init(swiper) {
            updateCounter(swiper);
        },

        slideChange(swiper) {
            updateCounter(swiper);
        },

        breakpoint(swiper) {
            updateCounter(swiper);
        },
    },
});

function updateCounter(swiper) {
    current.textContent = swiper.realIndex + 1;

    total.textContent =
        swiper.slides.length - swiper.params.slidesPerView + 1;
}