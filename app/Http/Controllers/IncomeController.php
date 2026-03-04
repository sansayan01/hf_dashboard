<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    private function authorizeSuperAdmin()
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can access income tracking.');
        }
    }

    /**
     * List incomes with filters, summary, and advanced analytics
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Income::with('creator');
        $this->applyFilters($query, $request);

        // ── Clone Query for Stats & Charts ──
        $statsQuery = clone $query;
        $incomes = $query->paginate(20)->withQueryString();

        $data = $this->getStatsAndAnalytics($statsQuery);

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('incomes.partials.table', compact('incomes'))->render(),
                'pagination_html' => (string) $incomes->links(),
                'active_filters_html' => view('incomes.partials.active_filters', compact('incomes'))->render(),
                'stats' => array_merge($data['stats'], [
                    'resultCount' => $incomes->total() . ' results',
                    'activeCount' => collect(['search', 'category', 'payment_method', 'date_from', 'date_to', 'date_preset', 'amount_min', 'amount_max'])->filter(fn($k) => request()->filled($k))->count(),
                ]),
                'charts' => $data['charts']
            ]);
        }

        return view('incomes.index', array_merge(['incomes' => $incomes], $data['all_vars']));
    }

    /**
     * Apply all search and filter logic to the query
     */
    private function applyFilters($query, Request $request): void
    {
        // ── Sort ──
        $sortBy = $request->input('sort_by', 'income_date');
        $sortDir = $request->input('sort_dir', 'desc');
        $allowedSorts = ['income_date', 'amount', 'title', 'category', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('income_date');
        }

        // ── Quick Date Presets ──
        if ($request->filled('date_preset')) {
            switch ($request->date_preset) {
                case 'today':
                    $query->whereDate('income_date', today());
                    break;
                case '7d':
                    $query->whereDate('income_date', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->whereDate('income_date', '>=', now()->subDays(30));
                    break;
                case '90d':
                    $query->whereDate('income_date', '>=', now()->subDays(90));
                    break;
                case 'this_month':
                    $query->whereMonth('income_date', now()->month)
                        ->whereYear('income_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('income_date', now()->subMonth()->month)
                        ->whereYear('income_date', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('income_date', now()->year);
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
        if ($request->filled('received_by')) {
            $query->where('received_by', 'like', "%{$request->received_by}%");
        }
        if ($request->filled('source')) {
            $query->where('source', 'like', "%{$request->source}%");
        }
        if ($request->filled('date_from') && !$request->filled('date_preset')) {
            $query->whereDate('income_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to') && !$request->filled('date_preset')) {
            $query->whereDate('income_date', '<=', $request->date_to);
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
                    ->orWhere('received_by', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
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
        $totalIncome = (clone $statsQuery)->sum('amount');
        $totalCount = (clone $statsQuery)->count();

        $thisMonthTotal = (clone $statsQuery)->whereMonth('income_date', now()->month)
            ->whereYear('income_date', now()->year)
            ->sum('amount');

        $lastMonthTotal = (clone $statsQuery)->whereMonth('income_date', now()->subMonth()->month)
            ->whereYear('income_date', now()->subMonth()->year)
            ->sum('amount');

        $monthChange = $lastMonthTotal > 0
            ? round((($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 1)
            : ($thisMonthTotal > 0 ? 100 : 0);

        $topCategory = (clone $statsQuery)->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        // ── Analytics: Trends ──
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = (clone $statsQuery)->whereMonth('income_date', $date->month)
                ->whereYear('income_date', $date->year)
                ->sum('amount');
            $monthlyTrend->push([
                'label' => $date->format('M Y'),
                'short' => $date->format('M'),
                'total' => round($total, 2),
            ]);
        }

        $dailyIncome = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = (clone $statsQuery)->whereDate('income_date', $date->toDateString())->sum('amount');
            $dailyIncome->push([
                'date' => $date->format('d'),
                'day' => $date->format('D'),
                'full' => $date->format('d M'),
                'total' => round($total, 2),
            ]);
        }
        $avgDailyIncome = $dailyIncome->avg('total');

        // ── Analytics: Breakdowns ──
        $categoryBreakdown = (clone $statsQuery)->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $paymentBreakdown = (clone $statsQuery)->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $recentIncomes = (clone $statsQuery)->latest('income_date')->latest('created_at')->limit(5)->get();

        // ── Advanced comparisons ──
        $thisWeekTotal = (clone $statsQuery)->whereBetween('income_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('amount');
        $lastWeekTotal = (clone $statsQuery)->whereBetween('income_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->sum('amount');
        $weekChange = $lastWeekTotal > 0
            ? round((($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1)
            : ($thisWeekTotal > 0 ? 100 : 0);

        $topIncomes = (clone $statsQuery)->orderByDesc('amount')->limit(5)->get();

        $dayOfWeekIncome = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])->map(function ($day, $i) use ($statsQuery) {
            $dayNum = $i + 1;
            $sqlDayNum = ($dayNum == 7) ? 1 : ($dayNum + 1);
            return [
                'day' => $day,
                'total' => round((clone $statsQuery)->whereRaw('DAYOFWEEK(income_date) = ?', [$sqlDayNum])->sum('amount'), 2),
                'count' => (clone $statsQuery)->whereRaw('DAYOFWEEK(income_date) = ?', [$sqlDayNum])->count()
            ];
        });

        $dayOfMonth = now()->day;
        $projectedMonthly = $dayOfMonth > 0 ? round(($thisMonthTotal / $dayOfMonth) * now()->daysInMonth, 2) : 0;

        $categoryMonthlyTrend = collect();
        $allCategories = Income::select('category')->distinct()->pluck('category');
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthData = ['label' => $date->format('M')];
            foreach ($allCategories as $cat) {
                $monthData[$cat] = round((clone $statsQuery)->where('category', $cat)
                    ->whereMonth('income_date', $date->month)->whereYear('income_date', $date->year)->sum('amount'), 2);
            }
            $categoryMonthlyTrend->push($monthData);
        }

        $threeMonthsAgo = now()->subMonths(3);
        $threeMonthIncomeCount = (clone $statsQuery)->where('income_date', '>=', $threeMonthsAgo)->count();
        $incomesPerWeek = round($threeMonthIncomeCount / max(now()->diffInWeeks($threeMonthsAgo), 1), 1);

        return [
            'stats' => [
                'totalIncome' => number_format($totalIncome, 2),
                'totalCount' => $totalCount,
                'thisMonthTotal' => number_format($thisMonthTotal, 2),
                'monthChange' => $monthChange,
                'topCategory' => $topCategory ? $topCategory->category : 'N/A',
                'thisWeekTotal' => number_format($thisWeekTotal, 2),
                'weekChange' => $weekChange,
                'projectedMonthly' => number_format($projectedMonthly, 2),
                'incomesPerWeek' => $incomesPerWeek,
                'growthPercent' => $projectedMonthly > 0 ? round(($thisMonthTotal / $projectedMonthly) * 100) : 0,
            ],
            'charts' => [
                'monthlyTrend' => $monthlyTrend,
                'categoryBreakdown' => $categoryBreakdown,
                'paymentBreakdown' => $paymentBreakdown,
                'dailyIncome' => $dailyIncome,
                'dayOfWeekIncome' => $dayOfWeekIncome,
                'categoryMonthlyTrend' => $categoryMonthlyTrend,
                'topIncomes' => $topIncomes,
                'recentIncomes' => $recentIncomes
            ],
            'all_vars' => compact(
                'totalIncome',
                'totalCount',
                'thisMonthTotal',
                'lastMonthTotal',
                'monthChange',
                'topCategory',
                'monthlyTrend',
                'categoryBreakdown',
                'paymentBreakdown',
                'dailyIncome',
                'avgDailyIncome',
                'recentIncomes',
                'thisWeekTotal',
                'lastWeekTotal',
                'weekChange',
                'topIncomes',
                'dayOfWeekIncome',
                'projectedMonthly',
                'categoryMonthlyTrend',
                'allCategories',
                'incomesPerWeek'
            )
        ];
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('incomes.create');
    }

    /**
     * Store new income
     */
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Income::CATEGORIES),
            'income_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Income::PAYMENT_METHODS)),
            'received_by' => 'required|string|max:255',
            'source' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        $validated['created_by'] = Auth::id();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('income_receipts', 'public');
        }

        unset($validated['receipt']);

        Income::create($validated);

        return redirect()->route('incomes.index')->with('success', 'Income recorded successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Income $income)
    {
        $this->authorizeSuperAdmin();
        return view('incomes.edit', compact('income'));
    }

    /**
     * Update existing income
     */
    public function update(Request $request, Income $income)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'required|string|in:' . implode(',', Income::CATEGORIES),
            'income_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|in:' . implode(',', array_keys(Income::PAYMENT_METHODS)),
            'received_by' => 'required|string|max:255',
            'source' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Handle receipt upload (replace old one)
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($income->receipt_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($income->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('income_receipts', 'public');
        }

        unset($validated['receipt']);

        $income->update($validated);

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    /**
     * Delete income
     */
    public function destroy(Income $income)
    {
        $this->authorizeSuperAdmin();

        // Delete receipt file if exists
        if ($income->receipt_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($income->receipt_path);
        }

        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }

    /**
     * Export incomes as CSV
     */
    public function export(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Income::with('creator')->latest('income_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('received_by')) {
            $query->where('received_by', 'like', "%{$request->received_by}%");
        }
        if ($request->filled('source')) {
            $query->where('source', 'like', "%{$request->source}%");
        }
        if ($request->filled('date_from')) {
            $query->whereDate('income_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('income_date', '<=', $request->date_to);
        }

        $incomes = $query->get();

        $filename = 'incomes_export_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($incomes) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Date', 'Title', 'Category', 'Amount (₹)', 'Payment Method', 'Received By', 'Source', 'Reference', 'Description', 'Notes', 'Recorded By']);

            foreach ($incomes as $income) {
                fputcsv($file, [
                    $income->income_date->format('d/m/Y'),
                    $income->title,
                    $income->category,
                    number_format($income->amount, 2),
                    Income::PAYMENT_METHODS[$income->payment_method] ?? $income->payment_method,
                    $income->received_by ?? '-',
                    $income->source ?? '-',
                    $income->reference_number ?? '-',
                    $income->description ?? '-',
                    $income->notes ?? '-',
                    $income->creator?->profile?->full_name ?? 'System',
                ]);
            }

            // Total row
            fputcsv($file, []);
            fputcsv($file, ['', '', 'TOTAL', number_format($incomes->sum('amount'), 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
