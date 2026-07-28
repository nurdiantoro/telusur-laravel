<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

foreach (DB::select('SHOW TABLES') as $t) {
    $arr = (array)$t;
    $table = reset($arr);
    $cols = Schema::getColumnListing($table);
    if (in_array('gallery', $cols) || in_array('image', $cols)) {
        echo $table . ': ' . implode(',', $cols) . PHP_EOL;
    }
}
