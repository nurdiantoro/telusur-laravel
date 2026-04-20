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
        Schema::table('posts', function (Blueprint $table) {

            // Hapus index yang redundant
            $table->dropIndex('posts_publish_time_index');
            $table->dropIndex('posts_status_index');
            $table->dropIndex('posts_type_index');
            $table->dropIndex('posts_category_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('publish_time', 'posts_publish_time_index');
            $table->index('status', 'posts_status_index');
            $table->index('type', 'posts_type_index');
            $table->index('category_id', 'posts_category_id_index');
        });
    }
};
