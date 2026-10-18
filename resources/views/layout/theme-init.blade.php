<script>
    (() => {
        let preference = 'system';

        try {
            preference = localStorage.getItem('agusha-blog-theme') ?? 'system';
        } catch {
            preference = 'system';
        }

        const followsDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = preference === 'system' ? (followsDarkMode ? 'dark' : 'light') : preference;
        document.documentElement.dataset.theme = theme;
        document.documentElement.dataset.themePreference = preference;
    })();
</script>
