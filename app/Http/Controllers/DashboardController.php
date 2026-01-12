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
            // Permission check: Must be in downline
            if (!$currentUser->canAccess($user)) {
                abort(403, 'Unauthorized to view this dashboard.');
            }
        } else {
            $user = $currentUser;
        }

        $user->load(['children.profile', 'children.children.profile', 'children.children.children.profile']);


        // Get downline IDs for filtering (scoped to the viewed user)
        $downlineIds = $user->getAllDownline()->pluck('id');
        // Include self to see own data + downline data (Cumulative View)
        // This ensures Uplines see their entire team's performance, but Downlines only see themselves (if they have no team).
        $allAccessibleIds = $downlineIds->push($user->id);

        // These queries aggregate data from the user and their entire downline tree.
        // Report Widgets Logic
        $stats = [
            'total_downline' => $user->getDownlineCount(),
            'pending_approvals' => $user->getPendingApprovalsCount(),
            'direct_children' => $user->children()->count(),
            'active_downline' => $user->getAllDownline()->where('status', 'active')->count(),
        ];

        // Report Widgets Logic
        $reports = [
            'surveys' => [
                'daily' => \App\Models\Survey::whereIn('created_by', $allAccessibleIds)->whereDate('created_at', now())->count(),
                'weekly' => \App\Models\Survey::whereIn('created_by', $allAccessibleIds)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'monthly' => \App\Models\Survey::whereIn('created_by', $allAccessibleIds)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                'total' => \App\Models\Survey::whereIn('created_by', $allAccessibleIds)->count(),
            ],
            'appointments' => [
                'daily' => \App\Models\Appointment::whereHas('survey', function ($q) use ($allAccessibleIds) {
                    $q->whereIn('created_by', $allAccessibleIds);
                })->whereDate('created_at', now())->count(),
                'weekly' => \App\Models\Appointment::whereHas('survey', function ($q) use ($allAccessibleIds) {
                    $q->whereIn('created_by', $allAccessibleIds);
                })->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'monthly' => \App\Models\Appointment::whereHas('survey', function ($q) use ($allAccessibleIds) {
                    $q->whereIn('created_by', $allAccessibleIds);
                })->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                'total' => \App\Models\Appointment::whereHas('survey', function ($q) use ($allAccessibleIds) {
                    $q->whereIn('created_by', $allAccessibleIds);
                })->count(),
            ]
        ];

        // Calculate the most recent 3 AM IST
        $now = now()->timezone('Asia/Kolkata');
        if ($now->hour < 3) {
            $startTime = $now->copy()->subDay()->setTime(3, 0, 0);
        } else {
            $startTime = $now->copy()->setTime(3, 0, 0);
        }

        // Get recent activities (performed by or performed on downline) since 3 AM IST
        $recentActivities = ActivityLog::where(function ($q) use ($allAccessibleIds) {
            $q->whereIn('user_id', $allAccessibleIds)
                ->orWhereIn('performed_by', $allAccessibleIds);
        })
            ->where('created_at', '>', $startTime)
            ->with(['user.profile', 'performedBy.profile'])
            ->latest()
            ->get();

        // Get pending approvals (Super Admin only - or theoretically if a user can approve others)
        // For "View As", we probably still want to show what *that* user would see if they have approval rights
        $pendingApprovals = $user->isSuperAdmin()
            ? User::pending()->with('profile')->latest()->get()
            : collect(); // Or $user->getPendingApprovals() if that logic exists

        $isViewAs = $currentUser->id !== $user->id;

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'pendingApprovals', 'isViewAs'));
    }

    /**
     * Get hierarchy tree data
     */
    public function getHierarchyTree(Request $request)
    {
        $user = auth()->user();
        $targetUserId = $request->get('user_id', $user->id);

        // Check if user can access target user
        $targetUser = User::findOrFail($targetUserId);

        if (!$user->canAccess($targetUser) && $targetUser->id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        // Build tree
        $tree = $this->buildTree($targetUser);

        return response()->json($tree);
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
