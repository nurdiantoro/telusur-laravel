import './bootstrap';
import Swiper from 'swiper';
import {
    Navigation,
    Pagination,
    Autoplay
} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';


// ─── Push Notification Component ─────────────────────────────────────────────
function pushNotification() {
    return {
        isSubscribed  : false,
        isLoading     : false,
        isDenied      : false,
        showTooltip   : false,
        tooltipTitle  : '',
        tooltipMessage: '',
        vapidPublicKey: null,
        swRegistration: null,

        get statusLabel() {
            if (this.isDenied)     return 'Notifikasi diblokir di pengaturan browser';
            if (this.isSubscribed) return 'Klik untuk mematikan notifikasi';
            return 'Klik untuk mengaktifkan notifikasi berita baru';
        },

        async init() {
            // Cek dukungan browser
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                console.warn('[Push] Browser tidak mendukung Web Push.');
                return;
            }

            // Ambil VAPID public key dari server
            try {
                const res  = await fetch('/api/push/vapid-key');
                const data = await res.json();
                this.vapidPublicKey = data.public_key;
            } catch (e) {
                console.error('[Push] Gagal mengambil VAPID key:', e);
                return;
            }

            // Daftarkan service worker
            try {
                this.swRegistration = await navigator.serviceWorker.register('/service-worker.js');
                console.log('[Push] Service Worker registered.');
            } catch (e) {
                console.error('[Push] Gagal mendaftarkan Service Worker:', e);
                return;
            }

            // Cek status permission & subscription saat ini
            await this.checkSubscriptionStatus();
        },

        async checkSubscriptionStatus() {
            if (Notification.permission === 'denied') {
                this.isDenied     = true;
                this.isSubscribed = false;
                return;
            }

            const sub         = await this.swRegistration?.pushManager.getSubscription();
            this.isSubscribed = !!sub;
        },

        async toggleNotification() {
            if (this.isLoading || this.isDenied) return;
            this.isSubscribed ? await this.unsubscribe() : await this.subscribe();
        },

        async subscribe() {
            this.isLoading = true;
            try {
                const permission = await Notification.requestPermission();

                if (permission !== 'granted') {
                    this.isDenied = permission === 'denied';
                    this.showMessage(
                        'Notifikasi Diblokir',
                        permission === 'denied'
                            ? 'Aktifkan notifikasi di pengaturan browser kamu.'
                            : 'Izin notifikasi dibatalkan.'
                    );
                    return;
                }

                const subscription = await this.swRegistration.pushManager.subscribe({
                    userVisibleOnly     : true,
                    applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey),
                });

                await fetch('/api/push/subscribe', {
                    method : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify({
                        endpoint   : subscription.endpoint,
                        p256dh_key : this.arrayBufferToBase64(subscription.getKey('p256dh')),
                        auth_token : this.arrayBufferToBase64(subscription.getKey('auth')),
                    }),
                });

                this.isSubscribed = true;
                this.showMessage('Notifikasi Aktif ✓', 'Kamu akan mendapat notifikasi saat ada berita baru.');

            } catch (e) {
                console.error('[Push] Subscribe error:', e);
                this.showMessage('Gagal', 'Terjadi kesalahan saat mengaktifkan notifikasi.');
            } finally {
                this.isLoading = false;
            }
        },

        async unsubscribe() {
            this.isLoading = true;
            try {
                const subscription = await this.swRegistration.pushManager.getSubscription();

                if (subscription) {
                    await fetch('/api/push/unsubscribe', {
                        method : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        },
                        body: JSON.stringify({ endpoint: subscription.endpoint }),
                    });

                    await subscription.unsubscribe();
                }

                this.isSubscribed = false;
                this.showMessage('Notifikasi Dimatikan', 'Kamu tidak akan menerima notifikasi lagi.');

            } catch (e) {
                console.error('[Push] Unsubscribe error:', e);
                this.showMessage('Gagal', 'Terjadi kesalahan saat mematikan notifikasi.');
            } finally {
                this.isLoading = false;
            }
        },

        showMessage(title, message) {
            this.tooltipTitle   = title;
            this.tooltipMessage = message;
            this.showTooltip    = true;
            setTimeout(() => { this.showTooltip = false; }, 3500);
        },

        urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64  = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = atob(base64);
            return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
        },

        arrayBufferToBase64(buffer) {
            return btoa(String.fromCharCode(...new Uint8Array(buffer)));
        },
    };
}
// ─────────────────────────────────────────────────────────────────────────────
//
//
//
// ─── API untuk Berita ─────────────────────────────────────────────────────
function createFetcher(urlApi) {
    return () => ({
        isLoading: true,
        isLoaded: false,
        apiPosts: [],
        pagination: null,
        error: null,
        currentUrl: urlApi,

        async fetchData(url = null) {
            this.isLoading = true;

            if (url) {
                this.currentUrl = url;
            }

            try {
                const response = await fetch(this.currentUrl);
                const json = await response.json();

                this.apiPosts = json.data ?? [];
                this.pagination = json.pagination ?? null;

                if (!this.isLoaded && this.$refs.skeleton) {
                    this.$refs.skeleton.style.display = 'none';
                }

                this.isLoaded = true;

            } catch (error) {
                this.error = error;
            } finally {
                this.isLoading = false;
            }
        },

        next() {
            if (this.pagination?.next_page_url) {
                this.fetchData(this.pagination.next_page_url);
            }
        },

        prev() {
            if (this.pagination?.prev_page_url) {
                this.fetchData(this.pagination.prev_page_url);
            }
        },

        hasPagination() {
            return this.pagination && this.pagination.last_page > 1;
        },

        init() {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this.fetchData();
                    observer.disconnect();
                }
            }, {
                rootMargin: '200px'
            });

            observer.observe(this.$el);
        }
    });
}
// ─────────────────────────────────────────────────────────────────────────────
// ─── Daftarkan Alpine.js ─────────────────────────────────────────────────────
Alpine.plugin(collapse);
window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaUtama', createFetcher('/api/berita-utama'));
    Alpine.data('beritaPopuler', createFetcher('/api/berita-populer'));
    Alpine.data('beritaTerbaru', createFetcher('/api/berita-terbaru'));
    Alpine.data('beritaTerbaruTanpaPagination', createFetcher('/api/berita-terbaru/tanpa-pagination'));
    Alpine.data('beritaVideo', createFetcher('/api/berita-video'));
    Alpine.data('beritaOpini', createFetcher('/api/berita-opini'));

    // Daftarkan push notification component
    Alpine.data('pushNotification', pushNotification);
});
Alpine.start();
// ─────────────────────────────────────────────────────────────────────────────
//
//
//
// ─── Swiper.js ───────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.hotNews')) {
        new Swiper('.hotNews', {
            modules: [Navigation, Autoplay],
            loop: true,
            speed: 1500,
            slidesPerView: 1,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.hotNews-next',
                prevEl: '.hotNews-prev',
            },
            on: {
                init: function () {
                    document.querySelector('.hotNews').classList.remove('hidden');
                }
            }
        });
    }

    if (document.querySelector('.highlightNews')) {
        new Swiper('.highlightNews', {
            modules: [Navigation, Autoplay],
            loop: true,
            speed: 2000,
            slidesPerView: 1,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.highlightNews-next',
                prevEl: '.highlightNews-prev',
            },
            on: {
                init: function () {
                    document.querySelector('.highlightNews').classList.remove('hidden');
                }
            }
        });
    }


    if (document.querySelector('.beritaVideo')) {
        new Swiper('.beritaVideo', {
            modules: [Navigation, Pagination, Autoplay],
            loop: true,
            navigation:true,
            pagination: {
                el: ".beritaVideo-pagination",
                clickable: true,
            },
            speed: 2000,
            slidesPerView: 3,
            spaceBetween: 10,
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 5,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                },
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            on: {
                init: function () {
                    document.querySelector('.beritaVideo').classList.remove('hidden');
                }
            }
        });
    }

    if (document.querySelector('.beritaFoto')) {
        new Swiper('.beritaFoto', {
            modules: [Navigation, Pagination, Autoplay],
            loop: true,
            navigation:true,
            pagination: {
                el: ".beritaFoto-pagination",
                clickable: true,
            },
            speed: 2000,
            slidesPerView: 3,
            spaceBetween: 10,
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 5,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                },
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            on: {
                init: function () {
                    document.querySelector('.beritaFoto').classList.remove('hidden');
                }
            }
        });
    }
});
// ─────────────────────────────────────────────────────────────────────────────
