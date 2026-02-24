<?php

use App\Models\Attendance;
use App\Models\IncentiveConfig;
use App\Models\User;
use App\Services\IncentiveService;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::beginTransaction();

try {
    echo "--- Starting Incentive Verification ---\n";

    // 1. Setup Test Users
    $dm = User::where('designation', 'dm')->first() ?: User::factory()->create(['designation' => 'dm', 'employee_id' => 'TEST_DM_' . time()]);
    $bm = User::where('designation', 'bm')->where('parent_id', $dm->id)->first() ?: User::factory()->create(['designation' => 'bm', 'parent_id' => $dm->id, 'employee_id' => 'TEST_BM_' . time()]);
    $rm = User::where('designation', 'rm')->where('parent_id', $bm->id)->first() ?: User::factory()->create(['designation' => 'rm', 'parent_id' => $bm->id, 'employee_id' => 'TEST_RM_' . time()]);
    $ro = User::where('designation', 'ro')->where('parent_id', $rm->id)->first() ?: User::factory()->create(['designation' => 'ro', 'parent_id' => $rm->id, 'employee_id' => 'TEST_RO_' . time()]);

    echo "Chain created: DM({$dm->id}) -> BM({$bm->id}) -> RM({$rm->id}) -> RO({$ro->id})\n";

    // 2. Setup Configs
    IncentiveConfig::whereIn('designation', ['ro', 'rm', 'bm', 'dm'])->whereNull('user_id')->delete();
    IncentiveConfig::create(['designation' => 'ro', 'medicines_amount' => 10, 'pathology_amount' => 10, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);
    IncentiveConfig::create(['designation' => 'rm', 'medicines_amount' => 5, 'pathology_amount' => 5, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);
    IncentiveConfig::create(['designation' => 'bm', 'medicines_amount' => 2, 'pathology_amount' => 2, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);
    IncentiveConfig::create(['designation' => 'dm', 'medicines_amount' => 1, 'pathology_amount' => 1, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);

    echo "Incentive configs set.\n";

    // 3. Clear today's attendance for these users
    Attendance::where('date', now()->toDateString())->whereIn('user_id', [$ro->id, $rm->id, $bm->id, $dm->id])->delete();

    // 4. Run Logic
    $amount = 1000.00;
    echo "Applying incentive for RO with base amount: {$amount}\n";
    app(IncentiveService::class)->applyIncentive($ro, 'medicines', $amount);

    // 5. Check Results
    $results = Attendance::where('date', now()->toDateString())->whereIn('user_id', [$ro->id, $rm->id, $bm->id, $dm->id])->get();

    foreach ($results as $att) {
        $user = User::find($att->user_id);
        echo "User {$user->designation} ({$user->id}): Medicines Incentive = {$att->medicines_amount}\n";
    }

    if ($results->count() == 4) {
        echo "SUCCESS: All 4 users in hierarchy received incentives.\n";
    } else {
        echo "FAILURE: Expected 4 attendance records, got " . $results->count() . "\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "--- Verification Finished (Changes Rolled Back) ---\n";
}
