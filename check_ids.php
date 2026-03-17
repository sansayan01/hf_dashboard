<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Survey;
use Illuminate\Support\Facades\DB;

echo "--- Unique Prefixes in patient_id ---\n";
$prefixes = DB::table('surveys')
    ->selectRaw("DISTINCT REGEXP_REPLACE(patient_id, '[0-9]+$', '') as prefix")
    ->pluck('prefix');
foreach ($prefixes as $p) echo "- $p\n";

echo "\n--- Unique Prefixes in survey_id ---\n";
$survey_prefixes = DB::table('surveys')
    ->selectRaw("DISTINCT REGEXP_REPLACE(survey_id, '[0-9]+$', '') as prefix")
    ->pluck('prefix');
foreach ($survey_prefixes as $p) echo "- $p\n";

echo "\n--- Checking for ID 1 gaps for HFP ---\n";
$hfp1 = DB::table('surveys')
    ->where('patient_id', 'HFP0000001')
    ->count();
echo "HFP0000001 count: $hfp1\n";

$hfpo1 = DB::table('surveys')
    ->where('patient_id', 'HFPO000001')
    ->count();
echo "HFPO000001 count: $hfpo1\n";

$hfpo_any = DB::table('surveys')
    ->where('patient_id', 'like', 'HFPO%')
    ->limit(5)
    ->pluck('patient_id');
echo "Sample HFPO IDs: " . implode(', ', $hfpo_any->toArray()) . "\n";
