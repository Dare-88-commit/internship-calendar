(function () {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');

    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            nav.classList.toggle('is-open');
        });
    }

    // Theme toggle functionality
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const html = document.documentElement;
    const themeIcon = document.querySelector('.theme-icon');

    // Load theme from localStorage or detect system preference
    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        let theme = 'dark'; // default

        if (savedTheme) {
            theme = savedTheme;
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            theme = 'light';
        }

        applyTheme(theme);
    }

    function applyTheme(theme) {
        if (theme === 'light') {
            html.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            if (themeIcon) themeIcon.textContent = '☀️';
        } else {
            html.removeAttribute('data-theme');
            localStorage.setItem('theme', 'dark');
            if (themeIcon) themeIcon.textContent = '🌙';
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
        });
    }

    // Initialize theme on page load
    initializeTheme();

    const today = new Date();
    const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
        .toISOString()
        .slice(0, 10);

    document.querySelectorAll('[data-event-date]').forEach((el) => {
        if (el.getAttribute('data-event-date') === localToday) {
            el.classList.add('today-highlight');
        }
    });

    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('submit', (event) => {
            const message = el.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
})();

