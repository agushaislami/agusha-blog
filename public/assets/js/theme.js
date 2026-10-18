(() => {
    const root = document.documentElement;
    const storageKey = 'agusha-blog-theme';
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

    const readPreference = () => {
        try {
            return localStorage.getItem(storageKey) ?? 'system';
        } catch {
            return 'system';
        }
    };

    let preference = readPreference();

    const applyTheme = () => {
        const theme = preference === 'system' ? (mediaQuery.matches ? 'dark' : 'light') : preference;
        root.dataset.theme = theme;
        root.dataset.themePreference = preference;
        const toggle = document.getElementById('themeToggle');
        const nextTheme = theme === 'dark' ? 'light' : 'dark';
        const nextLabel = nextTheme === 'dark' ? 'Aktifkan mode gelap' : 'Aktifkan mode terang';

        if (toggle) {
            toggle.innerHTML = `<i class="fas fa-${nextTheme === 'dark' ? 'moon' : 'sun'}" aria-hidden="true"></i>`;
            toggle.setAttribute('aria-label', nextLabel);
            toggle.setAttribute('title', nextLabel);
            toggle.setAttribute('aria-pressed', String(theme === 'dark'));
        }
    };

    document.addEventListener('click', (event) => {
        const toggle = event.target.closest('#themeToggle');

        if (!toggle) {
            return;
        }

        const currentTheme = root.dataset.theme;
        preference = currentTheme === 'dark' ? 'light' : 'dark';

        try {
            localStorage.setItem(storageKey, preference);
        } catch {
            // Keep the selected theme for the current page if storage is unavailable.
        }

        applyTheme();
    });

    mediaQuery.addEventListener('change', () => {
        if (preference === 'system') {
            applyTheme();
        }
    });

    applyTheme();
})();
