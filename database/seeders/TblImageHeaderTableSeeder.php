<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblImageHeaderTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_image_header')->insert(array(
            0 =>
            array(
                'id' => 1,
                'title' => 'Majelis Permusyawaratan Rakyat',
                'image' => '1579050314.jpg',
                'link' => 'http://telusur.co.id/category/14',
                'created_at' => '2019-06-22 13:44:33',
                'updated_at' => '2020-01-15 08:05:14',
            ),
            1 =>
            array(
                'id' => 2,
                'title' => 'Dewan Perwakilan Rakyat',
                'image' => '1561564637.png',
                'link' => 'http://telusur.utamafirst.events/category/15',
                'created_at' => '2019-06-22 14:34:55',
                'updated_at' => '2019-06-26 16:03:21',
            ),
            2 =>
            array(
                'id' => 5,
                'title' => 'Dewan Pertimbangan Daerah',
                'image' => '1561564681.png',
                'link' => 'http://telusur.utamafirst.events/category/16',
                'created_at' => '2019-06-26 15:49:27',
                'updated_at' => '2019-06-26 16:02:24',
            ),
            3 =>
            array(
                'id' => 6,
                'title' => 'admin',
                'image' => '1727886978.jpg',
                'link' => 'https://telusur.co.id/',
                'created_at' => '2024-10-02 23:36:18',
                'updated_at' => '2024-10-02 23:36:18',
            ),
        ));
    }
}
