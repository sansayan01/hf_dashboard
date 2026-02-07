<?php
// standalone diagnostic script

echo "<h1>Environment Diagnostic</h1>";

// 1. PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";

// 2. Extensions
echo "<h2>2. Extensions</h2>";
$extensions = ['gd', 'zip', 'mbstring', 'curl', 'xml'];
foreach ($extensions as $ext) {
    echo "Extension '$ext': " . (extension_loaded($ext) ? "<span style='color:green'>LOADED</span>" : "<span style='color:red'>MISSING</span>") . "<br>";
}

// 3. Resource Limits
echo "<h2>3. Resource Limits</h2>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

// 4. DomPDF Check
echo "<h2>4. DomPDF Availability</h2>";
try {
    include 'vendor/autoload.php';
    if (class_exists('Dompdf\Dompdf')) {
        echo "<span style='color:green'>Dompdf is available</span><br>";
    } else {
        echo "<span style='color:red'>Dompdf NOT found in vendor</span><br>";
    }
} catch (Exception $e) {
    echo "Error checking Dompdf: " . $e->getMessage() . "<br>";
}

// 5. ZIP Command Check (PowerShell)
echo "<h2>5. PowerShell Check</h2>";
$output = [];
$returnVar = -1;
exec('powershell -Command "Get-Command Compress-Archive"', $output, $returnVar);
if ($returnVar === 0) {
    echo "<span style='color:green'>PowerShell Compress-Archive is AVAILABLE</span><br>";
} else {
    echo "<span style='color:red'>PowerShell Compress-Archive is NOT available</span><br>";
}

echo "<h2>6. Laravel Storage Path</h2>";
$storagePath = __DIR__ . '/storage/app';
if (is_writable($storagePath)) {
    echo "<span style='color:green'>Storage path is WRITABLE: $storagePath</span><br>";
} else {
    echo "<span style='color:red'>Storage path is NOT WRITABLE: $storagePath</span><br>";
}
