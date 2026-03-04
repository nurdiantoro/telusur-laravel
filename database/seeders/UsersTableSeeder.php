<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
            INSERT INTO users (id,role_id,name,email,username,password,remember_token,created_at,updated_at,deleted_at)

            SELECT id,role_id,name,email,username,password,remember_token,created_at,updated_at,deleted_at
            FROM tbl_users
        ");
    }
}
