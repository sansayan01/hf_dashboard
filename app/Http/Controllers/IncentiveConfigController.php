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

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'designation' => 'required|in:super_admin,hs,dm,bm,rm,ro',
            'incentive_amount' => 'required|numeric|min:0',
            'medicines_amount' => 'required|numeric|min:0',
            'pathology_amount' => 'required|numeric|min:0',
            'membership_amount' => 'required|numeric|min:0',
            'ots_amount' => 'required|numeric|min:0',
            'ta_amount' => 'required|numeric|min:0',
        ]);

        // Set a retroactive date for global configs so they apply to past attendances too
        $validated['effective_from'] = '2024-01-01';

        // Use updateOrCreate to keep things simple - one config per designation
        IncentiveConfig::updateOrCreate(
            ['designation' => $validated['designation'], 'user_id' => null],
            $validated
        );

        return redirect()->back()->with('success', 'Incentive configuration updated successfully.');
    }

    public function destroy(IncentiveConfig $incentiveConfig)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $incentiveConfig->delete();

        return redirect()->back()->with('success', 'Configuration removed.');
    }

    public function syncAttendances()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
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
                    $attendance->ta_amount = $config->ta_amount;
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
