const CACHE_NAME = 'cdss-tifoid-shell-v1';
const SHELL_URLS = [
    '/offline.html',
    '/manifest.webmanifest',
    '/pwa-icon.svg',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(SHELL_URLS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName.startsWith('cdss-tifoid-shell-') && cacheName !== CACHE_NAME)
                    .map((cacheName) => caches.delete(cacheName))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);
    if (event.request.method !== 'GET' || requestUrl.origin !== self.location.origin) {
        return;
    }

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(async () => {
                const offlinePage = await caches.match('/offline.html');
                return offlinePage || Response.error();
            })
        );
        return;
    }

    if (SHELL_URLS.includes(requestUrl.pathname)) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => cachedResponse || fetch(event.request))
        );
    }
});
