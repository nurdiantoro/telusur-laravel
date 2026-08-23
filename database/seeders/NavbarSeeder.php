<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavbarSeeder extends Seeder
{
    // Mengatur ulang struktur kategori: Kriminal, Politik, dan Hukum menjadi subkategori Polhukam.
    // Kategori yang aktif di navbar: Polhukam, Nasional, Megapol, Daerah, Internasional,
    // Ekuin, Sport, Gaya Hidup, Telusuria, Kriminal, Politik, dan Hukum.
    // Sekaligus mengatur urutan tampil seluruh kategori sesuai sort_order yang ditentukan.

    public function run(): void
    {
        DB::statement("
            UPDATE post_categories
            SET
                parent_id = CASE id
                    WHEN 1 THEN NULL
                    WHEN 2 THEN 17
                    WHEN 3 THEN NULL
                    WHEN 4 THEN NULL
                    WHEN 5 THEN NULL
                    WHEN 6 THEN NULL
                    WHEN 7 THEN NULL
                    WHEN 8 THEN NULL
                    WHEN 9 THEN NULL
                    WHEN 10 THEN NULL
                    WHEN 11 THEN NULL
                    WHEN 12 THEN NULL
                    WHEN 14 THEN NULL
                    WHEN 15 THEN NULL
                    WHEN 16 THEN NULL
                    WHEN 17 THEN NULL
                    WHEN 18 THEN NULL
                    WHEN 19 THEN NULL
                    WHEN 20 THEN NULL
                    WHEN 21 THEN NULL
                    WHEN 23 THEN 17
                    WHEN 24 THEN 17
                    WHEN 25 THEN NULL
                    WHEN 26 THEN NULL
                    WHEN 27 THEN NULL
                    WHEN 28 THEN NULL
                    WHEN 29 THEN NULL
                END,

                is_navbar = CASE id
                    WHEN 1 THEN 0
                    WHEN 2 THEN 1
                    WHEN 3 THEN 1
                    WHEN 4 THEN 1
                    WHEN 5 THEN 1
                    WHEN 6 THEN 1
                    WHEN 7 THEN 1
                    WHEN 8 THEN 1
                    WHEN 9 THEN 1
                    WHEN 10 THEN 1
                    WHEN 11 THEN 0
                    WHEN 12 THEN 0
                    WHEN 14 THEN 0
                    WHEN 15 THEN 0
                    WHEN 16 THEN 0
                    WHEN 17 THEN 1
                    WHEN 18 THEN 0
                    WHEN 19 THEN 0
                    WHEN 20 THEN 0
                    WHEN 21 THEN 0
                    WHEN 23 THEN 1
                    WHEN 24 THEN 1
                    WHEN 25 THEN 0
                    WHEN 26 THEN 0
                    WHEN 27 THEN 0
                    WHEN 28 THEN 0
                    WHEN 29 THEN 0
                END,

                sort_order = CASE id
                    WHEN 1 THEN 13
                    WHEN 2 THEN 12
                    WHEN 3 THEN 2
                    WHEN 4 THEN 3
                    WHEN 5 THEN 4
                    WHEN 6 THEN 5
                    WHEN 7 THEN 6
                    WHEN 8 THEN 7
                    WHEN 9 THEN 8
                    WHEN 10 THEN 9
                    WHEN 11 THEN 14
                    WHEN 12 THEN 15
                    WHEN 14 THEN 16
                    WHEN 15 THEN 17
                    WHEN 16 THEN 18
                    WHEN 17 THEN 1
                    WHEN 18 THEN 19
                    WHEN 19 THEN 20
                    WHEN 20 THEN 21
                    WHEN 21 THEN 22
                    WHEN 23 THEN 10
                    WHEN 24 THEN 11
                    WHEN 25 THEN 23
                    WHEN 26 THEN 24
                    WHEN 27 THEN 25
                    WHEN 28 THEN 26
                    WHEN 29 THEN 27
                END

            WHERE id IN (
                1,2,3,4,5,6,7,8,9,10,
                11,12,14,15,16,17,18,19,20,21,
                23,24,25,26,27,28,29
            )
        ");
    }
}
