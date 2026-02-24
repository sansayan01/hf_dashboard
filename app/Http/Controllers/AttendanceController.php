<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent',
            'date' => 'required|date',
        ]);

        $user = User::findOrFail($request->user_id);
        $effectiveUser = User::getEffectiveUser();

        // 1. Permission check: Only SuperAdmin or the user's RM (parent) can mark attendance
        if (!$effectiveUser->isSuperAdmin() && $effectiveUser->id !== $user->parent_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 2. Lock logic: Attendance can be edited only on the same day.
        $attendanceDate = Carbon::parse($request->date);
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $attendanceDate->format('Y-m-d'))
            ->first();

        if ($attendance && $attendance->isLocked() && !$effectiveUser->isSuperAdmin()) {
            return response()->json(['message' => 'Attendance is locked and cannot be modified.'], 403);
        }

        if (!$attendance) {
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->date = $attendanceDate->startOfDay();
        }

        // 3. Mark logic
        $attendance->marked_by = $effectiveUser->id;
        $attendance->status = $request->status;

        if ($request->status === 'present') {
            $config = $user->getCurrentIncentive($attendance->date);
            if ($config) {
                $attendance->incentive_amount = $config->incentive_amount;
                $attendance->ta_amount = $user->designation === 'ro' ? $config->ta_amount : 0;
            } else {
                $attendance->incentive_amount = $attendance->incentive_amount ?? 0;
                $attendance->ta_amount = $attendance->ta_amount ?? 0;
            }

            // Explicitly preserve or initialize activity amounts
            $attendance->medicines_amount = $attendance->medicines_amount ?? 0;
            $attendance->pathology_amount = $attendance->pathology_amount ?? 0;
            $attendance->membership_amount = $attendance->membership_amount ?? 0;
            $attendance->ots_amount = $attendance->ots_amount ?? 0;
        } else {
            // Reset amounts if marking as absent
            $attendance->incentive_amount = 0;
            $attendance->ta_amount = 0;
            $attendance->medicines_amount = 0;
            $attendance->pathology_amount = 0;
            $attendance->membership_amount = 0;
            $attendance->ots_amount = 0;
        }

        // Recalculate total
        $attendance->total_amount = $attendance->incentive_amount +
            $attendance->ta_amount +
            $attendance->medicines_amount +
            $attendance->pathology_amount +
            $attendance->membership_amount +
            $attendance->ots_amount;

        $attendance->save();

        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance
        ]);
    }

    public function index()
    {
        $effectiveUser = User::getEffectiveUser();

        // RM sees their ROs
        if ($effectiveUser->isRM()) {
            $ros = $effectiveUser->children()->where('designation', 'ro')->get();
        } elseif ($effectiveUser->isSuperAdmin()) {
            $ros = User::where('designation', 'ro')->get();
        } else {
            abort(403);
        }

        return view('attendance.mark', compact('ros'));
    }

    public function roDashboard(Request $request)
    {
        $user = User::getEffectiveUser();
        // Allow roles that earn incentives to see their dashboard
        if (!in_array($user->designation, ['ro', 'rm', 'bm', 'dm']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $targetDate = Carbon::createFromDate($year, $month, 1);

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            // Align "Incentives" with main dashboard (Sum of activity pieces)
            'incentive' => $attendances->sum(function ($a) {
                return $a->medicines_amount + $a->pathology_amount + $a->membership_amount + $a->ots_amount;
            }),
            'ta' => $attendances->sum('ta_amount'),
            // Total includes TA + Activity (Matching main dashboard total)
            'total' => $attendances->sum('total_amount') - $attendances->sum('incentive_amount'),
        ];

        $allAttendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.calendar', compact('user', 'summary', 'allAttendances', 'targetDate'));
    }

    public function show(User $user, Request $request)
    {
        // Permission check
        if (!User::getEffectiveUser()->canAccess($user)) {
            abort(403);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $targetDate = Carbon::createFromDate($year, $month, 1);

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'incentive' => $attendances->sum(function ($a) {
                return $a->medicines_amount + $a->pathology_amount + $a->membership_amount + $a->ots_amount;
            }),
            'ta' => $attendances->sum('ta_amount'),
            'total' => $attendances->sum('total_amount') - $attendances->sum('incentive_amount'),
        ];

        $allAttendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.calendar', compact('user', 'summary', 'allAttendances', 'targetDate'));
    }

    public function exportReport(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
        ]);

        $attendances = Attendance::with(['user', 'markedBy'])
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->get();

        $filename = "Attendance_Report_{$request->month}_{$request->year}.csv";
        $header = ['Date', 'RO Name', 'Employee ID', 'Status', 'Incentive', 'TA', 'Medicines', 'Pathology', 'Membership', 'OTs', 'Total', 'Marked By', 'Timestamp'];

        $callback = function () use ($attendances, $header) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);

            foreach ($attendances as $row) {
                fputcsv($file, [
                    $row->date->format('Y-m-d'),
                    $row->user->profile->full_name ?? 'N/A',
                    $row->user->employee_id,
                    ucfirst($row->status),
                    $row->incentive_amount,
                    $row->ta_amount,
                    $row->medicines_amount,
                    $row->pathology_amount,
                    $row->membership_amount,
                    $row->ots_amount,
                    $row->total_amount,
                    $row->markedBy->profile->full_name ?? 'System',
                    $row->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}
