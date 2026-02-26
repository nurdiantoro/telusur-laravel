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
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('post_type_id')->nullable();
            $table->string('cover')->nullable();
            $table->string('cover_thumbnail')->nullable();
            $table->string('caption')->nullable();
            $table->string('video_url')->nullable();
            $table->text('content');
            $table->integer('status_id');
            $table->integer('category_id')->nullable();
            $table->integer('author_id');
            $table->integer('views')->default(0)->unsigned();
            $table->timestamp('publish_time')->nullable()->useCurrent();
            $table->timestamps();
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
