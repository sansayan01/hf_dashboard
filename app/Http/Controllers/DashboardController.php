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

        $downlineIds = $canViewDownline ? $user->getAllDownlineIds() : [];
        $allAccessibleIds = array_merge($downlineIds, [$user->id]);

        if ($user->isOfficeInCharge() && $user->upline_id) {
            $allAccessibleIds[] = $user->upline_id;
        }

        $stats = $this->getStats($user, $downlineIds, $canViewDownline);
        $reports = $canViewReports ? $this->getReports($allAccessibleIds) : [];
        $recentActivities = $this->getRecentActivities($allAccessibleIds);
        $earnings = $this->getEarnings($user);

        if (!$user->isOfficeInCharge()) {
            $user->load(['children.profile']);
        }

        $isViewAs = $currentUser->id !== $user->id;

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'isViewAs', 'canApprove', 'earnings'));
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

        return [
            'surveys' => $surveyStats ? $surveyStats->toArray() : [],
            'appointments' => $appStats ? $appStats->toArray() : []
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
                SUM(total_amount - incentive_amount) as monthly_total_no_base
            ")
            ->first();

        $todayEarnings = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->first();

        $isRO = $user->designation === 'ro';

        return [
            'monthly_ta' => $isRO ? ($earningsData->monthly_ta ?? 0) : 0,
            'monthly_incentives' => $earningsData->monthly_incentives ?? 0,
            'monthly_breakdown' => [
                'medicines' => $earningsData->monthly_medicines ?? 0,
                'pathology' => $earningsData->monthly_pathology ?? 0,
                'membership' => $earningsData->monthly_membership ?? 0,
                'ots' => $earningsData->monthly_ots ?? 0,
            ],
            'monthly_total' => $earningsData->monthly_total_no_base ?? 0,
            'today_total' => $todayEarnings ? ($todayEarnings->total_amount - $todayEarnings->incentive_amount) : 0,
            'today_breakdown' => $todayEarnings ? [
                'ta' => $isRO ? $todayEarnings->ta_amount : 0,
                'medicines' => $todayEarnings->medicines_amount,
                'pathology' => $todayEarnings->pathology_amount,
                'membership' => $todayEarnings->membership_amount,
                'ots' => $todayEarnings->ots_amount,
                'incentives' => $todayEarnings->medicines_amount + $todayEarnings->pathology_amount + $todayEarnings->membership_amount + $todayEarnings->ots_amount
            ] : ['ta' => 0, 'medicines' => 0, 'pathology' => 0, 'membership' => 0, 'ots' => 0, 'incentives' => 0]
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
