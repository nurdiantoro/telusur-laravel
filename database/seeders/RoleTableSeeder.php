<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $roleNames = DB::table('set_libraries')
            ->where('category_id', 10)
            ->pluck('name')
            ->map(fn($name) => strtolower(trim($name)))
            ->filter()
            ->unique();

        Role::insertOrIgnore(
            $roleNames->map(fn($name) => ['name' => $name])->toArray()
        );
    }
}
