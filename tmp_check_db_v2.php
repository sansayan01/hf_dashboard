<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;
use App\Models\User;

$id = 6;
$user = User::find($id);

echo "COLUMN EXISTS: " . (Schema::hasColumn('users', 'password_plain') ? 'YES' : 'NO') . "\n";
if ($user) {
    echo "USER ID: " . $user->id . "\n";
    echo "EMAIL: " . $user->email . "\n";
    echo "PASSWORD_PLAIN: " . ($user->password_plain ?? 'NULL') . "\n";
} else {
    echo "USER $id NOT FOUND\n";
}
