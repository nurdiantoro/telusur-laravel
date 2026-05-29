<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdsenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO adsense (id, slug, label, script, created_at, updated_at)

            SELECT id,slug,label,script,created_at,updated_at
            FROM tbl_adsense
        ");
    }
}
