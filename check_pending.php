<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$pending = User::pending()->get();
foreach ($pending as $p) {
    echo "ID: {$p->id}, Designation: {$p->designation}, Parent ID: " . ($p->parent_id ?? 'NULL') . "\n";
}
