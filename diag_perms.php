<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RolePermission;

$roles = ['office_in_charge', 'hs', 'dm', 'bm', 'rm', 'ro'];
foreach ($roles as $role) {
    $count = RolePermission::where('role', $role)->count();
    echo "Role: $role, Permissions: $count\n";
    if ($count > 0) {
        $perms = RolePermission::where('role', $role)->pluck('permission_key')->toArray();
        echo " - " . implode(", ", $perms) . "\n";
    }
}
