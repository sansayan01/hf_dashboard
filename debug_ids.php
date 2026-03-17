<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ids = \Illuminate\Support\Facades\DB::table('surveys')->pluck('patient_id')->toArray();
sort($ids);
print_r($ids);

$next = App\Models\Survey::generatePatientId();
echo "Next ID: $next\n";
