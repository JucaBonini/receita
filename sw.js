const CACHE_NAME = 'dr-pwa-v1';
const ASSETS_TO_CACHE = [
  './',
  './assets/css/main.min.css',
  './assets/js/main.js',
  './assets/images/placeholder.jpg'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[PWA] Precaching Offline Assets');
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Ativação e limpeza de caches antigos
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log('[PWA] Clearing Old Cache', cache);
            return caches.delete(cache);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// Estratégia: Stale-While-Revalidate (Cache primeiro, atualiza em background)
self.addEventListener('fetch', (event) => {
  // Ignorar requisições do Google AdSense e outras origens externas
  if (event.request.url.includes('google') || event.request.url.includes('doubleclick')) {
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      const fetchPromise = fetch(event.request).then((networkResponse) => {
        // Apenas cacheia posts e assets internos
        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      });

      // Retorna o cache se existir, senão espera o network
      return cachedResponse || fetchPromise;
    })
  );
});
