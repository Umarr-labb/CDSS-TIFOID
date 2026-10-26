<style>
    .pwa-install-button {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 1100;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 16px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: #087f8c;
        box-shadow: 0 5px 18px rgba(7, 94, 104, .28);
        font: 600 14px/1.2 Arial, sans-serif;
        cursor: pointer;
    }
    .pwa-install-button:hover { background: #075e68; }
    .pwa-install-button[hidden] { display: none; }
    @media (max-width: 600px) {
        .pwa-install-button { right: 14px; bottom: 14px; }
    }
</style>
<button type="button" class="pwa-install-button" id="pwa-install-button">
    <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3"/>
    </svg>
    <span>Pasang Aplikasi</span>
</button>
<script>
    (() => {
        const installButton = document.getElementById('pwa-install-button');
        let installPrompt = null;
        const isInstalled = () => window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;

        if (isInstalled()) {
            installButton.hidden = true;
        }

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            installPrompt = event;
        });

        window.addEventListener('appinstalled', () => {
            installButton.hidden = true;
            installPrompt = null;
        });

        installButton.addEventListener('click', async () => {
            if (installPrompt) {
                installPrompt.prompt();
                await installPrompt.userChoice;
                installPrompt = null;
                return;
            }

            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                alert('Aplikasi dapat dipasang setelah website dipublikasikan menggunakan HTTPS (SSL).');
                return;
            }

            alert('Untuk memasang aplikasi, buka menu browser Chrome/Edge lalu pilih "Instal aplikasi" atau "Instal halaman sebagai aplikasi".');
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .catch((error) => console.error('Gagal mendaftarkan service worker CDSS:', error));
            });
        }
    })();
</script>
