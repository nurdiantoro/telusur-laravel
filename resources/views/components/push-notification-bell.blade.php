{{--
    Komponen Tombol Notifikasi (Bell Icon)
    ========================================
    Cara pakai di layout/navbar:
        <x-push-notification-bell />

    Komponen ini:
    - Menampilkan icon lonceng
    - Mendeteksi status notifikasi (granted/denied/default)
    - Menampilkan tooltip status
    - Handle subscribe & unsubscribe
--}}

<div x-data="pushNotification()" x-init="init()" class="relative">

    {{-- Tombol Bell --}}
    <button @click="toggleNotification()" :title="statusLabel" :disabled="isLoading || isDenied"
        class="relative flex h-10 w-10 items-center justify-center rounded-full transition-colors duration-200 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:hover:bg-gray-700"
        :class="{
            'text-blue-600 dark:text-blue-400': isSubscribed,
            'text-gray-500 dark:text-gray-400': !isSubscribed,
            'text-red-400': isDenied,
        }">
        {{-- Loading Spinner --}}
        <svg x-show="isLoading" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
        </svg>

        {{-- Bell Icon (subscribed) --}}
        <svg x-show="!isLoading && isSubscribed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
            fill="currentColor">
            <path
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Bell Slash Icon (not subscribed / denied) --}}
        <svg x-show="!isLoading && !isSubscribed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Dot indikator aktif --}}
        <span x-show="isSubscribed && !isLoading"
            class="absolute right-1.5 top-1.5 block h-2 w-2 rounded-full bg-blue-500 ring-1 ring-white"></span>
    </button>

    {{-- Tooltip / Status Message --}}
    <div x-show="showTooltip" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="absolute right-0 z-50 mt-2 w-56 rounded-lg border border-gray-200 bg-white p-3 text-sm shadow-lg dark:border-gray-700 dark:bg-gray-800"
        style="top: 100%">
        <p class="font-medium text-gray-800 dark:text-gray-100" x-text="tooltipTitle"></p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="tooltipMessage"></p>
    </div>

</div>

@push('scripts')
    <script>
        function pushNotification() {
            return {
                isSubscribed: false,
                isLoading: false,
                isDenied: false,
                showTooltip: false,
                tooltipTitle: '',
                tooltipMessage: '',
                vapidPublicKey: null,
                swRegistration: null,

                // Label untuk title attribute tombol
                get statusLabel() {
                    if (this.isDenied) return 'Notifikasi diblokir di pengaturan browser';
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
                        const res = await fetch('/api/push/vapid-key');
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

                    // Cek status permission saat ini
                    await this.checkSubscriptionStatus();
                },

                async checkSubscriptionStatus() {
                    const permission = Notification.permission;

                    if (permission === 'denied') {
                        this.isDenied = true;
                        this.isSubscribed = false;
                        return;
                    }

                    // Cek apakah sudah ada subscription aktif
                    const sub = await this.swRegistration?.pushManager.getSubscription();
                    this.isSubscribed = !!sub;
                },

                async toggleNotification() {
                    if (this.isLoading || this.isDenied) return;

                    if (this.isSubscribed) {
                        await this.unsubscribe();
                    } else {
                        await this.subscribe();
                    }
                },

                async subscribe() {
                    this.isLoading = true;

                    try {
                        // Minta izin notifikasi ke user (browser akan tampilkan dialog)
                        const permission = await Notification.requestPermission();

                        if (permission !== 'granted') {
                            this.isDenied = permission === 'denied';
                            this.showMessage(
                                'Notifikasi Diblokir',
                                permission === 'denied' ?
                                'Aktifkan notifikasi di pengaturan browser kamu.' :
                                'Izin notifikasi dibatalkan.'
                            );
                            return;
                        }

                        // Subscribe ke Push Manager
                        const subscription = await this.swRegistration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey),
                        });

                        // Kirim data subscription ke server
                        await fetch('/api/push/subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ??
                                    '',
                            },
                            body: JSON.stringify({
                                endpoint: subscription.endpoint,
                                p256dh_key: this.arrayBufferToBase64(subscription.getKey('p256dh')),
                                auth_token: this.arrayBufferToBase64(subscription.getKey('auth')),
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
                            // Hapus dari server dulu
                            await fetch('/api/push/unsubscribe', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        ?.content ?? '',
                                },
                                body: JSON.stringify({
                                    endpoint: subscription.endpoint
                                }),
                            });

                            // Kemudian unsubscribe dari browser
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
                    this.tooltipTitle = title;
                    this.tooltipMessage = message;
                    this.showTooltip = true;
                    setTimeout(() => {
                        this.showTooltip = false;
                    }, 3500);
                },

                // Konversi VAPID public key dari base64 ke Uint8Array
                urlBase64ToUint8Array(base64String) {
                    const padding = '='.repeat((4 - base64String.length % 4) % 4);
                    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
                    const rawData = atob(base64);
                    return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
                },

                // Konversi ArrayBuffer ke base64 string
                arrayBufferToBase64(buffer) {
                    return btoa(String.fromCharCode(...new Uint8Array(buffer)));
                },
            };
        }
    </script>
@endpush
