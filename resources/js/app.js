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
        error: null,

        async fetchData() {
            if (this.isLoaded) return;

            try {

                const response = await fetch(urlApi);
                const {
                    data
                } = await response.json();

                // console.log('Data dari API:', data);
                this.$refs.skeleton.style.display = 'none';
                this.apiPosts = data;
                this.isLoaded = true;
            } catch (error) {
                this.error = error;

                // console.error(error);

            } finally {
                this.isLoading = false;
            }
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
