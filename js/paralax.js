const parallaxItems = document.querySelectorAll('.js-parallax');
const paralax = document.querySelector('.hero');

let ticking = false;

function updateParallax() {
    const heroRect = paralax.getBoundingClientRect();

    const offset = -heroRect.top;

    parallaxItems.forEach((item, index) => {
        const speed = index === 0 ? 0.2 : 0.35;

        item.style.transform = `translate3d(0, ${offset * speed}px, 0)`;
    });

    ticking = false;
}

window.addEventListener('scroll', () => {
    if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
    }
});

updateParallax();