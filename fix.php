<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Starting Comprehensive Database Repair...\n";

    // 1. Fix coupon_codes designation
    echo "Updating coupon_codes designation enum...\n";
    DB::statement("ALTER TABLE coupon_codes MODIFY designation ENUM('dm', 'bm', 'rm', 'ro', 'membership') NULL");
    echo "SUCCESS: coupon_codes updated.\n";

    // 2. Add missing columns to surveys table
    echo "Checking for missing columns in surveys table...\n";
    $columns = [
        'membership_fee' => "DECIMAL(10, 2) DEFAULT 0 AFTER is_member",
        'discount_percentage' => "DECIMAL(5, 2) DEFAULT 0 AFTER membership_fee",
        'discount_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER discount_percentage",
        'final_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER discount_amount",
        'amount_paid' => "DECIMAL(10, 2) DEFAULT 0 AFTER final_amount",
        'due_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER amount_paid",
        'payment_method' => "VARCHAR(255) NULL AFTER due_amount",
        'payment_screenshot' => "VARCHAR(255) NULL AFTER payment_method",
    ];

    foreach ($columns as $column => $definition) {
        if (!Schema::hasColumn('surveys', $column)) {
            echo "Adding column: $column...\n";
            DB::statement("ALTER TABLE surveys ADD COLUMN $column $definition");
            echo "SUCCESS: Added $column.\n";
        } else {
            echo "SKIP: $column already exists.\n";
        }
    }

    // 3. Mark migrations as completed to fix the migration system
    echo "Marking migrations as completed...\n";
    $migrations = [
        '2026_02_20_000000_create_chatbot_training_data_table',
        '2026_02_23_045900_add_payment_details_to_surveys_table',
        '2026_02_25_000000_update_coupon_codes_designation_enum'
    ];

    foreach ($migrations as $m) {
        $exists = DB::table('migrations')->where('migration', $m)->exists();
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $m,
                'batch' => (DB::table('migrations')->max('batch') ?? 0) + 1
            ]);
            echo "Marked $m as migrated.\n";
        } else {
            echo "Migration $m already marked.\n";
        }
    }

    echo "\nALL REPAIRS COMPLETED SUCCESSFULLY.\n";
} catch (\Exception $e) {
    echo "\nCRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
