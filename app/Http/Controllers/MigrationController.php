<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    public function ubahkategori()
    {
        DB::beginTransaction();

        try {
            $total = 0;

            Post::whereNotNull('category_id')
                ->orderBy('id') // wajib untuk chunk
                ->chunk(500, function ($posts) use (&$total) {

                    foreach ($posts as $post) {
                        DB::table('pivot_post_categories')->updateOrInsert(
                            [
                                'post_id' => $post->id,
                                'post_category_id' => $post->category_id,
                            ],
                            []
                        );

                        $total++;
                    }
                });

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Migrasi category_id ke pivot berhasil',
                'total'   => $total,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function ubahstatus()
    {
        Post::whereNotNull('status_id')
            ->chunkById(500, function ($posts) {

                DB::beginTransaction();

                try {
                    foreach ($posts as $post) {
                        $status = match ($post->status_id) {
                            2001, 2002 => 'draft',
                            2003 => 'published',
                            2004 => 'unpublished',
                            default => null,
                        };

                        if ($status) {
                            DB::table('posts')
                                ->where('id', $post->id)
                                ->update(['status' => $status]);
                        }
                    }

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Migrasi status selesai',
        ]);
    }

    public function ubahtype()
    {
        Post::whereNotNull('post_type_id')
            ->chunkById(500, function ($posts) {

                DB::beginTransaction();

                try {
                    foreach ($posts as $post) {

                        $type = match ($post->post_type_id) {
                            3001 => 'Post',
                            3002 => 'Opini',
                            3003 => 'Berita Video',
                            default => null,
                        };

                        if ($type) {
                            DB::table('posts')
                                ->where('id', $post->id)
                                ->update([
                                    'type' => $type
                                ]);
                        }
                    }

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e; // biar Laravel tetap tahu ada error
                }
            });

        return response()->json([
            'status'  => 'success',
            'message' => 'Migrasi post type selesai',
        ]);
    }
}
