<!-- markdownlint-disable -->
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

<p align="left">
1. <b>composer install</b><br>
2. <b>npm install</b><br>
3. <b>cp .env.example .env</b><br>
4. isi key .env seperti project yang lama, biar gak reset password<br>
5. <b>php artisan migrate</b><br>
6. Import db lama ke db baru (manual di phpmyadmin)<br>
7. <b>php artisan db:seed</b> (tabel db lama aman di hapus)<br>
8. Jalankan Meilisearch<br>
8a. ubah .env SCOUT_DRIVER=meilisearch<br>
8b. jalankan di terminal  : <b>meilisearch</b><br>
8c. jika 8b tidak berjalan, jalankan di terminal  : <b>nohup meilisearch > meili.log 2>&1 &</b><br>
8d. <b>php artisan scout:import "App\Models\Post"</b><br>
8e. cek apakah sudah jalan, jalankan di terminal : <b>curl http://127.0.0.1:7700</b><br>
9. <b>php artisan storage:link</b><br>
10. <b>php artisan optimize</b><br>
</p>

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
