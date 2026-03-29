<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTelusurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telusur.co.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@telusur.co.id'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'),
            ]
        );

        $author = User::firstOrCreate(
            ['email' => 'author@telusur.co.id'],
            [
                'name' => 'Author',
                'password' => Hash::make('password'),
            ]
        );

        $pimpinan_redaksi = User::firstOrCreate(
            ['email' => 'pimpinan_redaksi@telusur.co.id'],
            [
                'name' => 'Pimpinan Redaksi',
                'password' => Hash::make('password'),
            ]
        );

        $adminRole = Role::where('name', 'administrator')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $authorRole = Role::where('name', 'author')->first();
        $pimpinan_redaksiRole = Role::where('name', 'pimpinan redaksi')->first();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        if ($editorRole) {
            $editor->roles()->syncWithoutDetaching([$editorRole->id]);
        }

        if ($authorRole) {
            $author->roles()->syncWithoutDetaching([$authorRole->id]);
        }

        if ($pimpinan_redaksiRole) {
            $pimpinan_redaksi->roles()->syncWithoutDetaching([$pimpinan_redaksiRole->id]);
        }
    }
}
