<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetLibraryCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('set_library_category')->insert(array(
            0 =>
            array(
                'id' => 10,
                'name' => 'Role Users',
            ),
            1 =>
            array(
                'id' => 20,
                'name' => 'Post Status',
            ),
            2 =>
            array(
                'id' => 30,
                'name' => 'Post Type',
            ),
        ));
    }
}
