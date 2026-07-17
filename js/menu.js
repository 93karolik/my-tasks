const burger = document.querySelector('.header__burger');
const nav = document.querySelector('.header__nav');

burger.addEventListener('click', () => {
    const isOpen = burger.classList.toggle('header__burger--is-open');

    nav.classList.toggle('header__nav--is-open', isOpen);
    document.body.classList.toggle('menu-open', isOpen);

    burger.setAttribute('aria-expanded', isOpen);
    burger.setAttribute(
        'aria-label',
        isOpen ? 'Close menu' : 'Open menu'
    );
});