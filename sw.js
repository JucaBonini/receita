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

// Interceptação de Requisições
self.addEventListener('fetch', (event) => {
  // Ignorar AdSense e Analytics externas para não quebrar o cache
  if (!event.request.url.startsWith(self.location.origin)) return;

  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) return cachedResponse;

      return fetch(event.request).then((networkResponse) => {
        // Cache dinâmico para imagens e posts que o usuário visita
        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }
        return networkResponse;
      }).catch(() => {
        // Fallback para a página offline se for uma navegação de página
        if (event.request.mode === 'navigate') {
          return caches.match(OFFLINE_URL);
        }
      });
    })
  );
});
