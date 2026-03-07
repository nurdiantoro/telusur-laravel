<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
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
                            3001 => 'post',
                            3002 => 'opini',
                            3003 => 'video',
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
