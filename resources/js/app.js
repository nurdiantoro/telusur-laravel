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
        loading: true,
        posts: [],

        async load() {
            try {
                const res = await fetch('/api/berita-utama')
                const json = await res.json()

                // 🔥 langsung pakai dari API (udah clean)
                this.posts = json.data

            } catch (e) {
                console.error('Gagal load berita utama:', e)
            } finally {
                this.loading = false
            }
        }
    }))
});

Alpine.start();

//
//
//
// Inisialisasi Swiper setelah DOM siap
//
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
