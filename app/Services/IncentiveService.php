<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use App\Models\IncentiveConfig;
use Carbon\Carbon;

class IncentiveService
{
    /**
     * Apply incentive to a user based on a specific category.
     * Automatically marks attendance as present if not already done.
     *
     * @param User $user
     * @param string $category 'medicines', 'pathology', 'membership', 'ots'
     * @param float $baseAmount The transaction amount to calculate percentage from
     * @return void
     */
    public function applyIncentive(User $user, string $category, float $baseAmount = 0)
    {
        $today = Carbon::today();
        $config = $user->getCurrentIncentive();

        // Recursively apply to upline first (top-down or bottom-up? 
        // Bottom-up (RO first, then RM, then BM, then DM) is usually safer for logic.
        // Let's do the current user first.
        if ($config) {
            $attendance = Attendance::firstOrNew([
                'user_id' => $user->id,
                'date' => $today,
            ]);

            $isNew = !$attendance->exists;

            if ($isNew) {
                // First activity of the day: Mark present and snapshot base amounts
                $attendance->marked_by = auth()->id() ?: $user->id; // Use person who triggered the action or the user themselves
                $attendance->status = 'present';
                $attendance->incentive_amount = $config->incentive_amount;
                $attendance->ta_amount = $user->designation === 'ro' ? $config->ta_amount : 0;
                $attendance->medicines_amount = 0;
                $attendance->pathology_amount = 0;
                $attendance->membership_amount = 0;
                $attendance->ots_amount = 0;
            }

            // Increment the specific category
            $column = $category . '_amount'; // e.g., medicines_amount
            $configValue = $config->$column;

            if (in_array($category, ['medicines', 'pathology', 'ots'])) {
                // Calculated as percentage of baseAmount
                $increment = ($baseAmount * $configValue) / 100;
            } else {
                // Fixed amount (e.g. Membership)
                $increment = floatval($configValue);
            }

            $attendance->$column += $increment;

            // Recalculate total
            $attendance->total_amount = $attendance->incentive_amount +
                $attendance->ta_amount +
                $attendance->medicines_amount +
                $attendance->pathology_amount +
                $attendance->membership_amount +
                $attendance->ots_amount;

            $attendance->save();
        }

        // Recursively trigger for parent (Upline)
        // Hierarchy: RO -> RM -> BM -> DM -> HS -> Super Admin
        // Typically incentives stop at DM or HS as per requirements.
        $parent = $user->parent;
        if ($parent && !in_array($parent->designation, ['super_admin'])) {
            $this->applyIncentive($parent, $category, $baseAmount);
        }
    }
}
