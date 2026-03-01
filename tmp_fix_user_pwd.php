<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\User;

$user = User::find(6);
if ($user) {
    if (!$user->password_plain) {
        $user->password_plain = 'hf@2026';
        $user->save();
        echo "Updated password_plain for user 6 to 'hf@2026'\n";
    } else {
        echo "User 6 already has password_plain: " . $user->password_plain . "\n";
    }
} else {
    echo "User 6 not found.\n";
}
