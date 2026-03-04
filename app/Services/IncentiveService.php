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
        $config = $user->getCurrentIncentive($today);

        if ($config) {
            $attendance = Attendance::firstOrNew([
                'user_id' => $user->id,
                'date' => $today,
            ]);

            if (!$attendance->exists) {
                $attendance->marked_by = auth()->id() ?: $user->id;
                $attendance->status = 'present';
                // Only RO gets basic incentive (Basic is removed from UI/Total but kept for Record if needed)
                $attendance->incentive_amount = $user->isRO() ? ($config->incentive_amount ?? 0) : 0;
                // Only RO gets TA in TAB mode. RM/BM/DM get 0 TA initial.
                $attendance->ta_amount = ($user->isRO() && !$user->isDabMode()) ? ($config->ta_amount ?? 0) : 0;
                $attendance->medicines_amount = 0;
                $attendance->pathology_amount = 0;
                $attendance->membership_amount = 0;
                $attendance->ots_amount = 0;
            }

            $column = $category . '_amount';
            $configValue = $config->$column;

            if (in_array($category, ['medicines', 'pathology', 'ots'])) {
                $increment = ($baseAmount * $configValue) / 100;
            } else {
                $increment = floatval($configValue);
            }

            $attendance->$column = ($attendance->$column ?? 0) + $increment;
            $attendance->save();
        }

        $parent = $user->parent;
        if ($parent && !in_array($parent->designation, ['super_admin'])) {
            $this->applyIncentive($parent, $category, $baseAmount);
        }
    }

    /**
     * Specifically handle appointment incentives (DA) across hierarchy.
     */
    public function applyAppointmentIncentive(User $user, $date = null)
    {
        $date = $date ? Carbon::parse($date)->startOfDay() : Carbon::today();
        $config = $user->getCurrentIncentive($date);

        if ($config) {
            $attendance = Attendance::firstOrNew([
                'user_id' => $user->id,
                'date' => $date,
            ]);

            if (!$attendance->exists) {
                $attendance->marked_by = auth()->id() ?: $user->id;
                $attendance->status = 'present';
                // Only RO gets basic incentive record
                $attendance->incentive_amount = $user->isRO() ? ($config->incentive_amount ?? 0) : 0;
                // Only RO gets TA in TAB mode. RM/BM/DM get 0 TA initial.
                $attendance->ta_amount = ($user->isRO() && !$user->isDabMode()) ? ($config->ta_amount ?? 0) : 0;
                $attendance->medicines_amount = 0;
                $attendance->pathology_amount = 0;
                $attendance->membership_amount = 0;
                $attendance->ots_amount = 0;
            }

            // DA earnings go into ta_amount field
            // Super Admin sets da_amount in IncentiveConfig
            $daAmount = ($config && $config->da_amount > 0) ? $config->da_amount : 0;

            $attendance->ta_amount = ($attendance->ta_amount ?? 0) + $daAmount;
            $attendance->save();
        }

        $parent = $user->parent;
        if ($parent && !in_array($parent->designation, ['super_admin'])) {
            $this->applyAppointmentIncentive($parent, $date);
        }
    }
}
