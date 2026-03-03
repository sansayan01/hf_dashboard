<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $targetUserId = $request->get('as_user', $currentUser->id);

        if ($targetUserId != $currentUser->id) {
            $user = User::findOrFail($targetUserId);
            if (!$currentUser->canAccess($user)) {
                abort(403, 'Unauthorized to view this dashboard.');
            }
            session(['view_as_user_id' => $targetUserId]);
        } else {
            $user = $currentUser;
            session()->forget('view_as_user_id');
        }

        if ($user->designation === 'staff') {
            return redirect()->route('inventory.index');
        }

        $canViewDownline = $user->canViewDownline();
        $canViewReports = $user->isSuperAdmin() || \App\Models\RolePermission::check($user->designation, 'can_view_reports');
        $canApprove = $user->isSuperAdmin() || \App\Models\RolePermission::check($user->designation, 'can_approve_users');

        $downlineIds = $canViewDownline ? $user->getTeamDownlineIds() : [];
        $allAccessibleIds = array_merge($downlineIds, [$user->id]);

        if ($user->isOfficeInCharge() && $user->upline_id) {
            $allAccessibleIds[] = $user->upline_id;
        }

        $stats = $this->getStats($user, $downlineIds, $canViewDownline);
        $reports = $canViewReports ? $this->getReports($allAccessibleIds) : [];
        $recentActivities = $this->getRecentActivities($allAccessibleIds);
        $earnings = $this->getEarnings($user);

        $pendingUsers = [];
        if ($canApprove) {
            $pendingUsers = User::with('profile')->pending();
            if (!$user->isSuperAdmin()) {
                $pendingUsers->whereIn('id', $downlineIds);
            }
            $pendingUsers = $pendingUsers->latest()->limit(5)->get();
        }

        if (!$user->isOfficeInCharge()) {
            $user->load(['children.profile']);
        }

        $isViewAs = $currentUser->id !== $user->id;

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'isViewAs', 'canApprove', 'canViewReports', 'canViewDownline', 'earnings', 'pendingUsers'));
    }

    private function getStats(User $user, array $downlineIds, bool $canViewDownline): array
    {
        return [
            'total_downline' => count($downlineIds),
            'pending_approvals' => $user->getPendingApprovalsCount(),
            'direct_children' => $canViewDownline ? $user->getDashboardChildrenCount() : 0,
            'active_downline' => count($downlineIds) > 0 ? User::whereIn('id', $downlineIds)->where('status', 'active')->count() : 0,
        ];
    }

    private function getReports(array $allAccessibleIds): array
    {
        $now = now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();
        $today = $now->copy()->startOfDay();

        $surveyStats = \App\Models\Survey::whereIn('created_by', $allAccessibleIds)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as daily,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as weekly,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as monthly
            ", [$today, $startOfWeek, $startOfMonth])
            ->first();

        $appStats = \App\Models\Appointment::whereHas('survey', function ($q) use ($allAccessibleIds) {
            $q->whereIn('created_by', $allAccessibleIds);
        })
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as daily,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as weekly,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as monthly
            ", [$today, $startOfWeek, $startOfMonth])
            ->first();

        $default = ['total' => 0, 'daily' => 0, 'weekly' => 0, 'monthly' => 0];

        return [
            'surveys' => $surveyStats ? array_merge($default, $surveyStats->toArray()) : $default,
            'appointments' => $appStats ? array_merge($default, $appStats->toArray()) : $default
        ];
    }

    private function getRecentActivities(array $allAccessibleIds)
    {
        $startTime = now()->timezone('Asia/Kolkata');
        if ($startTime->hour < 3) {
            $startTime = $startTime->subDay()->setTime(3, 0, 0);
        } else {
            $startTime = $startTime->setTime(3, 0, 0);
        }

        return ActivityLog::where(function ($q) use ($allAccessibleIds) {
            $q->whereIn('user_id', $allAccessibleIds)
                ->orWhereIn('performed_by', $allAccessibleIds);
        })
            ->where('created_at', '>', $startTime)
            ->with(['user.profile', 'performedBy.profile'])
            ->latest()
            ->limit(50)
            ->get();
    }

    private function getEarnings(User $user): ?array
    {
        if (!in_array($user->designation, ['ro', 'rm', 'bm', 'dm'])) {
            return null;
        }

        $salaryMode = $user->salary_mode ?? 'tab';



        $monthStart = now()->startOfMonth();
        $earningsData = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', '>=', $monthStart)
            ->selectRaw("
                SUM(ta_amount) as monthly_ta,
                SUM(medicines_amount) as monthly_medicines,
                SUM(pathology_amount) as monthly_pathology,
                SUM(membership_amount) as monthly_membership,
                SUM(ots_amount) as monthly_ots,
                SUM(medicines_amount + pathology_amount + membership_amount + ots_amount) as monthly_incentives,
                SUM(total_amount) as monthly_total
            ")
            ->first();

        $todayEarnings = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->first();

        $salaryMode = $user->salary_mode ?? 'tab';

        // Requirement: When TAB is the salary model, show them only the salary according to their attendance.
        // When DAB is the salary model, show them only the salary according to their successful doctor appointments.
        // Even if switched mid-month, we show only the current mode's basis.

        $monthlyBase = 0;
        $dabData = null;

        if ($salaryMode === 'dab') {
            // DAB Mode: Recompute purely from successful appointments this month
            $dabData = $user->getMonthlyDabEarnings();
            $monthlyBase = $dabData['earnings'];
        } else {
            // TAB Mode: Recompute purely from days marked 'present' this month
            // (We check user designation RO because non-ROs don't get TA)
            $presentDaysCount = \App\Models\Attendance::where('user_id', $user->id)
                ->where('date', '>=', $monthStart)
                ->where('status', 'present')
                ->count();

            $config = $user->getCurrentIncentive();
            $taRate = ($config && $user->designation === 'ro') ? $config->ta_amount : 0;
            $monthlyBase = $presentDaysCount * $taRate;
        }

        // Monthly Total = Recomputed Base (TA or DA) + All other incentives recorded in attendance (medicines, etc.)
        $monthlyTotal = $monthlyBase + ($earningsData->monthly_incentives ?? 0);

        // Calculate Today's "Base" (Daily TA or Today's DA)
        $todayBase = 0;
        if ($salaryMode === 'dab') {
            $config = $user->getCurrentIncentive();
            $daAmount = ($config && $config->da_amount > 0) ? $config->da_amount : 20;

            $todayBase = \App\Models\Appointment::where('created_by', $user->id)
                ->where('status', 'successful')
                ->whereDate('updated_at', now()->toDateString())
                ->count() * $daAmount;
        } else {
            $isTodayPresent = \App\Models\Attendance::where('user_id', $user->id)
                ->where('date', now()->toDateString())
                ->where('status', 'present')
                ->exists();

            $config = $user->getCurrentIncentive();
            $taRate = ($config && $user->designation === 'ro') ? $config->ta_amount : 0;
            $todayBase = $isTodayPresent ? $taRate : 0;
        }

        $todayIncentives = $todayEarnings ? ($todayEarnings->medicines_amount + $todayEarnings->pathology_amount + $todayEarnings->membership_amount + $todayEarnings->ots_amount) : 0;

        $todayTotal = $todayBase + $todayIncentives;

        return [
            'salary_mode' => $salaryMode,
            'monthly_ta' => $monthlyBase,
            'monthly_incentives' => $earningsData->monthly_incentives ?? 0,
            'monthly_breakdown' => [
                'ta' => $monthlyBase,
                'medicines' => $earningsData->monthly_medicines ?? 0,
                'pathology' => $earningsData->monthly_pathology ?? 0,
                'membership' => $earningsData->monthly_membership ?? 0,
                'ots' => $earningsData->monthly_ots ?? 0,
            ],
            'monthly_total' => $monthlyTotal,
            'today_total' => $todayTotal,
            'today_breakdown' => [
                'ta' => $todayBase,
                'medicines' => $todayEarnings->medicines_amount ?? 0,
                'pathology' => $todayEarnings->pathology_amount ?? 0,
                'membership' => $todayEarnings->membership_amount ?? 0,
                'ots' => $todayEarnings->ots_amount ?? 0,
                'incentives' => $todayIncentives
            ],
            'dab' => $dabData,
        ];
    }

    /**
     * Get hierarchy tree data
     */
    public function getHierarchyTree(Request $request)
    {
        $user = User::getEffectiveUser();

        // Permission Check
        if (!$user->isSuperAdmin() && !\App\Models\RolePermission::check($user->designation, 'can_view_downline')) {
            abort(403);
        }

        $targetUserId = $request->get('user_id', $user->id);
        $targetUser = User::findOrFail($targetUserId);

        if (!$user->canAccess($targetUser)) {
            abort(403);
        }

        return response()->json($this->buildTree($targetUser));
    }

    /**
     * Get hierarchy tree children partial
     */
    public function getTreeChildren($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $effectiveUser = User::getEffectiveUser();
            $currentUser = auth()->user(); // Still needed for raw permission check if necessary, but we should use effectiveUser for access

            // Permission Check
            if (!$effectiveUser->isSuperAdmin() && !\App\Models\RolePermission::check($effectiveUser->designation, 'can_view_downline')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if (!$effectiveUser->canAccess($user)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $children = $user->getDirectChildren();

            // Eager load for performance and to avoid null issues in view
            if ($children instanceof \Illuminate\Database\Eloquent\Collection) {
                $children->load('profile');
            }

            $html = '';
            foreach ($children as $child) {
                $html .= view('dashboard.partials.tree_item', ['item' => $child])->render();
            }

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error("Hierarchy Tree Error: " . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Build hierarchy tree recursively
     */
    private function buildTree(User $user)
    {
        $children = $user->children()
            ->whereNotIn('designation', ['office_in_charge', 'camp_organizer', 'staff'])
            ->with(['profile'])
            ->get()
            ->map(function ($child) {
                return $this->buildTree($child);
            });

        return [
            'id' => $user->id,
            'employee_id' => $user->employee_id,
            'name' => $user->profile?->full_name ?? 'N/A',
            'designation' => $user->getDesignationLabel(),
            'status' => $user->status,
            'profile_picture' => $user->profile?->profile_picture ?? null,
            'children' => $children,
        ];
    }

    /**
     * Clear the "View As" context and return to the current user's dashboard.
     */
    public function clearContext()
    {
        session()->forget('view_as_user_id');
        return redirect()->route('dashboard')->with('success', 'Returned to your own dashboard.');
    }
}
