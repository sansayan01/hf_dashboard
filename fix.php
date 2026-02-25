<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Starting Comprehensive Database Repair v2...\n";

    // 1. Fix coupon_codes designation
    echo "Updating coupon_codes designation enum...\n";
    DB::statement("ALTER TABLE coupon_codes MODIFY designation ENUM('dm', 'bm', 'rm', 'ro', 'membership') NULL");
    echo "SUCCESS: coupon_codes updated.\n";

    // 2. Add missing columns to surveys table
    echo "Checking for missing columns in surveys table...\n";
    $surveyColumns = [
        'membership_fee' => "DECIMAL(10, 2) DEFAULT 0 AFTER is_member",
        'discount_percentage' => "DECIMAL(5, 2) DEFAULT 0 AFTER membership_fee",
        'discount_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER discount_percentage",
        'final_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER discount_amount",
        'amount_paid' => "DECIMAL(10, 2) DEFAULT 0 AFTER final_amount",
        'due_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER amount_paid",
        'payment_method' => "VARCHAR(255) NULL AFTER due_amount",
        'payment_screenshot' => "VARCHAR(255) NULL AFTER payment_method",
    ];

    foreach ($surveyColumns as $column => $definition) {
        if (!Schema::hasColumn('surveys', $column)) {
            echo "Adding column to surveys: $column...\n";
            DB::statement("ALTER TABLE surveys ADD COLUMN $column $definition");
            echo "SUCCESS: Added $column to surveys.\n";
        } else {
            echo "SKIP: $column already exists in surveys.\n";
        }
    }

    // 3. Add missing columns to attendances table
    echo "Checking for missing columns in attendances table...\n";

    // Check marked_by specifically as it's a foreign key usually
    if (!Schema::hasColumn('attendances', 'marked_by')) {
        echo "Adding column to attendances: marked_by...\n";
        // Adding as simple bigint first to avoid foreign key constraints errors if user doesn't exist
        DB::statement("ALTER TABLE attendances ADD COLUMN marked_by BIGINT UNSIGNED NULL AFTER user_id");
        echo "SUCCESS: Added marked_by to attendances.\n";
    }

    $attendanceColumns = [
        'incentive_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER status",
        'ta_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER incentive_amount",
        'medicines_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER ta_amount",
        'pathology_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER medicines_amount",
        'membership_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER pathology_amount",
        'ots_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER membership_amount",
        'total_amount' => "DECIMAL(10, 2) DEFAULT 0 AFTER ots_amount",
    ];

    foreach ($attendanceColumns as $column => $definition) {
        if (!Schema::hasColumn('attendances', $column)) {
            echo "Adding column to attendances: $column...\n";
            DB::statement("ALTER TABLE attendances ADD COLUMN $column $definition");
            echo "SUCCESS: Added $column to attendances.\n";
        } else {
            echo "SKIP: $column already exists in attendances.\n";
        }
    }

    // 4. Mark migrations as completed
    echo "Marking migrations as completed...\n";
    $migrations = [
        '2026_02_10_014732_add_payment_method_to_medicine_distributions_table',
        '2026_02_10_153538_add_post_to_users_table',
        '2026_02_11_034943_add_payment_columns_to_medicine_distributions_table',
        '2026_02_20_000000_create_chatbot_training_data_table',
        '2026_02_23_000000_create_incentive_configs_table',
        '2026_02_23_000001_update_attendances_table',
        '2026_02_23_000002_add_categories_to_incentive_configs.php', // Note: some might have .php and some not in DB, Laravel usually stores without extension
        '2026_02_23_000003_add_categories_to_attendances',
        '2026_02_23_022230_add_designation_to_incentive_configs',
        '2026_02_23_043000_create_pathology_tests_table',
        '2026_02_23_045442_add_camp_id_to_pathology_tests_table',
        '2026_02_23_045853_add_payment_details_to_pathology_tests_table',
        '2026_02_23_045900_add_payment_details_to_surveys_table',
        '2026_02_25_000000_update_coupon_codes_designation_enum'
    ];

    $batch = (DB::table('migrations')->max('batch') ?? 0) + 1;
    foreach ($migrations as $m) {
        // Handle both with and without .php suffix just in case
        $mBase = str_replace('.php', '', $m);
        $exists = DB::table('migrations')->where('migration', 'like', $mBase . '%')->exists();
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $mBase,
                'batch' => $batch
            ]);
            echo "Marked $mBase as migrated.\n";
        } else {
            echo "Migration $mBase already marked.\n";
        }
    }

    echo "\nALL REPAIRS COMPLETED SUCCESSFULLY.\n";
} catch (\Exception $e) {
    echo "\nCRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
