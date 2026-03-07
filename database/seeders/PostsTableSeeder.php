<?php

namespace Database\Seeders;

use App\Models\Post;
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
        // copy data dari tabel lama
        DB::statement("
            INSERT INTO posts (id,title,slug,cover,caption,video_url,content,category_id,author_id,views,publish_time,created_at,updated_at)

            SELECT id,title,slug,cover,caption,video_url,content,category_id,author_id,views,publish_time,created_at,updated_at
            FROM tbl_posts
        ");

        // isi kolom type berdasarkan post_type_id dari tabel lama
        DB::statement("
            UPDATE posts p
            JOIN tbl_posts t ON p.id = t.id
            SET p.type = CASE
                WHEN t.post_type_id = 3001 THEN 'post'
                WHEN t.post_type_id = 3002 THEN 'opini'
                WHEN t.post_type_id = 3003 THEN 'video'
                ELSE NULL
            END
        ");

        // isi kolom status berdasarkan status_id dari tabel lama
        DB::statement("
            UPDATE posts p
            JOIN tbl_posts t ON p.id = t.id
            SET p.status = CASE
                WHEN t.status_id IN (2001, 2002) THEN 'draft'
                WHEN t.status_id = 2003 THEN 'published'
                WHEN t.status_id = 2004 THEN 'unpublished'
                ELSE NULL
            END
");
    }
}
