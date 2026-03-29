<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
        INSERT INTO role_user (user_id, role_id)
        SELECT u.id, m.new_role_id
        FROM users u
        JOIN (
            SELECT 1001 as old_role_id, 1 as new_role_id
            UNION ALL SELECT 1002, 2
            UNION ALL SELECT 1003, 3
            UNION ALL SELECT 1004, 4
            UNION ALL SELECT 1005, 5
        ) m ON u.role_id = m.old_role_id
        ");

        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('role_id');
            });
        }
    }
}
