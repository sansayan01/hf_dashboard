<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Models\IncentiveConfig;
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
        $isMarkableRole = $user->isRO() || $user->isRM() || $user->isBM() || $user->isDM();

        // Field staff (RO, RM, BM, DM) need attendance tracking if on TAB mode
        if (!$isMarkableRole) {
            return response()->json(['message' => 'Attendance is only required for Field Staff (RO, RM, BM, DM).'], 400);
        }
        $effectiveUser = User::getEffectiveUser();

        // 1. Permission check: SuperAdmin or anyone who can access/view the user in their team
        if (!$effectiveUser->hasPermission('attendance.mark')) {
            return response()->json(['message' => 'Unauthorized. You do not have permission to mark attendance.'], 403);
        }
        
        if (!$effectiveUser->canAccess($user)) {
            return response()->json(['message' => 'Unauthorized. You do not have permission to modify this attendance.'], 403);
        }

        // 2. Lock logic: SuperAdmin can always edit. 
        $attendanceDate = Carbon::parse($request->date);
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $attendanceDate->format('Y-m-d'))
            ->first();

        // If it's locked and not SuperAdmin, check if the effective user has access.
        if ($attendance && $attendance->isLocked() && !$effectiveUser->isSuperAdmin() && !$effectiveUser->canAccess($user)) {
            return response()->json(['message' => 'Attendance is locked and cannot be modified.'], 403);
        }

        try {
            if (!$attendance) {
                $attendance = new Attendance();
                $attendance->user_id = $user->id;
                $attendance->date = $attendanceDate->startOfDay();
            }

            // 3. Mark logic
            $attendance->marked_by = $effectiveUser->id;
            $attendance->status = $request->status;

            if ($request->status === 'present') {
                /** @var IncentiveConfig|null $config */
                $config = $user->getCurrentIncentive($attendance->date);
                if ($config) {
                    $attendance->incentive_amount = $config->incentive_amount;

                    // For TAB users, set the fixed daily TA from config.
                    // For DAB users, ta_amount is managed by AppointmentController completions.
                    // We only set it to 0 if it's a NEW attendance record for a DAB user.
                    if (!$user->isDabMode()) {
                        $attendance->ta_amount = $config->ta_amount;
                    } else {
                        $attendance->ta_amount = $attendance->ta_amount ?? 0;
                    }
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

            $attendance->save();

            return response()->json([
                'message' => 'Attendance marked successfully',
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'System Error: ' . $e->getMessage() . ' at line ' . $e->getLine()
            ], 500);
        }
    }

    public function index()
    {
        $effectiveUser = User::getEffectiveUser();

        if (!$effectiveUser->hasPermission('attendance.mark')) {
            abort(403, 'Unauthorized access.');
        }

        // RMs and SuperAdmins only mark attendance for ROs (who are in TAB mode)
        // Note: TAB mode is defined as anything NOT 'dab'.
        if ($effectiveUser->isSuperAdmin()) {
            $ros = User::where('designation', 'ro')
                ->where('salary_mode', '!=', 'dab')
                ->active()
                ->get();
        } else {
            // Get all visible downline IDs and filter for ROs in TAB mode
            $visibleIds = $effectiveUser->getDataVisibilityIds();
            $ros = User::whereIn('id', $visibleIds)
                ->where('designation', 'ro')
                ->where('salary_mode', '!=', 'dab')
                ->active()
                ->get();

            if ($ros->isEmpty() && !$effectiveUser->canViewDownline()) {
                abort(403, 'Unauthorized. You do not have permission to access attendance marking.');
            }
        }

        return view('attendance.mark', compact('ros'));
    }

    public function roDashboard(Request $request)
    {
        $effectiveUser = User::getEffectiveUser();
        
        if (!$effectiveUser->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized access.');
        }
        $user = $effectiveUser;

        // If a user_id is provided, check if the effective user can view that user.
        // ROs are NEVER allowed to view another user's attendance — ignore the param entirely.
        if ($request->has('user_id') && $effectiveUser->designation !== 'ro') {
            $viewUser = User::find($request->user_id);
            if ($viewUser) {
                if ($effectiveUser->isSuperAdmin() || in_array($viewUser->id, $effectiveUser->getDataVisibilityIds())) {
                    $user = $viewUser;
                }
            }
        }

        // Allow roles that earn incentives or manage them to see dashboard
        $allowedDesignations = ['ro', 'rm', 'bm', 'dm', 'hs', 'office_in_charge'];
        if (!in_array($user->designation, $allowedDesignations) && !$user->isSuperAdmin()) {
            abort(403);
        }

        // DAB users don't have an attendance dashboard
        if ($user->salary_mode === 'dab' && $user->id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Attendance tracking is disabled for DAB mode.');
        }

        // Block viewing attendance calendars for DAB users even for admins/managers
        if ($user->salary_mode === 'dab') {
            abort(404, 'Attendance details not available for DAB users.');
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $targetDate = Carbon::createFromDate($year, $month, 1);

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // Auto-sync missing incentives if present
        foreach ($attendances as $a) {
            if ($a->status === 'present' && ($a->ta_amount == 0 && $a->incentive_amount == 0)) {
                /** @var IncentiveConfig|null $config */
                $config = $user->getCurrentIncentive($a->date);
                if ($config) {
                    $a->incentive_amount = $config->incentive_amount;
                    $a->ta_amount = $config->ta_amount;
                    $a->save();
                }
            }
        }

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'total_incentives' => $attendances->sum(function ($a) {
                return $a->incentive_amount + $a->medicines_amount + $a->pathology_amount + $a->membership_amount + $a->ots_amount;
            }),
            'ta' => $attendances->sum('ta_amount'),
            'total' => $attendances->sum('total_amount'),
        ];

        $allAttendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        // Get viewable users for search with filters applied
        $query = User::with('profile');
        if ($effectiveUser->isSuperAdmin()) {
            $query->where('designation', '!=', 'super_admin');
        } else {
            $visibilityIds = $effectiveUser->getDataVisibilityIds();
            if (!empty($visibilityIds)) {
                $query->whereIn('id', $visibilityIds);
            } else {
                $query->where('id', 0); // Hide everything if no visibility
            }
        }

        // Apply filters from request (Same as UserController)
        if ($request->filled('district')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('district', $request->district);
            });
        }
        if ($request->filled('block')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }
        if ($request->filled('gram_panchayat')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('gram_panchayat', $request->gram_panchayat);
            });
        }
        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        $viewableUsers = $query->get();

        // Calculate allowedFilters for designation dropdown
        $hierarchyLevels = [
            'super_admin' => 0,
            'office_in_charge' => 1,
            'hs' => 2,
            'dm' => 3,
            'bm' => 4,
            'rm' => 5,
            'ro' => 6
        ];
        $designationLabels = [
            'office_in_charge' => 'Office In-Charge',
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];
        $currentUserLevel = $hierarchyLevels[$effectiveUser->designation] ?? 99;
        $allowedFilters = [];
        foreach ($designationLabels as $key => $label) {
            if ($hierarchyLevels[$key] > $currentUserLevel) {
                $allowedFilters[$key] = $label;
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('attendance.partials.calendar_content', compact('user', 'summary', 'allAttendances', 'targetDate', 'viewableUsers', 'allowedFilters'))->render(),
                'title' => $targetDate->format('F Y'),
                'page_title' => $user->id === auth()->id() ? 'My Attendance' : ($user->profile->full_name ?? $user->employee_id) . "'s Attendance"
            ]);
        }

        return view('attendance.calendar', compact('user', 'summary', 'allAttendances', 'targetDate', 'viewableUsers', 'allowedFilters'));
    }

    public function show(User $user, Request $request)
    {
        $effectiveUser = User::getEffectiveUser();

        if (!$effectiveUser->hasPermission('attendance.view')) {
            abort(403, 'Unauthorized access.');
        }
        // Permission check
        if (!User::getEffectiveUser()->canAccess($user)) {
            abort(403);
        }

        // DAB users don't have an attendance dashboard
        if ($user->salary_mode === 'dab') {
            abort(404, 'Attendance details not available for DAB users.');
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $targetDate = Carbon::createFromDate($year, $month, 1);

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // Auto-sync missing incentives if present
        foreach ($attendances as $a) {
            if ($a->status === 'present' && ($a->ta_amount == 0 && $a->incentive_amount == 0)) {
                /** @var IncentiveConfig|null $config */
                $config = $user->getCurrentIncentive($a->date);
                if ($config) {
                    $a->incentive_amount = $config->incentive_amount;
                    $a->ta_amount = $config->ta_amount;
                    $a->save();
                }
            }
        }

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'total_incentives' => $attendances->sum(function ($a) {
                return $a->incentive_amount + $a->medicines_amount + $a->pathology_amount + $a->membership_amount + $a->ots_amount;
            }),
            'ta' => $attendances->sum('ta_amount'),
            'total' => $attendances->sum('total_amount'),
        ];

        $allAttendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        // Logic for viewable users search
        $viewableUsers = [];
        $effectiveUser = User::getEffectiveUser();
        if ($effectiveUser->isSuperAdmin()) {
            $viewableUsers = User::with('profile')->where('designation', '!=', 'super_admin')->get();
        } else {
            $visibilityIds = $effectiveUser->getDataVisibilityIds();
            if (!empty($visibilityIds)) {
                $viewableUsers = User::whereIn('id', $visibilityIds)->with('profile')->get();
            }
        }

        // Calculate allowedFilters for designation dropdown
        $hierarchyLevels = [
            'super_admin' => 0,
            'office_in_charge' => 1,
            'hs' => 2,
            'dm' => 3,
            'bm' => 4,
            'rm' => 5,
            'ro' => 6
        ];
        $designationLabels = [
            'office_in_charge' => 'Office In-Charge',
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];
        $currentUserLevel = $hierarchyLevels[$effectiveUser->designation] ?? 99;
        $allowedFilters = [];
        foreach ($designationLabels as $key => $label) {
            if ($hierarchyLevels[$key] > $currentUserLevel) {
                $allowedFilters[$key] = $label;
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('attendance.partials.calendar_content', compact('user', 'summary', 'allAttendances', 'targetDate', 'viewableUsers', 'allowedFilters'))->render(),
                'title' => $targetDate->format('F Y')
            ]);
        }

        return view('attendance.calendar', compact('user', 'summary', 'allAttendances', 'targetDate', 'viewableUsers', 'allowedFilters'));
    }

    public function report(Request $request)
    {
        $user = User::getEffectiveUser();

        if (!$user->hasPermission('attendance.view_report')) {
            abort(403, 'Unauthorized access.');
        }

        $query = Attendance::with(['user.profile', 'markedBy.profile']);

        if (!$user->isSuperAdmin()) {
            $query->whereIn('user_id', $user->getDataVisibilityIds());
        }

        $this->applyFilters($query, $request);

        $attendances = $query->orderBy('date', 'desc')->paginate(50);

        $designations = [
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
            'staff' => 'Pharmacist',
        ];

        if ($user->isSuperAdmin()) {
            $users = User::with('profile')->where('designation', '!=', 'super_admin')->get();
        } else {
            $users = User::whereIn('id', $user->getDataVisibilityIds())->with('profile')->get();
        }

        return view('attendance.report', compact('attendances', 'designations', 'users'));
    }

    public function exportReport(Request $request)
    {
        $user = User::getEffectiveUser();

        if (!$user->hasPermission('attendance.export')) {
            abort(403, 'Unauthorized access.');
        }

        $query = Attendance::with(['user.profile', 'markedBy.profile']);

        if (!$user->isSuperAdmin()) {
            $query->whereIn('user_id', $user->getDataVisibilityIds());
        }

        $this->applyFilters($query, $request);

        $attendances = $query->orderBy('date', 'desc')->get();

        $filename = "Attendance_Report_" . now()->format('Y-m-d_His') . ".csv";
        $header = ['Date', 'Name', 'Designation', 'Employee ID', 'Status', 'Incentive', 'TA', 'Medicines', 'Pathology', 'Membership', 'OTs', 'Total', 'Marked By', 'Timestamp'];

        $callback = function () use ($attendances, $header) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);

            foreach ($attendances as $row) {
                fputcsv($file, [
                    $row->date->format('Y-m-d'),
                    $row->user->profile->full_name ?? ($row->user->employee_id ?? 'Deleted User'),
                    $row->user ? ucfirst(str_replace('_', ' ', $row->user->designation)) : 'N/A',
                    $row->user->employee_id ?? 'N/A',
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

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('designation')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('designation', $request->designation);
            });
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }
    }
}
