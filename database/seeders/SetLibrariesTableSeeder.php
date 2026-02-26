<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetLibrariesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('set_libraries')->insert(array(
            0 =>
            array(
                'id' => 1001,
                'category_id' => 10,
                'name' => 'Administrator',
            ),
            1 =>
            array(
                'id' => 1002,
                'category_id' => 10,
                'name' => 'Editor',
            ),
            2 =>
            array(
                'id' => 1003,
                'category_id' => 10,
                'name' => 'Author',
            ),
            3 =>
            array(
                'id' => 1004,
                'category_id' => 10,
                'name' => 'Pimpinan Redaksi',
            ),
            4 =>
            array(
                'id' => 1005,
                'category_id' => 10,
                'name' => 'Member',
            ),
            5 =>
            array(
                'id' => 2001,
                'category_id' => 20,
                'name' => 'Draft',
            ),
            6 =>
            array(
                'id' => 2002,
                'category_id' => 20,
                'name' => 'Edited',
            ),
            7 =>
            array(
                'id' => 2003,
                'category_id' => 20,
                'name' => 'Publish',
            ),
            8 =>
            array(
                'id' => 2004,
                'category_id' => 20,
                'name' => 'Trash',
            ),
            9 =>
            array(
                'id' => 3001,
                'category_id' => 30,
                'name' => 'Post',
            ),
            10 =>
            array(
                'id' => 3002,
                'category_id' => 30,
                'name' => 'Opini',
            ),
            11 =>
            array(
                'id' => 3003,
                'category_id' => 30,
                'name' => 'Berita Video',
            ),
        ));
    }
}
