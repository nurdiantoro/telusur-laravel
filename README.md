<div align="left">
  <img height="104" src="https://telusur.co.id/img/logo.png"  />
</div>

###

<h1 align="left">Telusur Laravel</h1>

###

<p align="left">Telusur (Laravel) adalah aplikasi web berbasis Laravel 12 yang berfungsi sebagai sistem manajemen konten dan berita dengan admin panel menggunakan Filament.<br><br>Aplikasi ini dirancang untuk mengelola proses editorial secara terstruktur, mulai dari pembuatan konten, pengaturan user dan role, hingga publikasi berita.<br><br>Fitur utamanya mencakup:<br><br>Admin panel menggunakan Filament<br>Sistem role & permission (RBAC)<br>Manajemen berita/post<br>Activity logging untuk tracking perubahan data<br>Search engine menggunakan Laravel Scout + Meilisearch<br>Media management untuk file dan gambar<br>Workflow konten (draft, publish, dll)<br><br>Intinya, Telusur adalah CMS berita modern berbasis Laravel yang sudah dilengkapi sistem admin, search, dan audit log siap production.</p>

###

<h2 align="left">Cara Install</h2>

###

<p align="left">1. composer install<br>2. npm install<br>3. cp .env.example .env<br>4. isi key .env seperti project yang lama, biar gak reset password<br>5. php artisan migrate<br>6. Import db lama ke db baru (manual di phpmyadmin)<br>7. php artisan db:seed (tabel db lama aman di hapus)<br>8. Jalankan Meilisearch<br>8a. ubah .env SCOUT_DRIVER=meilisearch<br>8b. jalankan di terminal  : meilisearch<br>8c. jika 8b tidak berjalan, jalankan di terminal  : nohup meilisearch > meili.log 2>&1 &<br>8d. php artisan scout:import "App\Models\Post"<br>8e. cek apakah sudah jalan, jalankan di terminal : curl http://127.0.0.1:7700</p>

###

<h2 align="left">Technology</h2>

###

<div align="left">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg" height="40" alt="laravel logo"  />
  <img width="12" />
  <img src="https://skillicons.dev/icons?i=php" height="40" alt="php logo"  />
  <img width="12" />
  <img src="https://cdn.simpleicons.org/tailwindcss/06B6D4" height="40" alt="tailwindcss logo"  />
</div>

###
