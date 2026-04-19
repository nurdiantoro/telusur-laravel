import './bootstrap';
import Swiper from 'swiper';
import {
    Navigation,
    Autoplay
} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import Alpine from 'alpinejs';

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

                // hide skeleton hanya saat pertama kali load
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


window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('beritaUtama', createFetcher('/api/berita-utama'));
    Alpine.data('beritaPopuler', createFetcher('/api/berita-populer'));
    Alpine.data('beritaTerbaru', createFetcher('/api/berita-terbaru'));
    Alpine.data('beritaTerbaruTanpaPagination', createFetcher('/api/berita-terbaru/tanpa-pagination'));
    Alpine.data('beritaVideo', createFetcher('/api/berita-video'));
    Alpine.data('beritaOpini', createFetcher('/api/berita-opini'));
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
