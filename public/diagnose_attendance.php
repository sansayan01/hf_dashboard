<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php'
];

$autoloadFile = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloadFile = $path;
        break;
    }
}
if (!$autoloadFile)
    die("Could not find vendor/autoload.php");

require $autoloadFile;

$appFile = dirname($autoloadFile) . '/../bootstrap/app.php';
if (!file_exists($appFile))
    $appFile = dirname($autoloadFile) . '/bootstrap/app.php';
if (!file_exists($appFile))
    die("Could not find bootstrap/app.php");

$app = require_once $appFile;
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "<style>body{font-family:sans-serif;padding:20px}.ok{background:#d4edda;color:#155724;padding:8px;margin:4px 0;border-radius:4px}.err{background:#f8d7da;color:#721c24;padding:8px;margin:4px 0;border-radius:4px}</style>";
echo "<h1>Attendance Diagnostic</h1>";

// Check attendances table columns
echo "<h3>1. Attendances Table</h3>";
$requiredCols = ['id', 'user_id', 'marked_by', 'status', 'date', 'incentive_amount', 'ta_amount', 'total_amount', 'medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount'];
foreach ($requiredCols as $col) {
    if (Schema::hasColumn('attendances', $col)) {
        echo "<div class='ok'>✓ attendances.$col exists</div>";
    } else {
        echo "<div class='err'>✗ attendances.$col MISSING!</div>";
    }
}

// Check incentive_configs table
echo "<h3>2. Incentive Configs Table</h3>";
if (Schema::hasTable('incentive_configs')) {
    echo "<div class='ok'>✓ incentive_configs table exists</div>";
    $icCols = ['id', 'user_id', 'designation', 'incentive_amount', 'ta_amount', 'medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount', 'effective_from'];
    foreach ($icCols as $col) {
        if (Schema::hasColumn('incentive_configs', $col)) {
            echo "<div class='ok'>✓ incentive_configs.$col exists</div>";
        } else {
            echo "<div class='err'>✗ incentive_configs.$col MISSING!</div>";
        }
    }
} else {
    echo "<div class='err'>✗ incentive_configs table MISSING!</div>";
}

// Check users.salary_mode
echo "<h3>3. Users Table - salary_mode</h3>";
if (Schema::hasColumn('users', 'salary_mode')) {
    echo "<div class='ok'>✓ users.salary_mode exists</div>";
} else {
    echo "<div class='err'>✗ users.salary_mode MISSING! This will cause attendance store to fail.</div>";
}

// Check latest laravel.log errors
echo "<h3>4. Recent Laravel Errors (last 50 lines with 'attendance' or 'Attendance')</h3>";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -200);
    $relevant = [];
    foreach ($lastLines as $line) {
        if (stripos($line, 'attendance') !== false || stripos($line, 'SQLSTATE') !== false || stripos($line, 'salary_mode') !== false || stripos($line, 'incentive') !== false) {
            $relevant[] = htmlspecialchars(trim($line));
        }
    }
    if (!empty($relevant)) {
        echo "<pre style='background:#f5f5f5;padding:10px;overflow:auto;max-height:400px'>" . implode("\n", array_slice($relevant, -30)) . "</pre>";
    } else {
        echo "<div class='ok'>No attendance-related errors found in recent logs</div>";
    }
} else {
    echo "<div class='err'>Log file not found at $logFile</div>";
}

// Check pending migrations
echo "<h3>5. Pending Migrations</h3>";
try {
    \Artisan::call('migrate:status');
    $output = \Artisan::output();
    $pendingLines = [];
    foreach (explode("\n", $output) as $line) {
        if (stripos($line, 'Pending') !== false || stripos($line, 'attendance') !== false || stripos($line, 'incentive') !== false || stripos($line, 'salary_mode') !== false) {
            $pendingLines[] = htmlspecialchars(trim($line));
        }
    }
    if (!empty($pendingLines)) {
        echo "<pre style='background:#fff3cd;padding:10px'>" . implode("\n", $pendingLines) . "</pre>";
    } else {
        echo "<div class='ok'>All relevant migrations appear to be run</div>";
    }
} catch (\Exception $e) {
    echo "<div class='err'>Could not check migration status: " . $e->getMessage() . "</div>";
}

echo "<hr><p>Generated at " . date('Y-m-d H:i:s') . "</p>";
