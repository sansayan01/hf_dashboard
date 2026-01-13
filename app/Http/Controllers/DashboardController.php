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
        } else {
            $user = $currentUser;
        }

        // Optimization: Fetch IDs once
        $downlineIds = $user->getAllDownlineIds();
        $allAccessibleIds = array_merge($downlineIds, [$user->id]);

        // Optimized Stats
        $stats = [
            'total_downline' => count($downlineIds),
            'pending_approvals' => $user->getPendingApprovalsCount(),
            'direct_children' => $user->children()->count(),
            'active_downline' => User::whereIn('id', $downlineIds)->where('status', 'active')->count(),
        ];

        // Optimized Reports using conditional aggregation
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

        $reports = [
            'surveys' => $surveyStats->toArray(),
            'appointments' => $appStats->toArray()
        ];

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

        $pendingApprovals = $user->isSuperAdmin()
            ? User::pending()->with('profile')->latest()->get()
            : collect();

        $isViewAs = $currentUser->id !== $user->id;

        // Eager load only immediate children to speed up initial load
        $user->load(['children.profile']);

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'pendingApprovals', 'isViewAs'));
    }

    /**
     * Get hierarchy tree data
     */
    public function getHierarchyTree(Request $request)
    {
        $user = auth()->user();
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
            ->with('profile')
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
