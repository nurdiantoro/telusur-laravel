<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement("
            INSERT INTO post_categories (id, name, slug, created_at, updated_at)

            SELECT id,name,slug,created_at,updated_at
            FROM tbl_categories
        ");
    }
}
