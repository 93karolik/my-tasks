const body = document.body;
const themeSwitch = document.querySelector('.btn--theme-switch');

const THEME_KEY = 'theme';

const savedTheme = localStorage.getItem(THEME_KEY);

if (savedTheme) {
    body.dataset.theme = savedTheme;
}
themeSwitch.addEventListener('click', () => {
    const newTheme = body.dataset.theme === 'light'
        ? 'dark'
        : 'light';
    body.dataset.theme = newTheme;
    localStorage.setItem(THEME_KEY, newTheme);
});