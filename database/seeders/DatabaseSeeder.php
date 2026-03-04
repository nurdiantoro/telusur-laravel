<?php

namespace Database\Seeders;

use App\Models\User;
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
        // $this->call(TblAdsenseTableSeeder::class);
        // $this->call(TblCommentsTableSeeder::class);
        // $this->call(TblImageHeaderTableSeeder::class);

        // tabel baru
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CategoriesSeeder::class);
    }
}
