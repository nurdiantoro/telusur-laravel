import './bootstrap';

const btn = document.getElementById('menu-btn');
const menu = document.getElementById('mobile-menu');

btn.addEventListener('click', () => {
  // 1. Animasi Slide Down/Up pada Menu
  // Jika menu punya '-translate-y-full', ganti ke 'translate-y-0', dan sebaliknya
  menu.classList.toggle('-translate-y-full');
  menu.classList.toggle('translate-y-0');

  // 2. Animasi Icon Hamburger (Opsional tapi keren)
  // Menambahkan class 'open' agar CSS 'group-[.open]' di atas bekerja
  btn.classList.toggle('open');
});
