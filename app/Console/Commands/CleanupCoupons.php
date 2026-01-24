<?php

namespace App\Console\Commands;

use App\Models\CouponCode;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hf:cleanup-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete coupon codes that were used more than 7 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(7);

        // Fetch coupons that are marked as used and were used at or before the cutoff date
        $couponsToDelete = CouponCode::where('is_used', true)
            ->where(function ($query) use ($cutoffDate) {
                $query->where('used_at', '<=', $cutoffDate)
                      ->orWhere(function ($q) use ($cutoffDate) {
                          // Fallback to updated_at if used_at is somehow missing but is_used is true
                          $q->whereNull('used_at')->where('updated_at', '<=', $cutoffDate);
                      });
            })
            ->get();

        $count = $couponsToDelete->count();

        foreach ($couponsToDelete as $coupon) {
            $coupon->delete();
        }

        if ($count > 0) {
            $this->info("Successfully cleaned up {$count} used coupons.");
        } else {
            $this->info("No coupons required cleanup.");
        }
    }
}
