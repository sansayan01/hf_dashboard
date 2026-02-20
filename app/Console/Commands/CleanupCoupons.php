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
    protected $description = 'Permanently delete coupon codes that were redeemed or expired more than 24 hours ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subHours(24);

        // 1. Fetch coupons that are marked as used and were used at or before the cutoff date
        $redeemedCoupons = CouponCode::where('is_used', true)
            ->where(function ($query) use ($cutoffDate) {
                $query->where('used_at', '<=', $cutoffDate)
                    ->orWhere(function ($q) use ($cutoffDate) {
                        // Fallback to updated_at if used_at is somehow missing but is_used is true
                        $q->whereNull('used_at')->where('updated_at', '<=', $cutoffDate);
                    });
            });

        // 2. Fetch coupons that are expired and are beyond the 24-hour grace period
        $expiredCoupons = CouponCode::where('is_used', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $cutoffDate);

        // Count for logging
        $redeemedCount = $redeemedCoupons->count();
        $expiredCount = $expiredCoupons->count();
        $totalCount = $redeemedCount + $expiredCount;

        // Perform deletion
        $redeemedCoupons->delete();
        $expiredCoupons->delete();

        if ($totalCount > 0) {
            $this->info("Successfully cleaned up {$totalCount} coupons ({$redeemedCount} redeemed, {$expiredCount} expired).");
        } else {
            $this->info("No coupons required cleanup.");
        }
    }
}
