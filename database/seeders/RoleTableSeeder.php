<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement("
            INSERT INTO roles (id,name)

            SELECT id,name
            FROM set_libraries
            WHERE category_id = 10
        ");
    }
}
