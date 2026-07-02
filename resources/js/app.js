import './bootstrap';

const burger = document.getElementById('navbar-burger');
const mobileMenu = document.getElementById('navbar-mobile');
const userMenuBtn = document.getElementById('user-menu-btn');
const userDropdown = document.getElementById('user-dropdown');

if (burger && mobileMenu) {
    burger.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.toggle('open');
        burger.setAttribute('aria-expanded', String(isOpen));
        mobileMenu.setAttribute('aria-hidden', String(!isOpen));
    });
}

if (userMenuBtn && userDropdown) {
    userMenuBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = userDropdown.classList.toggle('open');
        userMenuBtn.setAttribute('aria-expanded', String(isOpen));
    });

    document.addEventListener('click', (event) => {
        if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.remove('open');
            userMenuBtn.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            userDropdown.classList.remove('open');
            userMenuBtn.setAttribute('aria-expanded', 'false');
        }
    });
}
