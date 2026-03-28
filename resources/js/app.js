import './bootstrap';

import Swiper from 'swiper';
import {
    Navigation,
    Autoplay
} from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';

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
            // pagination: {
            //     el: '.highlightNews-pagination',
            // },
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
