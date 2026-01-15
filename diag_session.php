<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Session Diagnostics ===\n\n";

// Check session driver
echo "Session Driver: " . config('session.driver') . "\n";
echo "Session Lifetime: " . config('session.lifetime') . " minutes\n";
echo "Session Table: " . config('session.table') . "\n\n";

// Check if sessions table exists
try {
    $tableExists = DB::select("SHOW TABLES LIKE 'sessions'");
    if (count($tableExists) > 0) {
        echo "✓ Sessions table exists\n";

        // Check table structure
        $columns = DB::select("DESCRIBE sessions");
        echo "\nTable Structure:\n";
        foreach ($columns as $col) {
            echo "  - {$col->Field} ({$col->Type})\n";
        }

        // Count sessions
        $count = DB::table('sessions')->count();
        echo "\nTotal sessions in database: $count\n";

        // Show recent sessions
        if ($count > 0) {
            echo "\nRecent sessions:\n";
            $recent = DB::table('sessions')->orderBy('last_activity', 'desc')->limit(5)->get();
            foreach ($recent as $session) {
                $lastActivity = date('Y-m-d H:i:s', $session->last_activity);
                echo "  - ID: " . substr($session->id, 0, 10) . "... | Last Activity: $lastActivity\n";
            }
        }
    } else {
        echo "✗ Sessions table does NOT exist\n";
    }
} catch (\Exception $e) {
    echo "✗ Error checking sessions table: " . $e->getMessage() . "\n";
}

// Check storage permissions
echo "\n=== Storage Permissions ===\n";
$storagePath = storage_path('framework/sessions');
echo "Session storage path: $storagePath\n";
echo "Directory exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Directory writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

// Check .env SESSION settings
echo "\n=== Environment Variables ===\n";
echo "SESSION_DRIVER: " . (env('SESSION_DRIVER') ?: 'not set (using default)') . "\n";
echo "SESSION_LIFETIME: " . (env('SESSION_LIFETIME') ?: 'not set (using default)') . "\n";
echo "SESSION_SECURE_COOKIE: " . (env('SESSION_SECURE_COOKIE') ?: 'not set') . "\n";
echo "SESSION_SAME_SITE: " . (env('SESSION_SAME_SITE') ?: 'not set (using default: lax)') . "\n";
