<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$res = DB::table('users as child')
    ->join('users as parent', 'child.parent_id', '=', 'parent.id')
    ->select('parent.designation as p_designation', 'child.designation as c_designation', DB::raw('count(*) as count'))
    ->groupBy('p_designation', 'c_designation')
    ->get();

foreach ($res as $r) {
    echo "Parent: {$r->p_designation} -> Child: {$r->c_designation} ({$r->count})\n";
}
