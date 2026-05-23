/**
 * Kill Switch Service Worker
 * 
 * File ini sengaja ada untuk meng-unregister dirinya sendiri
 * dan semua SW lain yang mungkin masih aktif di browser pengunjung.
 * 
 * SW lama akan fetch file ini (karena path-nya sama: /sw.js),
 * lalu langsung unregister dirinya sendiri.
 */

self.addEventListener('install', function (event) {
    // Skip waiting agar langsung aktif, tidak menunggu tab ditutup
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        // Hapus semua cache yang dibuat SW lama
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    return caches.delete(cacheName);
                })
            );
        }).then(function () {
            // Unregister diri sendiri
            return self.registration.unregister();
        }).then(function () {
            // Paksa semua tab yang terbuka untuk reload dari server
            return self.clients.matchAll({ type: 'window' });
        }).then(function (clients) {
            clients.forEach(function (client) {
                client.navigate(client.url);
            });
        })
    );
});
