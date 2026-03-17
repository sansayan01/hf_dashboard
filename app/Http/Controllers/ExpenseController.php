<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private function authorizeFinanceAccess($action = 'view')
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->hasFinancePermission($action)) {
            abort(403, 'Unauthorized access: You do not have permission to access expense tracking.');
        }
    }

    /**
     * List expenses with filters, summary, and advanced analytics
     */
    public function index(Request $request)
    {
        $this->authorizeFinanceAccess('view');

        $query = Expense::with('creator');
        $this->applyFilters($query, $request);

        // ── Clone Query for Stats & Charts ──
        $statsQuery = clone $query;
        $expenses = $query->paginate(20)->withQueryString();

        $data = $this->getStatsAndAnalytics($statsQuery);

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('expenses.partials.table', compact('expenses'))->render(),
                'pagination_html' => (string) $expenses->links(),
                'active_filters_html' => view('expenses.partials.active_filters', compact('expenses'))->render(),
                'stats' => array_merge($data['stats'], [
                    'resultCount' => $expenses->total() . ' results',
                    'activeCount' => collect(['search', 'category', 'payment_method', 'date_from', 'date_to', 'date_preset', 'amount_min', 'amount_max'])->filter(fn($k) => request()->filled($k))->count(),
                ]),
                'charts' => $data['charts']
            ]);
        }

        return view('expenses.index', array_merge(['expenses' => $expenses], $data['all_vars']));
    }

    /**
     * Apply all search and filter logic to the query
     */
    private function applyFilters($query, Request $request): void
    {
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
        if ($request->filled('expense_by')) {
            $query->where('expense_by', 'like', "%{$request->expense_by}%");
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
                    ->orWhere('expense_by', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Compile statistical and analytics data for the view
     */
    private function getStatsAndAnalytics($statsQuery): array
    {
        // ── Core Stats ──
        $totalExpenses = (clone $statsQuery)->sum('amount');
        $totalCount = (clone $statsQuery)->count();

        $thisMonthTotal = (clone $statsQuery)->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $lastMonthTotal = (clone $statsQuery)->whereMonth('expense_date', now()->subMonth()->month)
            ->whereYear('expense_date', now()->subMonth()->year)
            ->sum('amount');

        $monthChange = $lastMonthTotal > 0
            ? round((($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 1)
            : ($thisMonthTotal > 0 ? 100 : 0);

        $topCategory = (clone $statsQuery)->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        // ── Analytics: Trends ──
        $monthlyTrendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = (clone $statsQuery)->whereMonth('expense_date', $date->month)
                ->whereYear('expense_date', $date->year)
                ->sum('amount');
            $monthlyTrendData[] = [
                'label' => $date->format('M Y'),
                'short' => $date->format('M'),
                'total' => round($total, 2),
            ];
        }
        $monthlyTrend = collect($monthlyTrendData);

        $dailySpendingData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = (clone $statsQuery)->whereDate('expense_date', $date->toDateString())->sum('amount');
            $dailySpendingData[] = [
                'date' => $date->format('d'),
                'day' => $date->format('D'),
                'full' => $date->format('d M'),
                'total' => round($total, 2),
            ];
        }
        $dailySpending = collect($dailySpendingData);
        $avgDailySpend = $dailySpending->avg('total');

        // ── Analytics: Breakdowns ──
        $categoryBreakdown = (clone $statsQuery)->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $paymentBreakdown = (clone $statsQuery)->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $recentExpenses = (clone $statsQuery)->latest('expense_date')->latest('created_at')->limit(5)->get();

        // ── Advanced comparisons ──
        $thisWeekTotal = (clone $statsQuery)->whereBetween('expense_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('amount');
        $lastWeekTotal = (clone $statsQuery)->whereBetween('expense_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->sum('amount');
        $weekChange = $lastWeekTotal > 0
            ? round((($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1)
            : ($thisWeekTotal > 0 ? 100 : 0);

        $topExpenses = (clone $statsQuery)->orderByDesc('amount')->limit(5)->get();

        $dayOfWeekSpending = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])->map(function ($day, $i) use ($statsQuery) {
            $dayNum = $i + 1;
            $sqlDayNum = ($dayNum == 7) ? 1 : ($dayNum + 1);
            return [
                'day' => $day,
                'total' => round((clone $statsQuery)->whereRaw('DAYOFWEEK(expense_date) = ?', [$sqlDayNum])->sum('amount'), 2),
                'count' => (clone $statsQuery)->whereRaw('DAYOFWEEK(expense_date) = ?', [$sqlDayNum])->count()
            ];
        });

        $dayOfMonth = now()->day;
        $projectedMonthly = $dayOfMonth > 0 ? round(($thisMonthTotal / $dayOfMonth) * now()->daysInMonth, 2) : 0;

        $categoryMonthlyTrendData = [];
        $allCategories = Expense::select('category')->distinct()->pluck('category');
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthData = ['label' => $date->format('M')];
            foreach ($allCategories as $cat) {
                $monthData[$cat] = round((clone $statsQuery)->where('category', $cat)
                    ->whereMonth('expense_date', $date->month)->whereYear('expense_date', $date->year)->sum('amount'), 2);
            }
            $categoryMonthlyTrendData[] = $monthData;
        }
        $categoryMonthlyTrend = collect($categoryMonthlyTrendData);

        $threeMonthsAgo = now()->subMonths(3);
        $threeMonthExpenseCount = (clone $statsQuery)->where('expense_date', '>=', $threeMonthsAgo)->count();
        $expensesPerWeek = round($threeMonthExpenseCount / max(now()->diffInWeeks($threeMonthsAgo), 1), 1);

        return [
            'stats' => [
                'totalExpenses' => number_format($totalExpenses, 2),
                'totalCount' => $totalCount,
                'thisMonthTotal' => number_format($thisMonthTotal, 2),
                'monthChange' => $monthChange,
                'topCategory' => $topCategory ? $topCategory->category : 'N/A',
                'thisWeekTotal' => number_format($thisWeekTotal, 2),
                'weekChange' => $weekChange,
                'projectedMonthly' => number_format($projectedMonthly, 2),
                'expensesPerWeek' => $expensesPerWeek,
                'burnPercent' => $projectedMonthly > 0 ? round(($thisMonthTotal / $projectedMonthly) * 100) : 0,
            ],
            'charts' => [
                'monthlyTrend' => $monthlyTrend,
                'categoryBreakdown' => $categoryBreakdown,
                'paymentBreakdown' => $paymentBreakdown,
                'dailySpending' => $dailySpending,
                'dayOfWeekSpending' => $dayOfWeekSpending,
                'categoryMonthlyTrend' => $categoryMonthlyTrend,
                'topExpenses' => $topExpenses,
                'recentExpenses' => $recentExpenses
            ],
            'all_vars' => compact(
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
            )
        ];
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorizeFinanceAccess('edit');
        return view('expenses.create');
    }

    /**
     * Store new expense
     */
    public function store(Request $request)
    {
        $this->authorizeFinanceAccess('edit');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Expense::CATEGORIES),
            'expense_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Expense::PAYMENT_METHODS)),
            'expense_by' => 'required|string|max:255',
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
        $this->authorizeFinanceAccess('edit');
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update existing expense
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorizeFinanceAccess('edit');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Expense::CATEGORIES),
            'expense_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Expense::PAYMENT_METHODS)),
            'expense_by' => 'required|string|max:255',
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
        $this->authorizeFinanceAccess('edit');

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
        $this->authorizeFinanceAccess('view');

        $query = Expense::with('creator')->latest('expense_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('expense_by')) {
            $query->where('expense_by', 'like', "%{$request->expense_by}%");
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
            fputcsv($file, ['Date', 'Title', 'Category', 'Amount (₹)', 'Payment Method', 'Expense By', 'Reference', 'Description', 'Notes', 'Recorded By']);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->expense_date->format('d/m/Y'),
                    $expense->title,
                    $expense->category,
                    number_format($expense->amount, 2),
                    Expense::PAYMENT_METHODS[$expense->payment_method] ?? $expense->payment_method,
                    $expense->expense_by ?? '-',
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
