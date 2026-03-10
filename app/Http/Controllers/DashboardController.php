<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Income;
use App\Models\Expense;
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
        $financials = $user->hasFinancePermission('view') ? $this->getFinancialOverview() : null;

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

        return view('dashboard.index', compact('user', 'currentUser', 'stats', 'reports', 'recentActivities', 'isViewAs', 'canApprove', 'canViewReports', 'canViewDownline', 'earnings', 'pendingUsers', 'financials'));
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

        // Read all earnings directly from attendance records
        // These records are populated by IncentiveService which recursively credits
        // the entire hierarchy (RO → RM → BM → DM) using each role's configured rates
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

        // Monthly TA/DA base comes directly from attendance records
        // For RO in TAB: ta_amount = Travel Allowance (set when attendance marked present)
        // For any role in DAB: ta_amount = DA earnings (credited per successful appointment)
        // For RM/BM/DM in TAB: ta_amount = 0 (they don't get TA, only activity commissions)
        $monthlyTa = $earningsData->monthly_ta ?? 0;
        $monthlyIncentives = $earningsData->monthly_incentives ?? 0;
        $monthlyTotal = $monthlyTa + $monthlyIncentives;

        // Today's earnings - read directly from today's attendance record
        $todayTa = $todayEarnings->ta_amount ?? 0;
        $todayIncentives = $todayEarnings ? (
            $todayEarnings->medicines_amount +
            $todayEarnings->pathology_amount +
            $todayEarnings->membership_amount +
            $todayEarnings->ots_amount
        ) : 0;
        $todayTotal = $todayTa + $todayIncentives;

        // DAB data for display (appointment count)
        $dabData = null;
        if ($salaryMode === 'dab') {
            $dabData = $user->getMonthlyDabEarnings();
        }

        return [
            'salary_mode' => $salaryMode,
            'monthly_ta' => $monthlyTa,
            'monthly_incentives' => $monthlyIncentives,
            'monthly_breakdown' => [
                'ta' => $monthlyTa,
                'medicines' => $earningsData->monthly_medicines ?? 0,
                'pathology' => $earningsData->monthly_pathology ?? 0,
                'membership' => $earningsData->monthly_membership ?? 0,
                'ots' => $earningsData->monthly_ots ?? 0,
            ],
            'monthly_total' => $monthlyTotal,
            'today_total' => $todayTotal,
            'today_breakdown' => [
                'ta' => $todayTa,
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
     * Get financial overview data for Super Admin dashboard
     */
    private function getFinancialOverview(): array
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // This month totals
        $thisMonthIncome = Income::where('income_date', '>=', $monthStart)->sum('amount');
        $thisMonthExpense = Expense::where('expense_date', '>=', $monthStart)->sum('amount');

        // Last month totals
        $lastMonthIncome = Income::whereBetween('income_date', [$lastMonthStart, $lastMonthEnd])->sum('amount');
        $lastMonthExpense = Expense::whereBetween('expense_date', [$lastMonthStart, $lastMonthEnd])->sum('amount');

        // Month-over-month changes
        $incomeChange = $lastMonthIncome > 0
            ? round((($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1)
            : ($thisMonthIncome > 0 ? 100 : 0);
        $expenseChange = $lastMonthExpense > 0
            ? round((($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100, 1)
            : ($thisMonthExpense > 0 ? 100 : 0);

        // 6-month trend
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $mStart = $date->copy()->startOfMonth();
            $mEnd = $date->copy()->endOfMonth();
            $monthlyTrend->push([
                'label' => $date->format('M'),
                'income' => round(Income::whereBetween('income_date', [$mStart, $mEnd])->sum('amount'), 2),
                'expense' => round(Expense::whereBetween('expense_date', [$mStart, $mEnd])->sum('amount'), 2),
            ]);
        }

        // Top 3 categories
        $topIncomeCategories = Income::where('income_date', '>=', $monthStart)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->limit(3)->get();

        $topExpenseCategories = Expense::where('expense_date', '>=', $monthStart)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->limit(3)->get();

        // Recent 5 combined entries
        $recentIncomes = Income::latest('income_date')->latest('created_at')->limit(5)->get()->toBase()
            ->map(fn($i) => ['type' => 'income', 'title' => $i->title, 'amount' => $i->amount, 'category' => $i->category, 'date' => $i->income_date]);
        $recentExpenses = Expense::latest('expense_date')->latest('created_at')->limit(5)->get()->toBase()
            ->map(fn($e) => ['type' => 'expense', 'title' => $e->title, 'amount' => $e->amount, 'category' => $e->category, 'date' => $e->expense_date]);
        $recentFinancials = $recentIncomes->merge($recentExpenses)->sortByDesc('date')->take(5)->values();

        return [
            'thisMonthIncome' => $thisMonthIncome,
            'thisMonthExpense' => $thisMonthExpense,
            'netFlow' => $thisMonthIncome - $thisMonthExpense,
            'incomeChange' => $incomeChange,
            'expenseChange' => $expenseChange,
            'monthlyTrend' => $monthlyTrend,
            'topIncomeCategories' => $topIncomeCategories,
            'topExpenseCategories' => $topExpenseCategories,
            'recentFinancials' => $recentFinancials,
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
