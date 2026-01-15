<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<style>body{font-family:sans-serif; padding:20px;} .status{padding:10px; margin:5px 0; border-radius:5px;} .ok{background:#d4edda; color:#155724;} .err{background:#f8d7da; color:#721c24;}</style>";
echo "<h1>Live Server Diagnostics</h1>";

// 1. Check PHP Version
$phpVer = phpversion();
echo "<h3>1. PHP Version</h3>";
if (version_compare($phpVer, '8.2.0', '<')) {
    echo "<div class='status err'>✗ PHP 8.2+ Required. Found: $phpVer</div>";
} else {
    echo "<div class='status ok'>✓ PHP Version OK ($phpVer)</div>";
}

// 2. Check RolePermission.php Code
echo "<h3>2. Code Verification (RolePermission.php)</h3>";
$roleFile = __DIR__ . '/../app/Models/RolePermission.php'; // Assuming public/../app
if (!file_exists($roleFile)) {
    // Try flat structure
    $roleFile = __DIR__ . '/app/Models/RolePermission.php';
}

if (file_exists($roleFile)) {
    $content = file_get_contents($roleFile);
    if (strpos($content, 'fallbackCheck') !== false && strpos($content, 'try {') !== false) {
        echo "<div class='status ok'>✓ RolePermission.php has the Fail-Safe Fix matched.</div>";
    } else {
        echo "<div class='status err'>✗ RolePermission.php is the OLD version! <br>You MUST upload the new file from your local computer. Line 23 error confirms this.</div>";
    }
} else {
    echo "<div class='status err'>✗ Could not find app/Models/RolePermission.php</div>";
}

// 3. Database Check
echo "<h3>3. Database Check</h3>";
// define Laravel base path
$basePath = __DIR__ . '/../';
if (file_exists($basePath . 'bootstrap/app.php')) {
    require $basePath . 'vendor/autoload.php';
    $app = require_once $basePath . 'bootstrap/app.php';
    try {
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        // Check connection
        $pdo = \DB::connection()->getPdo();
        echo "<div class='status ok'>✓ Database Connected</div>";

        // Check Table
        $tableExists = \Schema::hasTable('role_permissions');
        if ($tableExists) {
            echo "<div class='status ok'>✓ Table 'role_permissions' exists.</div>";
        } else {
            echo "<div class='status err'>✗ Table 'role_permissions' is MISSING. <br>Run migrate_db.php or import SQL.</div>";
        }

    } catch (\Exception $e) {
        echo "<div class='status err'>✗ Database Error: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='status err'>Could not load Laravel to check DB. (vendor/bootstrap missing?)</div>";
}

// 4. Session Config Check
echo "<h3>4. Session Config</h3>";
echo "Driver: " . config('session.driver') . "<br>";
echo "Secure Cookie: " . (config('session.secure') ? 'TRUE' : 'FALSE') . "<br>";
if (config('session.driver') === 'file') {
    echo "<div class='status ok'>✓ Driver is FILE (Recommended)</div>";
} else {
    echo "<div class='status err'>✗ Driver should be FILE (found " . config('session.driver') . ")</div>";
}

echo "<hr><p>Generate at " . date('Y-m-d H:i:s') . "</p>";
