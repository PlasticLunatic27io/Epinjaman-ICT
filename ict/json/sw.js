const CACHE_NAME = 'pwa-cache-v1';

const urlsToCache = [
  '/ict/kptmmap.html',
  '/ict/style.css',
  '/ict/script.js',
  '/ict/json/manifest.json'
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
