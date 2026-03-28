<?php

namespace App\Http\Controllers;

use App\Models\IncentiveConfig;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;

class IncentiveConfigController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Fetch only the latest config for each designation
        $globalConfig = IncentiveConfig::whereNull('user_id')
            ->orderBy('designation')
            ->orderBy('effective_from', 'desc')
            ->get();

        return view('admin.incentive_configs.index', compact('globalConfig'));
    }

    /**
     * Store TA-based incentive configuration.
     * TA = Travel Allowance (Daily Rupees) - given when RO marks attendance (present).
     */
    public function storeTa(Request $request)
    {
        if (!auth()->user()->hasPermission('admin.manage_incentives')) {
             abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'designation' => 'required|in:dm,bm,rm,ro',
            'ta_amount' => 'required|numeric|min:0',
            'medicines_amount' => 'required|numeric|min:0',
            'pathology_amount' => 'required|numeric|min:0',
            'membership_amount' => 'required|numeric|min:0',
            'ots_amount' => 'required|numeric|min:0',
        ]);

        // Enforcement: TA based dashboard should be only visible for the RO. TA is not for the RM, BM, DM.
        if ($validated['designation'] !== 'ro') {
            $validated['ta_amount'] = 0;
        }

        $validated['incentive_amount'] = 0;
        $validated['effective_from'] = '2024-01-01';

        // Preserve DA amount if config already exists
        $existing = IncentiveConfig::where('designation', $validated['designation'])
            ->whereNull('user_id')
            ->first();

        if ($existing) {
            $validated['da_amount'] = $existing->da_amount ?? 0;
        }

        IncentiveConfig::updateOrCreate(
            ['designation' => $validated['designation'], 'user_id' => null],
            $validated
        );

        return redirect()->back()->with('success', 'TA-based incentive configuration updated successfully.');
    }

    /**
     * Store DA-based incentive configuration.
     * DA = Doctor Appointment - given when appointment is completed successfully.
     */
    public function storeDa(Request $request)
    {
        if (!auth()->user()->hasPermission('admin.manage_incentives')) {
             abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'designation' => 'required|in:dm,bm,rm,ro',
            'da_amount' => 'required|numeric|min:0',
            'medicines_amount' => 'required|numeric|min:0',
            'pathology_amount' => 'required|numeric|min:0',
            'membership_amount' => 'required|numeric|min:0',
            'ots_amount' => 'required|numeric|min:0',
        ]);

        // Preserve existing TA config fields
        $existing = IncentiveConfig::where('designation', $validated['designation'])
            ->whereNull('user_id')
            ->first();

        $data = [
            'designation' => $validated['designation'],
            'da_amount' => $validated['da_amount'],
            'medicines_amount' => $validated['medicines_amount'],
            'pathology_amount' => $validated['pathology_amount'],
            'membership_amount' => $validated['membership_amount'],
            'ots_amount' => $validated['ots_amount'],
            'effective_from' => '2024-01-01',
        ];

        if ($existing) {
            $data['ta_amount'] = $existing->ta_amount ?? 0;
            $data['incentive_amount'] = $existing->incentive_amount ?? 0;
        }

        IncentiveConfig::updateOrCreate(
            ['designation' => $validated['designation'], 'user_id' => null],
            $data
        );

        return redirect()->back()->with('success', 'DA-based incentive configuration updated successfully.');
    }

    public function destroy(IncentiveConfig $incentiveConfig)
    {
        if (!auth()->user()->hasPermission('admin.manage_incentives')) {
             abort(403, 'Unauthorized access.');
        }

        $incentiveConfig->delete();

        return redirect()->back()->with('success', 'Configuration removed.');
    }

    public function syncAttendances()
    {
        if (!auth()->user()->hasPermission('admin.manage_incentives')) {
             abort(403, 'Unauthorized access.');
        }

        try {
            $attendances = Attendance::where('status', 'present')->get();
            $count = 0;

            foreach ($attendances as $attendance) {
                /** @var Attendance $attendance */
                $user = $attendance->user;
                if (!$user)
                    continue;

                $config = $user->getCurrentIncentive($attendance->date);
                if ($config) {
                    $attendance->incentive_amount = $config->incentive_amount;

                    if ($user->salary_mode === 'dab') {
                        // For DAB mode, recalculate DA earnings for that day
                        $daAmount = ($config->da_amount > 0) ? $config->da_amount : 20;
                        $count = \App\Models\Appointment::where('created_by', $user->id)
                            ->where('status', 'successful')
                            ->whereDate('updated_at', $attendance->date->toDateString())
                            ->count();
                        $attendance->ta_amount = $count * $daAmount;
                    } else {
                        // For TAB mode, use configured TA (enforced to 0 for non-RO in storeTa)
                        $attendance->ta_amount = $config->ta_amount;
                    }

                    $attendance->save(); // Triggers total_amount calculation boot hook
                    $count++;
                }
            }

            return redirect()->back()->with('success', "Successfully synchronized $count attendance records with current configurations.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error during synchronization: ' . $e->getMessage());
        }
    }
}
