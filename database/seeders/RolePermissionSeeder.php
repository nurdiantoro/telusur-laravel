<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil Role nya
        |--------------------------------------------------------------------------
        */
        $admin = Role::where('name', 'administrator')->first();
        $editor = Role::where('name', 'editor')->first();
        $author = Role::where('name', 'author')->first();
        $pimpinan_redaksi = Role::where('name', 'pimpinan redaksi')->first();

        /*
        |--------------------------------------------------------------------------
        | 2. Ambil semua permission
        |--------------------------------------------------------------------------
        */
        $allPermissions = Permission::pluck('id');

        /*
        |--------------------------------------------------------------------------
        | 3. Sync permissions ke masing-masing role
        |    - administrator dapat semua permission
        |    - editor dapat permission terkait post, tags, dan post_categories
        |    - autor dapat permission terkait post dan tags
        |    - pimpinan_redaksi dapat semua permission
        |--------------------------------------------------------------------------
        */
        if ($admin) {
            $admin->permissions()->sync($allPermissions);
        }
        if ($editor) {
            $editorPermissions = Permission::whereIn('name', [
                'post.create',
                'post.read',
                'post.update',

                'tags.create',
                'tags.read',

                'post_categories.read',
            ])->pluck('id');
            $editor->permissions()->sync($editorPermissions);
        }
        if ($author) {
            $authorPermissions = Permission::whereIn('name', [
                'post.create',
                'post.read',
                'post.update',

                'tags.create',
                'tags.read',
            ])->pluck('id');
            $author->permissions()->sync($authorPermissions);
        }
        if ($pimpinan_redaksi) {
            $pimpinan_redaksi->permissions()->sync($allPermissions);
        }
    }
}
