<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$dms = User::where('designation', 'dm')->get();
foreach ($dms as $dm) {
    echo "DM ID: {$dm->id}, Parent ID: " . ($dm->parent_id ?? 'NULL') . "\n";
}
