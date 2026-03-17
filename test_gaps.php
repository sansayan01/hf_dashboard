<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Survey;
use Illuminate\Support\Facades\DB;

// Clear local surveys
DB::table('surveys')->truncate();

echo "Case 1: Empty database\n";
echo "Next ID: " . Survey::generatePatientId() . "\n\n";

echo "Case 2: 1 exists, 2 missing\n";
DB::table('surveys')->insert([
    'full_name' => 'Test 1',
    'patient_id' => 'HFP0000001',
    'created_by' => 1,
    'phone_number' => '123',
    'address' => 'Test',
    'pin' => '123',
    'gender' => 'male',
    'age' => 20
]);
echo "Next ID: " . Survey::generatePatientId() . "\n\n";

echo "Case 3: 1 trashed (original system), 2 exists\n";
// Manually add a "trashed" record with HFP0000001
DB::table('surveys')->insert([
    'full_name' => 'Trashed 1',
    'patient_id' => 'TRASH_HFP0000001_123',
    'created_by' => 1,
    'phone_number' => '123',
    'address' => 'Test',
    'pin' => '123',
    'gender' => 'male',
    'age' => 20,
    'deleted_at' => now()
]);
DB::table('surveys')->insert([
    'full_name' => 'Test 2',
    'patient_id' => 'HFP0000002',
    'created_by' => 1,
    'phone_number' => '123',
    'address' => 'Test',
    'pin' => '123',
    'gender' => 'male',
    'age' => 20
]);
echo "Next ID (Expecting 1 if gap filling works): " . Survey::generatePatientId() . "\n\n";

echo "Case 4: 1 is 'HFP0000001' but SOFT DELETED (old system)\n";
DB::table('surveys')->truncate();
DB::table('surveys')->insert([
    'full_name' => 'Soft Deleted 1',
    'patient_id' => 'HFP0000001',
    'created_by' => 1,
    'phone_number' => '123',
    'address' => 'Test',
    'pin' => '123',
    'gender' => 'male',
    'age' => 20,
    'deleted_at' => now()
]);
echo "Next ID (Expecting 2 if deleted 1 blocks it): " . Survey::generatePatientId() . "\n";
