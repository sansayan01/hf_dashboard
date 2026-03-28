<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::whereNotIn('employee_id', ['HFSA000001', 'HFSA000002'])->first();
echo "Testing User: {$user->employee_id} ({$user->designation})\n";

// Apply 'off' preset roughly for dashboard
$user->permissions = ['dashboard.view' => false];
$user->save();
echo "Saved JSON permissions: " . json_encode($user->permissions) . "\n";

// Reload and check
$reloadedUser = User::find($user->id);
echo "Reloaded JSON permissions: " . json_encode($reloadedUser->permissions) . "\n";
echo "hasPermission('dashboard.view'): " . ($reloadedUser->hasPermission('dashboard.view') ? 'YES' : 'NO') . "\n";

// Test Effective User logic
// Simulate login
auth()->login($reloadedUser);
$effectiveUser = User::getEffectiveUser();
echo "Effective User ID: {$effectiveUser->employee_id}\n";
echo "Effective User hasPermission('dashboard.view'): " . ($effectiveUser->hasPermission('dashboard.view') ? 'YES' : 'NO') . "\n";

