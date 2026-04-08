import './bootstrap';
import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Data Alpine: Berita Utama (Lazy Loading)
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaUtama', () => ({
        memuat: true,
        daftarBerita: [],
        halaman: 1,
        masihAda: true,
        async muat() {
            if (!this.masihAda || this.memuat) return;
            this.memuat = true;
            try {
                const res = await fetch(`/api/berita-utama?page=${this.halaman}`);
                const json = await res.json();
                const data = json.data || [];
                this.daftarBerita = [...this.daftarBerita, ...data];
                this.masihAda = data.length > 0;
                this.halaman++;
            } catch (e) {
                console.error('Gagal memuat berita utama', e);
            } finally {
                this.memuat = false;
            }
        },
        saatScroll() {
            if (!this.masihAda || this.memuat) return;
            const sentinel = this.$refs.sentinel;
            if (sentinel && sentinel.getBoundingClientRect().top < window.innerHeight + 100) {
                this.muat();
            }
        }
    }));
});

// Data Alpine: Artikel Terbaru (Lazy Loading)
document.addEventListener('alpine:init', () => {
    Alpine.data('artikelTerbaru', () => ({
        memuat: true,
        daftarArtikel: [],
        halaman: 1,
        masihAda: true,
        async muat() {
            if (!this.masihAda || this.memuat) return;
            this.memuat = true;
            try {
                const res = await fetch(`/api/artikel-terbaru?page=${this.halaman}`);
                const json = await res.json();
                const data = json.data || [];
                this.daftarArtikel = [...this.daftarArtikel, ...data];
                this.masihAda = data.length > 0;
                this.halaman++;
            } catch (e) {
                console.error('Gagal memuat artikel terbaru', e);
            } finally {
                this.memuat = false;
            }
        },
        saatScroll() {
            if (!this.masihAda || this.memuat) return;
            const sentinel = this.$refs.sentinel;
            if (sentinel && sentinel.getBoundingClientRect().top < window.innerHeight + 100) {
                this.muat();
            }
        }
    }));
});

// Data Alpine: Berita Opini
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaOpini', () => ({
        memuat: true,
        daftarOpini: [],
        async muat() {
            this.memuat = true;
            try {
                const res = await fetch('/api/post/opini/8');
                const json = await res.json();
                this.daftarOpini = (json.data || []).map(opini => ({
                    ...opini,
                    waktu_publish: opini.publish_time_formatted || opini.publish_time || ''
                }));
            } catch (e) {
                console.error('Gagal memuat opini', e);
            } finally {
                this.memuat = false;
            }
        }
    }));
});

// Data Alpine: Berita Populer
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaPopuler', () => ({
        memuat: true,
        daftarPopuler: [],
        async muat() {
            try {
                const res = await fetch('/api/berita-populer');
                const json = await res.json();
                this.daftarPopuler = json.data;
            } catch (e) {
                console.error('Gagal memuat berita populer');
            } finally {
                this.memuat = false;
            }
        }
    }));
});

// Data Alpine: Berita Video
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaVideo', () => ({
        memuat: true,
        daftarVideo: [],
        async muat() {
            this.memuat = true;
            try {
                const res = await fetch('/api/post/video/8');
                const json = await res.json();
                this.daftarVideo = (json.data || []).map(video => ({
                    ...video,
                    waktu_publish: video.publish_time_formatted || video.publish_time || ''
                }));
            } catch (e) {
                console.error('Gagal memuat berita video', e);
            } finally {
                this.memuat = false;
            }
        }
    }));
});

Alpine.start();

// Inisialisasi Swiper setelah DOM siap
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
});
