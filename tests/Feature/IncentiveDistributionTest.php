<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\IncentiveConfig;
use App\Models\InventoryWarehouse;
use App\Models\Medicine;
use App\Models\MedicineDistribution;
use App\Models\PathologyTest;
use App\Models\Survey;
use App\Models\User;
use App\Services\IncentiveService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class IncentiveDistributionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we have some default configs or clear them
        IncentiveConfig::truncate();
    }

    public function test_recursive_incentive_distribution_for_medicines()
    {
        // 1. Create Chain: DM -> BM -> RM -> RO
        $dm = User::factory()->create(['designation' => 'dm', 'parent_id' => 1]); // Parent 1 is placeholder for HS or similar
        $bm = User::factory()->create(['designation' => 'bm', 'parent_id' => $dm->id]);
        $rm = User::factory()->create(['designation' => 'rm', 'parent_id' => $bm->id]);
        $ro = User::factory()->create(['designation' => 'ro', 'parent_id' => $rm->id]);

        // 2. Configure Incentives (Percentages)
        IncentiveConfig::create(['designation' => 'ro', 'medicines_amount' => 10, 'pathology_amount' => 10, 'membership_amount' => 10, 'ots_amount' => 10, 'effective_from' => now()->subDay()]);
        IncentiveConfig::create(['designation' => 'rm', 'medicines_amount' => 5, 'pathology_amount' => 5, 'membership_amount' => 5, 'ots_amount' => 5, 'effective_from' => now()->subDay()]);
        IncentiveConfig::create(['designation' => 'bm', 'medicines_amount' => 2, 'pathology_amount' => 2, 'membership_amount' => 2, 'ots_amount' => 2, 'effective_from' => now()->subDay()]);
        IncentiveConfig::create(['designation' => 'dm', 'medicines_amount' => 1, 'pathology_amount' => 1, 'membership_amount' => 1, 'ots_amount' => 1, 'effective_from' => now()->subDay()]);

        // 3. Create Patient registered by RO
        $patient = Survey::create([
            'full_name' => 'John Doe',
            'phone_number' => '1234567890',
            'address' => 'Test Address',
            'age' => 30,
            'gender' => 'male',
            'pin' => '123456',
            'created_by' => $ro->id
        ]);

        // 4. Trigger Medicine Incentive
        $amount = 1000.00;
        app(IncentiveService::class)->applyIncentive($ro, 'medicines', $amount);

        // 5. Assert Attendances & Amounts
        // RO (10% of 1000 = 100)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $ro->id,
            'medicines_amount' => 100.00
        ]);

        // RM (5% of 1000 = 50)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $rm->id,
            'medicines_amount' => 50.00
        ]);

        // BM (2% of 1000 = 20)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $bm->id,
            'medicines_amount' => 20.00
        ]);

        // DM (1% of 1000 = 10)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $dm->id,
            'medicines_amount' => 10.00
        ]);
    }

    public function test_recursive_incentive_distribution_for_pathology()
    {
        // 1. Create Chain: DM -> BM -> RM -> RO
        $dm = User::factory()->create(['designation' => 'dm', 'parent_id' => 1]);
        $bm = User::factory()->create(['designation' => 'bm', 'parent_id' => $dm->id]);
        $rm = User::factory()->create(['designation' => 'rm', 'parent_id' => $bm->id]);
        $ro = User::factory()->create(['designation' => 'ro', 'parent_id' => $rm->id]);

        // 2. Configure Incentives
        IncentiveConfig::create(['designation' => 'ro', 'pathology_amount' => 10, 'medicines_amount' => 0, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);
        IncentiveConfig::create(['designation' => 'rm', 'pathology_amount' => 5, 'medicines_amount' => 0, 'membership_amount' => 0, 'ots_amount' => 0, 'effective_from' => now()->subDay()]);

        // 3. Trigger Pathology Incentive
        $amount = 2000.00;
        app(IncentiveService::class)->applyIncentive($ro, 'pathology', $amount);

        // 4. Assert Attendances
        // RO (10% of 2000 = 200)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $ro->id,
            'pathology_amount' => 200.00
        ]);

        // RM (5% of 2000 = 100)
        $this->assertDatabaseHas('attendances', [
            'user_id' => $rm->id,
            'pathology_amount' => 100.00
        ]);
    }
}
