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
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('gallery')->nullable()->after('description');
            $table->string('spatie_preview')->nullable()->after('gallery');
            $table->string('spatie_thumbnail')->nullable()->after('spatie_preview');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['gallery', 'spatie_preview', 'spatie_thumbnail']);
        });
    }
};
