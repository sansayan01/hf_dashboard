<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("ALTER TABLE coupon_codes MODIFY designation ENUM('dm', 'bm', 'rm', 'ro', 'membership') NULL");
    echo "SUCCESS: Column updated.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
