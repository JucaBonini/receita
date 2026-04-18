const CACHE_NAME = 'dr-pwa-v2.1'; // Atualizado para forçar atualização das correções de UI e Schema
const OFFLINE_URL = 'offline.html';

const ASSETS_TO_PRECACHE = [
  '/',
  './offline.html',
  './assets/css/main.min.css',
  './assets/js/main.js',
  './assets/images/logotipo-descomplicando_receitas300x300.png'
];

// Instalação: Cacheia arquivos críticos
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[PWA] Precaching Critical Assets');
      return cache.addAll(ASSETS_TO_PRECACHE);
    })
  );
  self.skipWaiting();
});

// Ativação: Limpa caches antigos
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME).map(name => caches.delete(name))
      );
    })
  );
  self.clients.claim();
});

// Interceptação de Requisições: Estratégia Stale-While-Revalidate
self.addEventListener('fetch', (event) => {
  if (!event.request.url.startsWith(self.location.origin)) return;

  // Estratégia Especial para Navegação (Network-First com Fallback Offline)
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match(OFFLINE_URL))
    );
    return;
  }

  // Estratégia Stale-While-Revalidate para Assets (CSS, JS, Imagens)
  event.respondWith(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.match(event.request).then((cachedResponse) => {
        const fetchPromise = fetch(event.request).then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        });
        return cachedResponse || fetchPromise;
      });
    })
  );
});
