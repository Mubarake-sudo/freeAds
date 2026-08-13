import './bootstrap';

function initFlashMessages() {
    document.querySelectorAll('.alert').forEach((alert) => {
        const closeAlert = () => {
            alert.classList.add('alert-fading');
            setTimeout(() => alert.remove(), 220);
        };

        const closeButton = alert.querySelector('.alert-close');
        if (closeButton) {
            closeButton.addEventListener('click', closeAlert);
        }

        setTimeout(closeAlert, 4000);
    });
}

function initPhotoPreview(inputId, previewId, placeholderId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const placeholder = placeholderId ? document.getElementById(placeholderId) : null;

    if (!input || !preview) {
        return;
    }

    input.addEventListener('change', (event) => {
        const file = event.target.files && event.target.files[0];

        if (!file) {
            preview.style.display = 'none';
            if (placeholder) {
                placeholder.style.display = 'block';
            }
            return;
        }

        const reader = new FileReader();
        reader.onload = (loadEvent) => {
            preview.src = loadEvent.target.result;
            preview.style.display = 'block';
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    });
}

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

initFlashMessages();
initPhotoPreview('photo', 'photo-preview', 'file-placeholder');
console.log('VORTEX ADS marketplace loaded successfully!');
