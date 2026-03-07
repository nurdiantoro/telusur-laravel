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
            $table->id(); // OK, primary key

            $table->string('title')->nullable(); // OK
            $table->string('slug')->unique()->nullable(); // OK, slug unique untuk detail page

            $table->string('type')->nullable();
            $table->index('type'); // TAMBAH: index biar filter by type lebih cepat

            $table->string('cover')->nullable(); // OK
            $table->string('caption')->nullable(); // OK
            $table->string('video_url')->nullable(); // OK

            $table->text('content')->nullable(); // OK

            $table->string('status')->nullable();
            $table->index('status'); // TAMBAH: index biar filter published cepat

            // UBAH: pakai foreignId untuk relasi
            $table->foreignId('category_id')->nullable()->index(); // sebelumnya integer, tambahkan index
            $table->foreignId('author_id')->nullable()->index(); // sebelumnya integer, tambahkan index

            $table->integer('views')->default(0)->unsigned(); // OK

            $table->timestamp('publish_time')->nullable()->useCurrent();
            $table->index('publish_time'); // TAMBAH: index biar order/filter by publish_time cepat

            $table->timestamps(); // OK

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
