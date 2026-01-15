<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "--- User Designations ---\n";
$designations = User::select('designation', DB::raw('count(*) as count'))
    ->groupBy('designation')
    ->get();
foreach ($designations as $d) {
    echo "{$d->designation}: {$d->count}\n";
}

echo "\n--- Root Users (parent_id is null) ---\n";
$roots = User::whereNull('parent_id')->get();
foreach ($roots as $r) {
    echo "ID: {$r->id}, Email: {$r->email}, Designation: {$r->designation}\n";
}

echo "\n--- HS Users and their parents ---\n";
$hs = User::where('designation', 'hs')->get();
foreach ($hs as $h) {
    echo "ID: {$h->id}, Email: {$h->email}, Parent ID: {$h->parent_id}\n";
}

echo "\n--- All Users with Parent ID set ---\n";
$children = User::whereNotNull('parent_id')->get();
foreach ($children as $c) {
    echo "ID: {$c->id}, Parent ID: {$c->parent_id}, Designation: {$c->designation}\n";
}
