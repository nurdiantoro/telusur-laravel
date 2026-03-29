<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | table post
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'post.create']);
        Permission::firstOrCreate(['name' => 'post.read']);
        Permission::firstOrCreate(['name' => 'post.update']);
        Permission::firstOrCreate(['name' => 'post.delete']);

        /*
        |--------------------------------------------------------------------------
        | table tags
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'tags.create']);
        Permission::firstOrCreate(['name' => 'tags.read']);
        Permission::firstOrCreate(['name' => 'tags.update']);
        Permission::firstOrCreate(['name' => 'tags.delete']);

        /*
        |--------------------------------------------------------------------------
        | table sidebar_ads
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'sidebar_ads.create']);
        Permission::firstOrCreate(['name' => 'sidebar_ads.read']);
        Permission::firstOrCreate(['name' => 'sidebar_ads.update']);
        Permission::firstOrCreate(['name' => 'sidebar_ads.delete']);

        /*
        |--------------------------------------------------------------------------
        | table post_categories
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'post_categories.create']);
        Permission::firstOrCreate(['name' => 'post_categories.read']);
        Permission::firstOrCreate(['name' => 'post_categories.update']);
        Permission::firstOrCreate(['name' => 'post_categories.delete']);

        /*
        |--------------------------------------------------------------------------
        | table comments
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'comments.create']);
        Permission::firstOrCreate(['name' => 'comments.read']);
        Permission::firstOrCreate(['name' => 'comments.update']);
        Permission::firstOrCreate(['name' => 'comments.delete']);

        /*
        |--------------------------------------------------------------------------
        | table roles
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'roles.create']);
        Permission::firstOrCreate(['name' => 'roles.read']);
        Permission::firstOrCreate(['name' => 'roles.update']);
        Permission::firstOrCreate(['name' => 'roles.delete']);

        /*
        |--------------------------------------------------------------------------
        | table users
        |--------------------------------------------------------------------------
        */
        Permission::firstOrCreate(['name' => 'users.create']);
        Permission::firstOrCreate(['name' => 'users.read']);
        Permission::firstOrCreate(['name' => 'users.update']);
        Permission::firstOrCreate(['name' => 'users.delete']);
    }
}
