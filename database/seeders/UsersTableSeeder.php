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
        DB::table('users')->insert(array(
            0 =>
            array(
                'id' => 1,
                'role_id' => 1001,
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => '$2y$10$wY9yNA0ts7MWGPRskH1fPeVIS7cnHJk2j1o6rs4HfyO6sSjYz6ZIC',
                'remember_token' => '1mWxTwYU8itjPEqRIFkr5ClixdHWKVTI1dkccI51WtvVmDgTlQTftV5azyR4',
                'created_at' => '2019-05-26 21:00:18',
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'id' => 64,
                'role_id' => 1001,
                'name' => 'Editor',
                'email' => 'it@telusur.co.id',
                'username' => 'editor',
                'password' => '$2y$10$7u75XGT8AbHG/GuEd1HSvO3bdMycuAkrOqpnFPUmnv5YaMwddnTuy',
                'remember_token' => 'EVyeOcnmFiUP6XwB52tx9WuCizdS4cxm6TYO6bCGgCIK7uS12XnrPlAvt7qx',
                'created_at' => '2020-02-03 14:21:18',
                'updated_at' => '2020-02-03 19:31:17',
            ),
            2 =>
            array(
                'id' => 65,
                'role_id' => 1002,
                'name' => 'Jesron Sihotang',
                'email' => 'jerson@gmail.com',
                'username' => 'jesronsihotang',
                'password' => '$2y$10$fFVEvRFvP/2Es8RIbTbel.17UKX4fBZW3bvRgtWrAtMux5rD2oB62',
                'remember_token' => NULL,
                'created_at' => '2020-02-03 18:42:09',
                'updated_at' => '2020-02-03 19:30:55',
            ),
            3 =>
            array(
                'id' => 66,
                'role_id' => 1002,
                'name' => 'Wili Harianja',
                'email' => 'wili@telusur.co.id',
                'username' => 'wiliharianja',
                'password' => '$2y$10$Wpv9TTKh7.UiHyByqMgSjuK2cmwmiD4JDbL8QPMnsjA.8PRRu1JZ6',
                'remember_token' => NULL,
                'created_at' => '2020-02-03 18:43:38',
                'updated_at' => '2020-02-03 18:43:38',
            ),
            4 =>
            array(
                'id' => 67,
                'role_id' => 1002,
                'name' => 'Admin',
                'email' => 'bayu@gmail.com',
                'username' => 'Bayutelusur',
                'password' => '$2y$10$lZrGLIQTQeYasrJRKObxMeOTKt6wfTs5OgGDiXvLj9sZRgWZPjNNm',
                'remember_token' => 'thmQzBD7eSQb0w6eEVjzNVzJIsqQZkKPJ530w5jZpd3PkT87a7InQD3nYinP',
                'created_at' => '2020-11-16 19:09:13',
                'updated_at' => '2020-11-17 00:45:20',
            ),
            5 =>
            array(
                'id' => 68,
                'role_id' => 1002,
                'name' => 'Admin',
                'email' => 'sugi@gmail.com',
                'username' => 'Sugitelusur',
                'password' => '$2y$10$AVbPB.jA5TXb8Lqy/tt1M.AMaK9MdImeDhyAFP41diqHtILBWAcee',
                'remember_token' => '3PwSpyvRrWLWryvCT8SGVRh5biETuwahqpTsebMriSGtrXIP5kts862ubIgN',
                'created_at' => '2020-11-16 19:14:22',
                'updated_at' => '2020-11-20 11:56:58',
            ),
            6 =>
            array(
                'id' => 69,
                'role_id' => 1001,
                'name' => 'Admin Sumut',
                'email' => 'haqeji@getairmail.com',
                'username' => 'adminsumut',
                'password' => '$2y$10$/fCA9IdgA0soq17yCa47PukWly7kGJ//A5NE8IgLxmREaEiQdtl.a',
                'remember_token' => 'vaY85TKiDrtlNuuLc2E5xKmvvbKsE3kk3rEQi21BCz88wlqoQLwxsUTSubts',
                'created_at' => '2022-10-27 17:02:41',
                'updated_at' => '2022-10-27 17:02:58',
            ),
            7 =>
            array(
                'id' => 70,
                'role_id' => 1005,
                'name' => 'ipay bocok',
                'email' => 'ipaybocok@gmail.com',
                'username' => 'ipaybau17',
                'password' => '$2y$10$QB4xn1As89EJoj8kHJi7BuwvG/17jQHg9Fam0k9CzFtZKK0pfWIY6',
                'remember_token' => 'bOgErcutxxLLtZv7cqJ1TgkKYpiyLtvAkXTaPRRUhU7XkbtD4LNDMzetL2Rt',
                'created_at' => '2024-10-02 23:32:47',
                'updated_at' => '2024-10-02 23:32:47',
            ),
        ));
    }
}
