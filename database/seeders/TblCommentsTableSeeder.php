<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblCommentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_comments')->insert(array(
            0 =>
            array(
                'id' => 1,
                'commentator_id' => 8,
                'comment' => 'TEK KOMENTAR APA AJA',
                'created_at' => '2019-06-13 07:17:34',
                'updated_at' => NULL,
                'post_id' => 17,
            ),
        ));
    }
}
