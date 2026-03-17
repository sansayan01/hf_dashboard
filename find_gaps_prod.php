<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$surveys = App\Models\Survey::withTrashed()
    ->where('patient_id', 'like', 'HFP%')
    ->where('patient_id', 'not like', 'TRASH_%')
    ->pluck('patient_id')
    ->map(function ($id) {
        $seq = substr($id, 3);
        return is_numeric($seq) ? (int)$seq : null;
    })
    ->filter()
    ->sort()
    ->values()
    ->toArray();

echo 'Total Parsed IDs: ' . count($surveys) . PHP_EOL;
echo 'Lowest ID: ' . ($surveys[0] ?? 'N/A') . PHP_EOL;
echo 'Highest ID: ' . end($surveys) . PHP_EOL;

$gaps = [];
for ($i = 1; $i < count($surveys); $i++) {
    if ($surveys[$i] - $surveys[$i-1] > 1) {
        $gaps[] = [$surveys[$i-1] + 1, $surveys[$i] - 1];
        if (count($gaps) > 10) break; // only print first 10 gaps
    }
}

echo 'First 10 Gaps:' . PHP_EOL;
foreach ($gaps as $gap) {
    if ($gap[0] == $gap[1]) {
        echo $gap[0] . PHP_EOL;
    } else {
        echo $gap[0] . ' to ' . $gap[1] . PHP_EOL;
    }
}
echo 'Gap filler says next ID is: ' . App\Models\Survey::generatePatientId() . PHP_EOL;
