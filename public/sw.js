const CACHE_NAME = 'sim-eskul-v2';
const OFFLINE_URL = '/offline';
const ASSETS = [
    OFFLINE_URL,
    '/logo.png',
    '/header_banner.png',
    '/favicon.ico',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

// Install Event - Pre-cache critical offline assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('[Service Worker] Pre-caching offline fallback and static assets');
            return cache.addAll(ASSETS).catch(err => console.error('[Service Worker] Asset cache error: ', err));
        }).then(() => self.skipWaiting())
    );
});

// Activate Event - Clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => {
                    console.log('[Service Worker] Removing old cache: ', key);
                    return caches.delete(key);
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', event => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    // Handle HTML Navigation requests (pages)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    console.log('[Service Worker] Navigation failed, serving offline page');
                    return caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // Handle Non-Navigation requests (CSS, JS, Images, Fonts)
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
                // Return from cache, but fetch fresh in background for future use if it's our own asset
                const fetchPromise = fetch(event.request).then(networkResponse => {
                    if (networkResponse.status === 200) {
                        caches.open(CACHE_NAME).then(cache => cache.put(event.request, networkResponse));
                    }
                    return networkResponse;
                }).catch(() => {/* Ignore network errors for background fetch */});
                
                return cachedResponse;
            }

            // Fallback to network
            return fetch(event.request).then(networkResponse => {
                // Dynamically cache successful responses from safe origins
                if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return networkResponse;
            }).catch(() => {
                // If it fails and it's an image, we could return a placeholder, or just fail
                return null;
            });
        })
    );
});
