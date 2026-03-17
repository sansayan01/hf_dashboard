<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$surveys = \Illuminate\Support\Facades\DB::table('surveys')
    ->select('id', 'patient_id', 'created_at', 'full_name')
    ->orderBy('id')
    ->get();

foreach ($surveys as $s) {
    echo "ID: {$s->id} | Patient ID: {$s->patient_id} | Name: {$s->full_name} | Created: {$s->created_at}\n";
}
