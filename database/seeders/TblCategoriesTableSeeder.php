<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_categories')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Pemilu',
                'slug' => 'pemilu',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Kriminal',
                'slug' => 'kriminal',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'NEWS',
                'slug' => 'news',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'SUMUT',
                'slug' => 'sumut',
            ),
            4 =>
            array(
                'id' => 6,
                'name' => 'Internasional',
                'slug' => 'internasional',
            ),
            5 =>
            array(
                'id' => 7,
                'name' => 'Ekuin',
                'slug' => 'ekuin',
            ),
            6 =>
            array(
                'id' => 8,
                'name' => 'Sport',
                'slug' => 'sport',
            ),
            7 =>
            array(
                'id' => 9,
                'name' => 'Gaya Hidup',
                'slug' => 'gaya-hidup',
            ),
            8 =>
            array(
                'id' => 11,
                'name' => 'Index',
                'slug' => 'index',
            ),
            9 =>
            array(
                'id' => 12,
                'name' => 'Parlemen',
                'slug' => 'parlemen',
            ),
            10 =>
            array(
                'id' => 14,
                'name' => 'MPR',
                'slug' => 'mpr',
            ),
            11 =>
            array(
                'id' => 15,
                'name' => 'DPR',
                'slug' => 'dpr',
            ),
            12 =>
            array(
                'id' => 16,
                'name' => 'DPD',
                'slug' => 'dpd',
            ),
            13 =>
            array(
                'id' => 17,
                'name' => 'Polhukam',
                'slug' => 'polhukam-2',
            ),
            14 =>
            array(
                'id' => 18,
                'name' => 'Headline',
                'slug' => 'headline',
            ),
            15 =>
            array(
                'id' => 19,
                'name' => 'Berita Foto',
                'slug' => 'berita-foto',
            ),
            16 =>
            array(
                'id' => 20,
                'name' => 'Hukum Dan Politik',
                'slug' => 'hukum-dan-politik',
            ),
            17 =>
            array(
                'id' => 21,
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
            ),
            18 =>
            array(
                'id' => 22,
                'name' => 'UMKM',
                'slug' => 'umkm',
            ),
            19 =>
            array(
                'id' => 23,
                'name' => 'politik',
                'slug' => 'politik',
            ),
            20 =>
            array(
                'id' => 24,
                'name' => 'hukum',
                'slug' => 'hukum',
            ),
            21 =>
            array(
                'id' => 25,
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
            ),
            22 =>
            array(
                'id' => 26,
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
            ),
            23 =>
            array(
                'id' => 27,
                'name' => 'Koperasi UKM/UMKM',
                'slug' => 'koperasi-ukmumkm',
            ),
            24 =>
            array(
                'id' => 28,
                'name' => 'Pemerintahan',
                'slug' => 'pemerintahan',
            ),
            25 =>
            array(
                'id' => 5,
                'name' => 'Daerah',
                'slug' => 'daerah',
            ),
        ));
    }
}
