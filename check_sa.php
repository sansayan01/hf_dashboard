<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$sas = User::where('designation', 'super_admin')->get();
foreach ($sas as $sa) {
    echo "SA ID: {$sa->id}, Email: {$sa->email}, Parent ID: " . ($sa->parent_id ?? 'NULL') . "\n";
}
