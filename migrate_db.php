<?php

// Smart path detection for Autoload
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php', // Standard: public is sudirectory
    __DIR__ . '/vendor/autoload.php'     // Flat: public is root
];

$autoloadFile = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloadFile = $path;
        break;
    }
}

if (!$autoloadFile) {
    die("<h3>Error: Could not find vendor/autoload.php</h3><p>Please check your directory structure.</p>");
}

require $autoloadFile;

// Smart path detection for Bootstrap
$appFile = dirname($autoloadFile) . '/../bootstrap/app.php';
if (!file_exists($appFile)) {
    $appFile = dirname($autoloadFile) . '/bootstrap/app.php';
}

if (!file_exists($appFile)) {
    die("<h3>Error: Could not find bootstrap/app.php</h3>");
}

$app = require_once $appFile;

// Boot Kernel
try {
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
} catch (\Exception $e) {
    die("<h3>Boot Error:</h3> " . $e->getMessage());
}

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "<div style='font-family:sans-serif; padding:20px;'>";
echo "<h1>Database Migration Tool</h1>";

try {
    echo "<p><strong>Database:</strong> " . DB::connection()->getDatabaseName() . "</p>";
    echo "<p>Attempting to run migrations...</p>";

    // Run Migrations
    Artisan::call('migrate', ['--force' => true]);

    echo "<div style='background:#f5f5f5; padding:15px; border:1px solid #ddd; border-radius:5px; margin: 20px 0;'>";
    echo "<h3>Output:</h3>";
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "</div>";

    echo "<h2 style='color:green'>✓ Migrations completed successfully!</h2>";
    echo "<p>The 'role_permissions' table should now exist.</p>";

} catch (\Exception $e) {
    echo "<h2 style='color:red'>✗ Migration Failed</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
echo "</div>";
