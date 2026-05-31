<?php

namespace Database\Seeders;

use App\Models\PageSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageSetting::firstOrCreate(
            ['id' => 1],
            [
                'image_header' => '',
            ]
        );
    }
}
