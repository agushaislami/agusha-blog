@if (session('status'))
    <div class="flash-notification" id="flashNotification" role="status" aria-live="polite">
        <i class="fas fa-circle-check flash-notification-icon" aria-hidden="true"></i>
        <span>{{ session('status') }}</span>
        <button class="flash-notification-close" type="button" aria-label="Tutup notifikasi">&times;</button>
    </div>
    <style>
        .flash-notification { position: fixed; z-index: 2000; top: 1rem; right: 1rem; display: flex; align-items: center; gap: .65rem; width: min(360px, calc(100vw - 2rem)); padding: .8rem 1rem; border: 1px solid #b7dfc5; border-radius: .75rem; background: #f0fbf4; color: #185c32; box-shadow: 0 .5rem 1.5rem rgba(26, 55, 37, .16); font-size: .9rem; line-height: 1.4; animation: flash-notification-enter .3s ease-out both; }
        .flash-notification-icon { flex: 0 0 auto; color: #238548; }
        .flash-notification span { flex: 1; }
        .flash-notification-close { flex: 0 0 auto; padding: 0 .15rem; border: 0; background: transparent; color: currentColor; font-size: 1.35rem; line-height: 1; opacity: .65; }
        .flash-notification-close:hover { opacity: 1; }
        .flash-notification.is-leaving { animation: flash-notification-exit .25s ease-in both; }
        @keyframes flash-notification-enter { from { opacity: 0; transform: translateX(1.25rem); } to { opacity: 1; transform: translateX(0); } }
        @keyframes flash-notification-exit { to { opacity: 0; transform: translateX(1.25rem); } }
        @media (prefers-reduced-motion: reduce) { .flash-notification, .flash-notification.is-leaving { animation-duration: .01ms; } }
    </style>
    <script>
        (() => {
            const notification = document.getElementById('flashNotification');

            if (!notification) {
                return;
            }

            const dismiss = () => {
                notification.classList.add('is-leaving');
                window.setTimeout(() => notification.remove(), 250);
            };

            notification.querySelector('.flash-notification-close').addEventListener('click', dismiss);
            window.setTimeout(dismiss, 4500);
        })();
    </script>
@endif
