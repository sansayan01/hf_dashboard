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

        // Pharmacist (Staff) Specific Dashboard Redirection
        if ($currentUser->designation === 'staff') {
            return redirect()->route('inventory.index');
        }

        $targetUserId = $request->get('as_user', $currentUser->id);

        if ($targetUserId != $currentUser->id) {
            $user = User::findOrFail($targetUserId);
            if (!$currentUser->canAccess($user)) {
                abort(403, 'Unauthorized to view this dashboard.');
            }
        } else {
            $user = $currentUser;
        }

        // Check Permissions
        $canViewDownline = $currentUser->canViewDownline();
        $canViewReports = $currentUser->isSuperAdmin() || \App\Models\RolePermission::check($currentUser->designation, 'can_view_reports');
        $canApprove = $currentUser->isSuperAdmin() || \App\Models\RolePermission::check($currentUser->designation, 'can_approve_users');

        // Optimization: Fetch IDs once
        $downlineIds = $canViewDownline ? $user->getAllDownlineIds() : [];
        $allAccessibleIds = array_merge($downlineIds, [$user->id]);

        // For OIC, also include Upline's own ID in accessible list so we can see their surveys/reports
        if ($user->isOfficeInCharge() && $user->upline_id) {
            $allAccessibleIds[] = $user->upline_id;
        }

        // Optimized Stats
        $stats = [
            'total_downline' => count($downlineIds),
            'pending_approvals' => $user->getPendingApprovalsCount(),
            'direct_children' => $canViewDownline ? $user->getDashboardChildrenCount() : 0,
            'active_downline' => count($downlineIds) > 0 ? User::whereIn('id', $downlineIds)->where('status', 'active')->count() : 0,
        ];

        // Optimized Reports using conditional aggregation
        $now = now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();
        $today = $now->copy()->startOfDay();

        $reports = [];
        if ($canViewReports) {
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

            $reports = [
                'surveys' => $surveyStats ? $surveyStats->toArray() : [],
                'appointments' => $appStats ? $appStats->toArray() : []
            ];
        }

        // Calculate the most recent 3 AM IST
        $startTime = now()->timezone('Asia/Kolkata');
        if ($startTime->hour < 3) {
            $startTime = $startTime->subDay()->setTime(3, 0, 0);
        } else {
            $startTime = $startTime->setTime(3, 0, 0);
        }

        // Limit to 50 results for faster render
        $recentActivities = ActivityLog::where(function ($q) use ($allAccessibleIds) {
            $q->whereIn('user_id', $allAccessibleIds)
                ->orWhereIn('performed_by', $allAccessibleIds);
        })
            ->where('created_at', '>', $startTime)
            ->with(['user.profile', 'performedBy.profile'])
            ->latest()
            ->limit(50)
            ->get();


        $isViewAs = $currentUser->id !== $user->id;

        // Eager load only immediate children to speed up initial load
        if (!$user->isOfficeInCharge()) {
            $user->load(['children.profile']);
        }

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'isViewAs', 'canApprove'));
    }

    /**
     * Get hierarchy tree data
     */
    public function getHierarchyTree(Request $request)
    {
        $user = auth()->user();

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
            $currentUser = auth()->user();

            // Permission Check
            if (!$currentUser->isSuperAdmin() && !\App\Models\RolePermission::check($currentUser->designation, 'can_view_downline')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if (!$currentUser->canAccess($user)) {
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
            ->where('designation', '!=', 'office_in_charge')
            ->with(['profile'])
            ->get()
            ->map(function ($child) {
                return $this->buildTree($child);
            });

        return [
            'id' => $user->id,
            'employee_id' => $user->employee_id,
            'name' => $user->profile->full_name ?? 'N/A',
            'designation' => $user->getDesignationLabel(),
            'status' => $user->status,
            'profile_picture' => $user->profile->profile_picture ?? null,
            'children' => $children,
        ];
    }
}
