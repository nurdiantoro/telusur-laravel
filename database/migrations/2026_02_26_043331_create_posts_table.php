<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();

            $table->string('type')->nullable();
            $table->index('type'); // TAMBAH: index biar filter by type lebih cepat

            $table->string('cover')->nullable();
            $table->string('caption')->nullable();
            $table->string('video_url')->nullable();

            $table->text('content')->nullable();

            $table->string('status')->nullable();
            $table->index('status'); // TAMBAH: index biar filter published cepat

            // UBAH: pakai foreignId untuk relasi
            $table->foreignId('category_id')->nullable()->index(); // sebelumnya integer, tambahkan index
            $table->foreignId('author_id')->nullable()->index(); // sebelumnya integer, tambahkan index

            $table->integer('views')->default(0)->unsigned();

            $table->timestamp('publish_time')->nullable()->useCurrent();
            $table->index('publish_time'); // TAMBAH: index biar order/filter by publish_time cepat

            $table->timestamps();

            // TAMBAH: composite index untuk query berita terbaru
            $table->index(['status', 'publish_time']); // sering dipakai homepage
            $table->index(['category_id', 'publish_time']); // sering dipakai halaman kategori
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
