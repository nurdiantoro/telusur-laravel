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
        Schema::create('opinis', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('cover')->nullable();
            $table->foreignId('gallery_id')->nullable()->constrained()->nullOnDelete();
            $table->string('caption')->nullable();
            $table->text('content')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('post_categories')->nullOnDelete();
            $table->string('author')->nullable();
            $table->string('author_image')->nullable();
            $table->integer('views')->default(0)->unsigned();
            $table->timestamp('publish_time')->nullable()->useCurrent();

            $table->timestamps();

            // TAMBAH: composite index untuk query berita terbaru
            $table->index(['status', 'publish_time']); // sering dipakai homepage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opinis');
    }
};
