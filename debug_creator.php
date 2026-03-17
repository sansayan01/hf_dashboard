<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Delete ghost ID 1 (which is ID=3 in DB)
DB::table('surveys')->where('id', 3)->delete();
echo "Deleted phantom HFP0000001 (DB ID: 3)\n";

// 2. Check the creator of the recently created surveys
$surveys = \Illuminate\Support\Facades\DB::table('surveys')
    ->select('id', 'patient_id', 'created_by')
    ->orderBy('id', 'desc')
    ->limit(4)
    ->get();

foreach ($surveys as $s) {
    echo "Patient: {$s->patient_id} | created_by: {$s->created_by}\n";
}
