<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            PostsTableSeeder::class,
            CategoriesSeeder::class,
            RoleTableSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserTelusurSeeder::class,
            UserRoleSeeder::class,
            AdsenseSeeder::class,
            PageSettingSeeder::class,

            // Dipake saat development aja
            // GallerySeeder::class,
        ]);
    }
}
