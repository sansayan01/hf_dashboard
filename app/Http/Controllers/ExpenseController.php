<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private function authorizeSuperAdmin()
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can access expense tracking.');
        }
    }

    /**
     * List expenses with filters, summary, and advanced analytics
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Expense::with('creator');

        // ── Sort ──
        $sortBy = $request->input('sort_by', 'expense_date');
        $sortDir = $request->input('sort_dir', 'desc');
        $allowedSorts = ['expense_date', 'amount', 'title', 'category', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('expense_date');
        }

        // ── Quick Date Presets ──
        if ($request->filled('date_preset')) {
            switch ($request->date_preset) {
                case 'today':
                    $query->whereDate('expense_date', today());
                    break;
                case '7d':
                    $query->whereDate('expense_date', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->whereDate('expense_date', '>=', now()->subDays(30));
                    break;
                case '90d':
                    $query->whereDate('expense_date', '>=', now()->subDays(90));
                    break;
                case 'this_month':
                    $query->whereMonth('expense_date', now()->month)
                        ->whereYear('expense_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('expense_date', now()->subMonth()->month)
                        ->whereYear('expense_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('expense_date', now()->year);
                    break;
            }
        }

        // ── Standard Filters ──
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from') && !$request->filled('date_preset')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to') && !$request->filled('date_preset')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $expenses = $query->paginate(20)->withQueryString();

        // ── Core Stats ──
        $totalExpenses = Expense::sum('amount');
        $totalCount = Expense::count();

        $thisMonthTotal = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $lastMonthTotal = Expense::whereMonth('expense_date', now()->subMonth()->month)
            ->whereYear('expense_date', now()->subMonth()->year)
            ->sum('amount');

        $monthChange = $lastMonthTotal > 0
            ? round((($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 1)
            : ($thisMonthTotal > 0 ? 100 : 0);

        $topCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        // ── Analytics: Monthly Trend (Last 6 months) ──
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = Expense::whereMonth('expense_date', $date->month)
                ->whereYear('expense_date', $date->year)
                ->sum('amount');
            $monthlyTrend->push([
                'label' => $date->format('M Y'),
                'short' => $date->format('M'),
                'total' => round($total, 2),
            ]);
        }

        // ── Analytics: Category Breakdown (Pie/Doughnut) ──
        $categoryBreakdown = Expense::selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // ── Analytics: Payment Method Breakdown ──
        $paymentBreakdown = Expense::selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        // ── Analytics: Daily Spending (Last 30 days) ──
        $dailySpending = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Expense::whereDate('expense_date', $date->toDateString())->sum('amount');
            $dailySpending->push([
                'date' => $date->format('d'),
                'day' => $date->format('D'),
                'full' => $date->format('d M'),
                'total' => round($total, 2),
            ]);
        }

        $avgDailySpend = $dailySpending->avg('total');

        // ── Recent Activity Feed (Latest 5) ──
        $recentExpenses = Expense::with('creator')
            ->latest('expense_date')
            ->latest('created_at')
            ->limit(5)
            ->get();

        // ── Advanced: Week-over-Week Comparison ──
        $thisWeekTotal = Expense::whereBetween('expense_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('amount');
        $lastWeekTotal = Expense::whereBetween('expense_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->sum('amount');
        $weekChange = $lastWeekTotal > 0
            ? round((($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1)
            : ($thisWeekTotal > 0 ? 100 : 0);

        // ── Advanced: Top 5 Largest Expenses ──
        $topExpenses = Expense::orderByDesc('amount')->limit(5)->get();

        // ── Advanced: Day-of-Week Spending Heatmap ──
        $dayOfWeekSpending = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])->map(function ($day, $i) {
            $dayNum = $i + 1; // 1=Mon, 7=Sun
            // DAYOFWEEK returns 1 for Sunday, 2 for Monday, ..., 7 for Saturday.
            // Adjusting for 1=Mon, 7=Sun: if $dayNum is 7 (Sunday), use 1. Otherwise, use $dayNum + 1.
            $sqlDayNum = ($dayNum == 7) ? 1 : ($dayNum + 1);
            $total = Expense::whereRaw('DAYOFWEEK(expense_date) = ?', [$sqlDayNum])->sum('amount');
            $count = Expense::whereRaw('DAYOFWEEK(expense_date) = ?', [$sqlDayNum])->count();
            return ['day' => $day, 'total' => round($total, 2), 'count' => $count];
        });

        // ── Advanced: Projected Monthly Burn ──
        $dayOfMonth = now()->day;
        $daysInMonth = now()->daysInMonth;
        $projectedMonthly = $dayOfMonth > 0
            ? round(($thisMonthTotal / $dayOfMonth) * $daysInMonth, 2)
            : 0;

        // ── Advanced: Category Monthly Trend (Last 3 months for radar) ──
        $categoryMonthlyTrend = collect();
        $allCategories = Expense::select('category')->distinct()->pluck('category');
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthData = ['label' => $date->format('M')];
            foreach ($allCategories as $cat) {
                $monthData[$cat] = round(Expense::where('category', $cat)
                    ->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year)
                    ->sum('amount'), 2);
            }
            $categoryMonthlyTrend->push($monthData);
        }

        // ── Advanced: Expense Frequency (avg expenses per week in last 3 months) ──
        $threeMonthsAgo = now()->subMonths(3);
        $weekCount = max(now()->diffInWeeks($threeMonthsAgo), 1);
        $threeMonthExpenseCount = Expense::where('expense_date', '>=', $threeMonthsAgo)->count();
        $expensesPerWeek = round($threeMonthExpenseCount / $weekCount, 1);

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'totalCount',
            'thisMonthTotal',
            'lastMonthTotal',
            'monthChange',
            'topCategory',
            'monthlyTrend',
            'categoryBreakdown',
            'paymentBreakdown',
            'dailySpending',
            'avgDailySpend',
            'recentExpenses',
            'thisWeekTotal',
            'lastWeekTotal',
            'weekChange',
            'topExpenses',
            'dayOfWeekSpending',
            'projectedMonthly',
            'categoryMonthlyTrend',
            'allCategories',
            'expensesPerWeek'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('expenses.create');
    }

    /**
     * Store new expense
     */
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Expense::CATEGORIES),
            'expense_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Expense::PAYMENT_METHODS)),
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        $validated['created_by'] = Auth::id();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('expense_receipts', 'public');
        }

        unset($validated['receipt']);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Expense $expense)
    {
        $this->authorizeSuperAdmin();
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update existing expense
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Expense::CATEGORIES),
            'expense_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Expense::PAYMENT_METHODS)),
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Handle receipt upload (replace old one)
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('expense_receipts', 'public');
        }

        unset($validated['receipt']);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete expense
     */
    public function destroy(Expense $expense)
    {
        $this->authorizeSuperAdmin();

        // Delete receipt file if exists
        if ($expense->receipt_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Export expenses as CSV
     */
    public function export(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Expense::with('creator')->latest('expense_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->get();

        $filename = 'expenses_export_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($expenses) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Date', 'Title', 'Category', 'Amount (₹)', 'Payment Method', 'Reference', 'Description', 'Notes', 'Recorded By']);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->expense_date->format('d/m/Y'),
                    $expense->title,
                    $expense->category,
                    number_format($expense->amount, 2),
                    Expense::PAYMENT_METHODS[$expense->payment_method] ?? $expense->payment_method,
                    $expense->reference_number ?? '-',
                    $expense->description ?? '-',
                    $expense->notes ?? '-',
                    $expense->creator?->profile?->full_name ?? 'System',
                ]);
            }

            // Total row
            fputcsv($file, []);
            fputcsv($file, ['', '', 'TOTAL', number_format($expenses->sum('amount'), 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
