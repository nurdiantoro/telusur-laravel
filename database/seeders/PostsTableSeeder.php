<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO posts (id, title, slug, post_type_id, cover, caption, video_url, content, status_id, category_id, author_id, views, publish_time, created_at, updated_at)

            SELECT id, title,slug,post_type_id,cover,caption,video_url,content,status_id,category_id,author_id,views,publish_time,created_at,updated_at
            FROM tbl_posts
        ");
    }
}
