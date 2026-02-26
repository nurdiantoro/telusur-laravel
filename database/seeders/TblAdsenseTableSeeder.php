<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblAdsenseTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_adsense')->insert(array(
            0 =>
            array(
                'id' => 2,
                'slug' => 'inarticle2',
                'label' => 'Di Dalam Artikel 2',
                'script' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-1795135597705919"
     data-ad-slot="8091838128"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>',
                'created_at' => '2019-07-16 21:32:25',
                'updated_at' => '2019-09-09 11:16:48',
            ),
            1 =>
            array(
                'id' => 6,
                'slug' => 'sidebar1',
                'label' => 'Sidebar',
                'script' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- FIX -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1795135597705919"
     data-ad-slot="5431317558"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>',
                'created_at' => '2019-07-16 21:29:55',
                'updated_at' => '2019-09-09 11:14:32',
            ),
            2 =>
            array(
                'id' => 7,
                'slug' => 'inlist',
                'label' => 'Dalam List Berita',
                'script' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Persegi -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-1795135597705919"
     data-ad-slot="8853657430"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>',
                'created_at' => '2019-07-16 21:59:57',
                'updated_at' => '2019-09-09 11:14:32',
            ),
        ));
    }
}
