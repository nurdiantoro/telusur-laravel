/**
 * Service Worker untuk Web Push Notification
 * Letakkan file ini di: public/service-worker.js
 *
 * PENTING: File ini HARUS berada di root /public/
 * agar scope-nya mencakup seluruh domain.
 */

const CACHE_NAME = 'telusur-v1';

// ─── Install Event ─────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    console.log('[SW] Service Worker installed');
    self.skipWaiting(); // langsung aktif tanpa menunggu tab lama ditutup
});

// ─── Activate Event ────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    console.log('[SW] Service Worker activated');
    event.waitUntil(clients.claim()); // ambil kontrol semua tab langsung
});

// ─── Push Event ────────────────────────────────────────────────────────────
// Dipanggil server saat ada notifikasi baru
self.addEventListener('push', (event) => {
    console.log('[SW] Push event received');

    let data = {
        title : 'Telusur',
        body  : 'Ada berita baru!',
        icon  : '/images/icon-192x192.png',
        badge : '/images/badge-72x72.png',
        url   : '/',
        tag   : 'telusur-notification',
    };

    // Parse payload dari server jika ada
    if (event.data) {
        try {
            data = { ...data, ...event.data.json() };
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body    : data.body,
        icon    : data.icon,
        badge   : data.badge,
        tag     : data.tag,          // mencegah notif duplikat
        data    : { url: data.url }, // disimpan untuk dibuka saat diklik
        vibrate : [200, 100, 200],   // pola getar (mobile)
        actions : [
            {
                action : 'open',
                title  : 'Baca Sekarang',
            },
            {
                action : 'close',
                title  : 'Tutup',
            },
        ],
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// ─── Notification Click Event ───────────────────────────────────────────────
// Dipanggil ketika user mengklik notifikasi
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event.action);

    event.notification.close();

    // Jika user klik "Tutup", tidak perlu buka tab
    if (event.action === 'close') {
        return;
    }

    // Buka URL dari payload notifikasi
    const targetUrl = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((windowClients) => {
                // Cek apakah sudah ada tab yang terbuka dengan URL yang sama
                for (const client of windowClients) {
                    if (client.url === targetUrl && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Jika tidak ada, buka tab baru
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});

// ─── Push Subscription Change ───────────────────────────────────────────────
// Dipanggil browser jika subscription expired dan diperbarui otomatis
self.addEventListener('pushsubscriptionchange', (event) => {
    console.log('[SW] Push subscription changed');

    event.waitUntil(
        self.registration.pushManager.subscribe({
            userVisibleOnly     : true,
            applicationServerKey: event.newSubscription?.options?.applicationServerKey,
        }).then((newSubscription) => {
            // Kirim subscription baru ke server
            return fetch('/api/push/subscribe', {
                method  : 'POST',
                headers : { 'Content-Type': 'application/json' },
                body    : JSON.stringify({
                    endpoint    : newSubscription.endpoint,
                    p256dh_key  : btoa(String.fromCharCode(...new Uint8Array(newSubscription.getKey('p256dh')))),
                    auth_token  : btoa(String.fromCharCode(...new Uint8Array(newSubscription.getKey('auth')))),
                }),
            });
        })
    );
});
