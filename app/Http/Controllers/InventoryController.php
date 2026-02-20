<?php

namespace App\Http\Controllers;

use App\Models\InventoryStock;
use App\Models\InventoryTransaction;
use App\Models\Medicine;
use App\Models\Survey;
use App\Models\InventoryWarehouse;
use App\Models\InventorySponsor;
use App\Models\MedicineDistribution;
use App\Models\MedicineDistributionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\MedicineCategory;
use App\Models\User;

class InventoryController extends Controller
{
    /**
     * Display stock overview.
     */
    /**
     * Display stock overview with advanced dashboard metrics.
     */
    public function index(Request $request)
    {
        $user = User::getEffectiveUser();
        $currentUser = auth()->user(); // Still needed for some specific logic
        $selectedWarehouseId = $this->resolveSelectedWarehouseId($user, $request);

        // Retroactive fix: Ensure all active stocks have a sponsor_id
        $this->ensureStockSponsors();

        // Get ALL aggregated stocks for metrics and charts
        $allAggregatedStocks = $this->getAggregatedStocks($request, $selectedWarehouseId);

        // --- Dashboard Metrics Calculation (on Full Set) ---
        $totalValue = $this->calculateTotalValue($allAggregatedStocks);
        $totalMedicines = $allAggregatedStocks->pluck('medicine_id')->unique()->count();

        $stockHealth = $this->calculateStockHealth($allAggregatedStocks);
        $lowStockCount = $stockHealth['lowStockCount'];
        $nearExpiryCount = $stockHealth['nearExpiryCount'];
        $expiredCount = $stockHealth['expiredCount'];

        // Chart Data
        $categoryChartData = $this->prepareCategoryChart($allAggregatedStocks);
        $warehouseChartData = $this->prepareWarehouseChart($allAggregatedStocks, $selectedWarehouseId);
        $trendChartData = $this->prepareTrendChart($selectedWarehouseId);
        $topMovers = $this->getTopMovers($selectedWarehouseId);
        $recentActivity = $this->getRecentActivity($selectedWarehouseId);
        $medicineValueChartData = $this->getMedicineValueChartData($selectedWarehouseId);
        $categoryQtyChartData = $this->getCategoryQtyChartData($allAggregatedStocks);
        $sponsorChartData = $this->getSponsorChartData($allAggregatedStocks);
        $expiryBreakdown = $this->getExpiryBreakdown($allAggregatedStocks);

        // Financials
        $todaySales = $this->getTodaySales($selectedWarehouseId);
        $todayPurchases = $this->getTodayPurchases($selectedWarehouseId);
        $paymentMethods = $this->getPaymentMethods($selectedWarehouseId);
        $receivables = $this->getReceivables($selectedWarehouseId);
        $recentDues = $this->getRecentDues();

        // Dead Stock
        $deadStockCount = $this->getDeadStockCount($selectedWarehouseId);

        // Warehouses - pharmacists/OICs only see their camp
        $warehouses = $this->getAccessibleWarehouses($user);

        // Get medicine quantities for legacy chart - respect selected warehouse and exclusivity
        $medicineData = $this->getMedicineChartData($request, $selectedWarehouseId);

        // Fetch categories for the filter
        $categories = MedicineCategory::orderBy('name')->get();

        // Low stock list (with coverage days)
        $lowStockMedicines = $this->getLowStockMedicines($request, $selectedWarehouseId);
        $lowStockMedicines = $this->attachCoverageDays($lowStockMedicines, $selectedWarehouseId);

        $stocks = $allAggregatedStocks;

        return view('inventory.index', compact(
            'stocks',
            'lowStockMedicines',
            'warehouses',
            'categories',
            'medicineData',
            'totalValue',
            'totalMedicines',
            'lowStockCount',
            'nearExpiryCount',
            'expiredCount',
            'medicineValueChartData',
            'warehouseChartData',
            'trendChartData',
            'topMovers',
            'recentActivity',
            'todaySales',
            'todayPurchases',
            'paymentMethods',
            'receivables',
            'deadStockCount',
            'expiryBreakdown',
            'categoryQtyChartData',
            'sponsorChartData',
            'recentDues'
        ));
    }

    /**
     * Export batch-wise inventory as CSV (respects current filters).
     */
    public function exportBatchInventory(Request $request)
    {
        $user = User::getEffectiveUser();
        $selectedWarehouseId = $this->resolveSelectedWarehouseId($user, $request);
        $stocks = $this->getAggregatedStocks($request, $selectedWarehouseId);

        $filename = "batch_inventory_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($stocks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Medicine', 'Quantity', 'Unit', 'Batch Number', 'Warehouse', 'Category', 'Status', 'Expiry Date']);

            foreach ($stocks as $stock) {
                $status = 'Healthy';
                if ($stock->expiry_date->isPast()) {
                    $status = 'Expired';
                } elseif ($stock->expiry_date->diffInMonths(now()) < 3) {
                    $status = 'Near Expiry';
                }

                fputcsv($file, [
                    $stock->medicine->name ?? 'Unknown',
                    $stock->quantity,
                    $stock->medicine->unit ?? 'Tablet',
                    $stock->batch_number,
                    $stock->warehouse->name ?? 'Main',
                    $stock->medicine->category->name ?? 'N/A',
                    $status,
                    $stock->expiry_date->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Show form to add new stock.
     */
    public function create()
    {
        $medicines = Medicine::orderBy('name')->get();
        $warehouses = InventoryWarehouse::where('is_active', true)
            ->where('type', InventoryWarehouse::TYPE_WAREHOUSE)
            ->get();
        $sponsors = InventorySponsor::orderBy('name')->get();
        return view('inventory.create', compact('medicines', 'warehouses', 'sponsors'));
    }

    /**
     * Store new stock and log transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'sponsor_id' => 'nullable|exists:inventory_sponsors,id',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $stock = InventoryStock::create([
                'medicine_id' => $validated['medicine_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'sponsor_id' => $validated['sponsor_id'] ?? null,
                'batch_number' => $validated['batch_number'],
                'expiry_date' => $validated['expiry_date'],
                'quantity' => $validated['quantity'],
            ]);

            $transactionData = [
                'stock_id' => $stock->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'user_id' => auth()->id(),
                'notes' => 'Stock Received',
            ];

            if (Schema::hasColumn('inventory_transactions', 'warehouse_id')) {
                $transactionData['warehouse_id'] = $validated['warehouse_id'];
            }
            if (Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
                $transactionData['sponsor_id'] = $validated['sponsor_id'] ?? null;
            }

            InventoryTransaction::create($transactionData);
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Stock added successfully.');
    }

    /**
     * Display transaction history.
     */
    public function exportTransactions()
    {
        $isStaff = auth()->user()->designation === 'staff' || auth()->user()->isOfficeInCharge();
        $isRestricted = $isStaff && auth()->user()->camp_id;
        $defaultView = $isRestricted ? 'dispenses' : 'movements';
        $view = request('view', $defaultView);

        if ($isRestricted && $view === 'movements') {
            $view = 'dispenses';
        }

        if ($view === 'dispenses') {
            $query = MedicineDistribution::with(['patient', 'camp', 'pharmacist', 'items.medicine'])->latest();
        } else {
            $query = InventoryTransaction::with(['stock.medicine', 'user', 'patient', 'warehouse', 'sponsor'])->latest();
            if ($view === 'sponsors') {
                $query->where('type', 'dispense')->whereNotNull('sponsor_id');
            } else {
                $query->where('type', '!=', 'dispense');
            }
        }

        // Apply filters
        if (request('search')) {
            $search = request('search');
            if ($view === 'dispenses') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('patient_id', 'like', '%' . $search . '%');
                    })->orWhereHas('items.medicine', function ($mq) use ($search) {
                        $mq->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('camp', function ($wq) use ($search) {
                        $wq->where('name', 'like', '%' . $search . '%');
                    });
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('patient_id', 'like', '%' . $search . '%');
                    })->orWhereHas('stock.medicine', function ($mq) use ($search) {
                        $mq->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('warehouse', function ($wq) use ($search) {
                        $wq->where('name', 'like', '%' . $search . '%');
                    });
                });
            }
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        if ($view === 'dispenses' && request('payment_method')) {
            $method = request('payment_method');
            if ($method === 'due') {
                $query->where(function ($q) {
                    $q->whereIn('payment_method', ['due', 'credit', 'later'])
                        ->orWhere('due_amount', '>', 0);
                });
            } else {
                $query->where('payment_method', $method);
            }
        }

        $transactions = $query->get();

        $filename = "transactions_" . $view . "_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions, $view) {
            $file = fopen('php://output', 'w');

            if ($view === 'dispenses') {
                fputcsv($file, ['Date', 'Time', 'Patient', 'Medicine', 'Batch', 'QTY', 'Grand Total', 'Performed By', 'Camp/Warehouse']);

                $hasDistIdColumn = Schema::hasColumn('inventory_transactions', 'distribution_id');
                $distributionTransactions = collect();

                if ($hasDistIdColumn) {
                    $distIds = $transactions->pluck('id');
                    $distributionTransactions = InventoryTransaction::whereIn('distribution_id', $distIds)
                        ->with('stock')
                        ->get()
                        ->groupBy('distribution_id');
                }

                foreach ($transactions as $transaction) {
                    /** @var MedicineDistribution $transaction */
                    $medicines = $transaction->items->map(function ($i) {
                        return $i->medicine->name . ' (x' . $i->quantity . ')';
                    })->implode(', ');

                    $batches = isset($distributionTransactions[$transaction->id])
                        ? $distributionTransactions[$transaction->id]->pluck('stock.batch_number')->unique()->filter()->implode(', ')
                        : 'N/A';

                    fputcsv($file, [
                        $transaction->created_at->format('Y-m-d'),
                        $transaction->created_at->format('h:i A'),
                        $transaction->patient->full_name ?? 'System',
                        $medicines,
                        $batches,
                        $transaction->items->count(),
                        '₹' . number_format($transaction->final_amount, 2),
                        $transaction->pharmacist->profile->full_name ?? $transaction->pharmacist->employee_id,
                        $transaction->camp->name ?? 'N/A'
                    ]);
                }
            } elseif ($view === 'sponsors') {
                fputcsv($file, ['Date', 'Sponsor', 'Medicine', 'Batch', 'QTY', 'Medicine Value']);

                foreach ($transactions as $transaction) {
                    /** @var InventoryTransaction $transaction */
                    $distribution = $transaction->distribution;
                    $distId = $distribution ? $distribution->id : filter_var($transaction->notes, FILTER_SANITIZE_NUMBER_INT);
                    $lineValue = 0;

                    if ($distId) {
                        $distItem = MedicineDistributionItem::where('distribution_id', $distId)
                            ->where('medicine_id', $transaction->stock?->medicine_id)
                            ->first();

                        if ($distItem) {
                            $lineValue = $distItem->unit_price * $transaction->quantity;
                        }
                    }

                    fputcsv($file, [
                        $transaction->created_at->format('Y-m-d'),
                        $transaction->sponsor->name ?? 'N/A',
                        $transaction->stock->medicine->name ?? 'N/A',
                        $transaction->stock->batch_number ?? 'N/A',
                        $transaction->quantity,
                        '₹' . number_format($lineValue, 2)
                    ]);
                }
            } else {
                fputcsv($file, ['Date', 'Time', 'Medicine', 'Location', 'Batch', 'Type', 'Qty', 'Performed By']);

                foreach ($transactions as $transaction) {
                    /** @var InventoryTransaction $transaction */
                    fputcsv($file, [
                        $transaction->created_at->format('Y-m-d'),
                        $transaction->created_at->format('h:i A'),
                        $transaction->stock->medicine->name ?? 'N/A',
                        $transaction->warehouse->name ?? 'N/A',
                        $transaction->stock->batch_number ?? 'N/A',
                        ucfirst($transaction->type),
                        $transaction->quantity,
                        $transaction->user->profile->full_name ?? $transaction->user->employee_id
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function transactions()
    {
        $isStaff = auth()->user()->designation === 'staff' || auth()->user()->isOfficeInCharge();
        $isRestricted = $isStaff && auth()->user()->camp_id;
        $defaultView = $isRestricted ? 'dispenses' : 'movements';
        $view = request('view', $defaultView);

        // Prevent restricted users from viewing 'movements'
        if ($isRestricted && $view === 'movements') {
            $view = 'dispenses';
        }

        if ($view === 'dispenses' || $view === 'sponsors') {
            // Retroactive fix: Ensure all stocks have a sponsor_id
            if (Schema::hasColumn('inventory_stocks', 'sponsor_id') && Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
                InventoryStock::whereNull('sponsor_id')->chunk(100, function ($stocks) {
                    foreach ($stocks as $stock) {
                        $inTx = $stock->transactions()->where('type', 'in')->first();
                        if ($inTx && $inTx->sponsor_id) {
                            $stock->update(['sponsor_id' => $inTx->sponsor_id]);
                            continue;
                        }

                        // Trace by batch
                        $rootSponsorId = InventoryTransaction::where('type', 'in')
                            ->whereNotNull('sponsor_id')
                            ->whereHas('stock', function ($q) use ($stock) {
                                $q->where('medicine_id', $stock->medicine_id)
                                    ->where('batch_number', $stock->batch_number);
                            })
                            ->value('sponsor_id');

                        if ($rootSponsorId) {
                            $stock->update(['sponsor_id' => $rootSponsorId]);
                        }
                    }
                });

                // Retroactive fix: Ensure all transactions have sponsor_id if missing
                InventoryTransaction::whereNull('sponsor_id')->chunk(200, function ($txs) {
                    foreach ($txs as $tx) {
                        $sponsorId = null;
                        if ($tx->stock && $tx->stock->sponsor_id) {
                            $sponsorId = $tx->stock->sponsor_id;
                        } else if ($tx->stock) {
                            $inTx = $tx->stock->transactions()->where('type', 'in')->first();
                            $sponsorId = $inTx?->sponsor_id;

                            if (!$sponsorId) {
                                // Trace by batch
                                $sponsorId = InventoryTransaction::where('type', 'in')
                                    ->whereNotNull('sponsor_id')
                                    ->whereHas('stock', function ($q) use ($tx) {
                                        $q->where('medicine_id', $tx->stock->medicine_id)
                                            ->where('batch_number', $tx->stock->batch_number);
                                    })
                                    ->value('sponsor_id');
                            }
                        }

                        if ($sponsorId) {
                            $tx->update(['sponsor_id' => $sponsorId]);
                        }
                    }
                });
            }

            // Retroactive fix: Link dispense transactions to distributions if missing
            if (Schema::hasColumn('inventory_transactions', 'distribution_id')) {
                InventoryTransaction::where('type', 'dispense')
                    ->whereNull('distribution_id')
                    ->chunk(100, function ($txs) {
                        foreach ($txs as $tx) {
                            $distId = filter_var($tx->notes, FILTER_SANITIZE_NUMBER_INT);
                            if ($distId && MedicineDistribution::where('id', $distId)->exists()) {
                                $tx->update(['distribution_id' => $distId]);
                            }
                        }
                    });
            }

            // Retroactive fix: Ensure old distributions have final_amount and discount fields
            if (Schema::hasColumn('medicine_distributions', 'final_amount')) {
                MedicineDistribution::where('final_amount', 0)
                    ->where('total_amount', '>', 0)
                    ->chunk(50, function ($distributions) {
                        foreach ($distributions as $dist) {
                            $perc = $dist->total_amount > 300 ? 20 : 18;
                            $amt = round(($dist->total_amount * $perc) / 100, 2);
                            $dist->update([
                                'discount_percentage' => $perc,
                                'discount_amount' => $amt,
                                'final_amount' => $dist->total_amount - $amt
                            ]);
                        }
                    });
            }
        }

        if ($view === 'dispenses') {
            $query = MedicineDistribution::with(['patient', 'camp', 'pharmacist', 'items.medicine'])->latest();
        } else {
            $query = InventoryTransaction::with(['stock.medicine', 'user', 'patient', 'warehouse', 'sponsor'])->latest();
            if ($view === 'sponsors') {
                $query->where('type', 'dispense')
                    ->where(function ($q) {
                        $q->whereNotNull('sponsor_id')
                            ->orWhereHas('stock', function ($sq) {
                                $sq->whereNotNull('sponsor_id');
                            });
                    });
            } else {
                $query->where('type', '!=', 'dispense');
            }
        }

        // Apply filters
        if (request('search')) {
            $search = request('search');
            if ($view === 'dispenses') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('patient_id', 'like', '%' . $search . '%');
                    })->orWhereHas('items.medicine', function ($mq) use ($search) {
                        $mq->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('camp', function ($wq) use ($search) {
                        $wq->where('name', 'like', '%' . $search . '%');
                    });
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('patient_id', 'like', '%' . $search . '%');
                    })->orWhereHas('stock.medicine', function ($mq) use ($search) {
                        $mq->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('warehouse', function ($wq) use ($search) {
                        $wq->where('name', 'like', '%' . $search . '%');
                    });
                });
            }
        }

        if (request('sponsor_id')) {
            if ($view === 'dispenses') {
                if (Schema::hasColumn('inventory_transactions', 'distribution_id')) {
                    $query->whereIn('id', function ($q) {
                        $q->select('distribution_id')
                            ->from('inventory_transactions')
                            ->where('sponsor_id', request('sponsor_id'))
                            ->whereNotNull('distribution_id');
                    });
                } else {
                    // Fallback to searching in notes if column doesn't exist
                    $query->whereIn('id', function ($q) {
                        $q->select('id')->from('medicine_distributions');
                        // Sponsor filtering without distribution_id is complex via notes, 
                        // so we might skip filtering or do a basic search if possible.
                    });
                }
            } else {
                $query->where('sponsor_id', request('sponsor_id'));
            }
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        if ($view === 'dispenses' && request('payment_method')) {
            $method = request('payment_method');
            if ($method === 'due') {
                $query->where(function ($q) {
                    $q->whereIn('payment_method', ['due', 'credit', 'later'])
                        ->orWhere('due_amount', '>', 0);
                });
            } else {
                $query->where('payment_method', $method);
            }
        }

        $perPage = request('per_page', 20);
        $transactions = request('view_all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        $totalGrandSum = 0;
        if ($view === 'dispenses') {
            $totalGrandSum = (clone $query)->sum('final_amount');
        } elseif ($view === 'sponsors') {
            // Calculate sum based on individual line items (unit_price * quantity)
            $allTransactions = (clone $query)->get();
            $hasDistIdColumn = Schema::hasColumn('inventory_transactions', 'distribution_id');

            foreach ($allTransactions as $transaction) {
                $distId = ($hasDistIdColumn ? $transaction->distribution_id : null)
                    ?: filter_var($transaction->notes, FILTER_SANITIZE_NUMBER_INT);
                if ($distId) {
                    $distItem = MedicineDistributionItem::where('distribution_id', $distId)
                        ->where('medicine_id', $transaction->stock->medicine_id)
                        ->first();
                    if ($distItem) {
                        $totalGrandSum += ($distItem->unit_price * $transaction->quantity);
                    }
                }
            }
        }

        $sponsors = InventorySponsor::all();

        return view('inventory.transactions', compact('transactions', 'totalGrandSum', 'sponsors', 'view', 'isStaff'));
    }

    /**
     * Show dispense form.
     */
    public function dispense($patientId = null)
    {
        $user = User::getEffectiveUser();
        $patient = $patientId ? Survey::findOrFail($patientId) : null;
        $allowedIds = $user->getDataVisibilityIds();
        $patients = Survey::whereIn('created_by', $allowedIds)->orderBy('full_name')->get();

        // Pharmacists can only see their assigned camp
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $warehouses = InventoryWarehouse::where('id', $user->camp_id)->where('is_active', true)->get();
            // Only show medicines available in their camp
            $medicines = Medicine::whereHas('stocks', function ($q) use ($user) {
                $q->where('quantity', '>', 0)->where('warehouse_id', $user->camp_id);
            })->with([
                        'stocks' => function ($q) use ($user) {
                            $q->where('quantity', '>', 0)->where('warehouse_id', $user->camp_id)->orderBy('expiry_date');
                        }
                    ])->get();
        } else {
            $warehouses = InventoryWarehouse::where('is_active', true)->get();
            $medicines = Medicine::whereHas('stocks', function ($q) {
                $q->where('quantity', '>', 0);
            })->with([
                        'stocks' => function ($q) {
                            $q->where('quantity', '>', 0)->orderBy('expiry_date');
                        }
                    ])->get();
        }

        return view('inventory.dispense', compact('patient', 'patients', 'medicines', 'warehouses'));
    }

    /**
     * Process medicine dispensing.
     */
    public function processDispense(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:surveys,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $item) {
                    $qtyToDispense = $item['quantity'];

                    // Get available stocks for this medicine (FIFO by expiry)
                    $stocks = InventoryStock::where('medicine_id', $item['medicine_id'])
                        ->where('warehouse_id', $validated['warehouse_id'])
                        ->where('quantity', '>', 0)
                        ->orderBy('expiry_date')
                        ->get();

                    if ($stocks->sum('quantity') < $qtyToDispense) {
                        throw new \Exception("Insufficient stock in the selected warehouse for medicine ID: " . $item['medicine_id']);
                    }

                    /** @var InventoryStock $stock */
                    foreach ($stocks as $stock) {
                        if ($qtyToDispense <= 0)
                            break;

                        $decrement = min($stock->quantity, $qtyToDispense);
                        $stock->decrement('quantity', $decrement);

                        $transactionData = [
                            'stock_id' => $stock->id,
                            'type' => 'dispense',
                            'quantity' => $decrement,
                            'user_id' => auth()->id(),
                            'patient_id' => $validated['patient_id'],
                            'notes' => $validated['notes'],
                        ];

                        if (Schema::hasColumn('inventory_transactions', 'warehouse_id')) {
                            $transactionData['warehouse_id'] = $validated['warehouse_id'];
                        }
                        if (Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
                            $transactionData['sponsor_id'] = $stock->sponsor_id;
                        }

                        InventoryTransaction::create($transactionData);

                        $qtyToDispense -= $decrement;
                    }
                }
            });

            return redirect()->route('inventory.index')
                ->with('success', 'Medicine(s) dispensed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Delete a transaction and revert stock changes.
     * @param InventoryTransaction $transaction
     */
    public function destroyTransaction(InventoryTransaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
                /** @var InventoryTransaction $transaction */
                /** @var InventoryStock $stock */
                $stock = $transaction->stock;

                if ($transaction->type === 'in') {
                    if ($stock->quantity < $transaction->quantity) {
                        throw new \Exception("Cannot delete this 'Stock In' because some of this stock has already been used.");
                    }
                    $stock->decrement('quantity', $transaction->quantity);
                } elseif ($transaction->type === 'dispense' || $transaction->type === 'out') {
                    $stock->increment('quantity', $transaction->quantity);
                }

                $transaction->delete();
            });

            return redirect()->back()->with('success', 'Transaction deleted and stock reverted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update transaction notes or quantity (adjusts stock).
     * @param Request $request
     * @param InventoryTransaction $transaction
     */
    public function updateTransaction(Request $request, InventoryTransaction $transaction)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($transaction, $validated) {
                /** @var InventoryTransaction $transaction */
                /** @var InventoryStock $stock */
                $stock = $transaction->stock;
                $diff = $validated['quantity'] - $transaction->quantity;

                if ($diff != 0) {
                    if ($transaction->type === 'in') {
                        if ($diff < 0 && $stock->quantity < abs($diff)) {
                            throw new \Exception("Cannot reduce quantity below current available stock.");
                        }
                        $stock->increment('quantity', $diff);
                    } elseif ($transaction->type === 'dispense' || $transaction->type === 'out') {
                        if ($diff > 0 && $stock->quantity < $diff) {
                            throw new \Exception("Insufficient stock to increase this dispense amount.");
                        }
                        $stock->decrement('quantity', $diff);
                    }
                }

                $transaction->update($validated);
            });

            return redirect()->back()->with('success', 'Transaction updated and stock adjusted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show stock transfer form.
     */
    public function transfer(Request $request)
    {
        $user = auth()->user();

        // Pharmacists can only see their assigned camp
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $warehouses = InventoryWarehouse::where('id', $user->camp_id)->where('is_active', true)->get();
            // Only show medicines available in their camp
            $medicines = Medicine::whereHas('stocks', function ($q) use ($user) {
                $q->where('quantity', '>', 0)->where('warehouse_id', $user->camp_id);
            })->with([
                        'stocks' => function ($q) use ($user) {
                            $q->where('quantity', '>', 0)->where('warehouse_id', $user->camp_id)->orderBy('expiry_date');
                        }
                    ])->get();
        } else {
            $warehouses = InventoryWarehouse::where('is_active', true)->get();
            $medicines = Medicine::whereHas('stocks', function ($q) {
                $q->where('quantity', '>', 0);
            })->with([
                        'stocks' => function ($q) {
                            $q->where('quantity', '>', 0)->orderBy('expiry_date');
                        }
                    ])->get();
        }

        $preSelectedStock = null;
        $preSelectedRepresentativeId = null;
        if ($request->has('stock_id')) {
            $preSelectedStock = InventoryStock::with('medicine', 'warehouse')->find($request->stock_id);
            if ($preSelectedStock) {
                // Find the first stock in this batch to match our grouped dropdown
                $preSelectedRepresentativeId = InventoryStock::where('medicine_id', $preSelectedStock->medicine_id)
                    ->where('warehouse_id', $preSelectedStock->warehouse_id)
                    ->where('batch_number', $preSelectedStock->batch_number)
                    ->orderBy('expiry_date')
                    ->value('id');
            }
        }

        return view('inventory.transfer', compact('warehouses', 'medicines', 'preSelectedStock', 'preSelectedRepresentativeId'));
    }

    /**
     * Process stock transfer between warehouses.
     */
    public function processTransfer(Request $request)
    {
        // Check if transfer_all is enabled
        $transferAll = $request->input('transfer_all') == '1';

        // Filter and re-index items before validation
        if ($request->has('items') && is_array($request->items)) {
            $filteredItems = array_values(array_filter($request->items, function ($item) {
                return !empty($item['stock_id']);
            }));
            $request->merge(['items' => $filteredItems]);
        }

        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:inventory_warehouses,id',
            'to_warehouse_id' => 'required|exists:inventory_warehouses,id|different:from_warehouse_id',
            'transfer_all' => 'nullable',
            'items' => (!$transferAll) ? 'required|array|min:1' : 'nullable',
            'items.*.stock_id' => (!$transferAll) ? 'required|exists:inventory_stocks,id' : 'nullable',
            'items.*.quantity' => (!$transferAll) ? 'required|integer|min:1' : 'nullable',
            'notes' => 'nullable|string',
        ], [
            'items.required' => 'Please select at least one medicine to transfer.',
            'items.min' => 'Please select at least one medicine to transfer.',
            'items.*.stock_id.required' => 'Please select a medicine for all rows.',
            'items.*.quantity.required' => 'Please enter a quantity for all selected medicines.',
        ]);

        try {
            DB::transaction(function () use ($validated, $transferAll) {
                if ($transferAll) {
                    // Transfer ALL stock from the source warehouse
                    $allStocks = InventoryStock::where('warehouse_id', $validated['from_warehouse_id'])
                        ->where('quantity', '>', 0)
                        ->get();

                    if ($allStocks->isEmpty()) {
                        throw new \Exception("No stock available to transfer from this location.");
                    }

                    /** @var InventoryStock $sourceStock */
                    foreach ($allStocks as $sourceStock) {
                        $transferQty = $sourceStock->quantity;

                        // Ensure we have a sponsor ID
                        $effectiveSponsorId = $sourceStock->sponsor_id;
                        if (!$effectiveSponsorId) {
                            $inTx = $sourceStock->transactions()->where('type', 'in')->first();
                            $effectiveSponsorId = $inTx?->sponsor_id;
                            if ($effectiveSponsorId) {
                                $sourceStock->update(['sponsor_id' => $effectiveSponsorId]);
                            }
                        }

                        // Deduct from source
                        $sourceStock->decrement('quantity', $transferQty);

                        // Find or create destination stock
                        $destStock = InventoryStock::where('medicine_id', $sourceStock->medicine_id)
                            ->where('warehouse_id', $validated['to_warehouse_id'])
                            ->where('batch_number', $sourceStock->batch_number)
                            ->where('expiry_date', $sourceStock->expiry_date)
                            ->where('sponsor_id', $effectiveSponsorId)
                            ->first();

                        if ($destStock) {
                            $destStock->increment('quantity', $transferQty);
                        } else {
                            $destStock = InventoryStock::create([
                                'medicine_id' => $sourceStock->medicine_id,
                                'warehouse_id' => $validated['to_warehouse_id'],
                                'batch_number' => $sourceStock->batch_number,
                                'expiry_date' => $sourceStock->expiry_date,
                                'sponsor_id' => $effectiveSponsorId,
                                'quantity' => $transferQty,
                            ]);
                        }

                        // Log transactions
                        InventoryTransaction::create([
                            'stock_id' => $sourceStock->id,
                            'warehouse_id' => $validated['from_warehouse_id'],
                            'sponsor_id' => $effectiveSponsorId,
                            'type' => 'out',
                            'quantity' => $transferQty,
                            'user_id' => auth()->id(),
                            'notes' => "Bulk transfer to " . InventoryWarehouse::find($validated['to_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);

                        InventoryTransaction::create([
                            'stock_id' => $destStock->id,
                            'warehouse_id' => $validated['to_warehouse_id'],
                            'sponsor_id' => $effectiveSponsorId,
                            'type' => 'in',
                            'quantity' => $transferQty,
                            'user_id' => auth()->id(),
                            'notes' => "Bulk transfer from " . InventoryWarehouse::find($validated['from_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);
                    }
                    $message = "All stock (" . $allStocks->count() . " items) transferred successfully.";
                } else {
                    // Process multi-item transfer (handles any number of items)
                    $itemCount = 0;

                    foreach ($validated['items'] as $item) {
                        /** @var InventoryStock $initialStock */
                        $initialStock = InventoryStock::findOrFail($item['stock_id']);
                        $totalQtyToTransfer = $item['quantity'];

                        $matchingStocks = InventoryStock::where('medicine_id', $initialStock->medicine_id)
                            ->where('warehouse_id', $validated['from_warehouse_id'])
                            ->where('batch_number', $initialStock->batch_number)
                            ->where('quantity', '>', 0)
                            ->orderBy('expiry_date')
                            ->get();

                        if ($matchingStocks->sum('quantity') < $totalQtyToTransfer) {
                            throw new \Exception("Insufficient stock for transfer (Medicine: {$initialStock->medicine->name}).");
                        }

                        foreach ($matchingStocks as $sourceStock) {
                            /** @var \App\Models\InventoryStock $sourceStock */
                            if ($totalQtyToTransfer <= 0)
                                break;
                            $currentTransferQty = min($sourceStock->quantity, $totalQtyToTransfer);

                            // Determine effective sponsor
                            $effectiveSponsorId = $sourceStock->sponsor_id;
                            if (!$effectiveSponsorId) {
                                $inTx = $sourceStock->transactions()->where('type', 'in')->first();
                                $effectiveSponsorId = $inTx?->sponsor_id;
                                if ($effectiveSponsorId) {
                                    $sourceStock->update(['sponsor_id' => $effectiveSponsorId]);
                                }
                            }

                            // Deduct from source
                            $sourceStock->decrement('quantity', $currentTransferQty);

                            // Find or create destination stock
                            $destStock = InventoryStock::where('medicine_id', $sourceStock->medicine_id)
                                ->where('warehouse_id', $validated['to_warehouse_id'])
                                ->where('batch_number', $sourceStock->batch_number)
                                ->where('expiry_date', $sourceStock->expiry_date)
                                ->where('sponsor_id', $effectiveSponsorId)
                                ->first();

                            if ($destStock) {
                                $destStock->increment('quantity', $currentTransferQty);
                            } else {
                                $destStock = InventoryStock::create([
                                    'medicine_id' => $sourceStock->medicine_id,
                                    'warehouse_id' => $validated['to_warehouse_id'],
                                    'batch_number' => $sourceStock->batch_number,
                                    'expiry_date' => $sourceStock->expiry_date,
                                    'sponsor_id' => $effectiveSponsorId,
                                    'quantity' => $currentTransferQty,
                                ]);
                            }

                            // Log transactions
                            InventoryTransaction::create([
                                'stock_id' => $sourceStock->id,
                                'warehouse_id' => $validated['from_warehouse_id'],
                                'sponsor_id' => $effectiveSponsorId,
                                'type' => 'out',
                                'quantity' => $currentTransferQty,
                                'user_id' => auth()->id(),
                                'notes' => "Multi-item transfer to " . InventoryWarehouse::find($validated['to_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                            ]);

                            InventoryTransaction::create([
                                'stock_id' => $destStock->id,
                                'warehouse_id' => $validated['to_warehouse_id'],
                                'sponsor_id' => $effectiveSponsorId,
                                'type' => 'in',
                                'quantity' => $currentTransferQty,
                                'user_id' => auth()->id(),
                                'notes' => "Multi-item transfer from " . InventoryWarehouse::find($validated['from_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                            ]);

                            $totalQtyToTransfer -= $currentTransferQty;
                        }

                        $itemCount++;
                    }

                    $message = "Successfully transferred {$itemCount} item(s).";
                }
            });

            return redirect()->route('inventory.index')
                ->with('success', $message ?? 'Stock transferred successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /**
     * Show stock adjustment form.
     */
    public function adjust(InventoryStock $stock)
    {
        $stock->load(['medicine', 'warehouse', 'sponsor']);
        return view('inventory.adjust', compact('stock'));
    }

    /**
     * Process stock adjustment.
     */
    public function processAdjust(Request $request, InventoryStock $stock)
    {
        $validated = $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'notes' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($stock, $validated) {
                $oldQty = $stock->quantity;
                $newQty = $validated['new_quantity'];
                $diff = $newQty - $oldQty;

                if ($diff == 0) {
                    return;
                }

                // Update stock quantity
                $stock->update(['quantity' => $newQty]);

                // Log adjustment transaction
                $transactionData = [
                    'stock_id' => $stock->id,
                    'type' => 'adjustment',
                    'quantity' => abs($diff),
                    'user_id' => auth()->id(),
                    'notes' => ($diff > 0 ? 'Stock Increased: ' : 'Stock Decreased: ') . $validated['notes'] . " (Original: $oldQty, New: $newQty)",
                ];

                if (Schema::hasColumn('inventory_transactions', 'warehouse_id')) {
                    $transactionData['warehouse_id'] = $stock->warehouse_id;
                }
                if (Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
                    $transactionData['sponsor_id'] = $stock->sponsor_id;
                }

                InventoryTransaction::create($transactionData);
            });

            return redirect()->route('inventory.index')
                ->with('success', 'Stock adjusted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function resolveSelectedWarehouseId(User $user, Request $request)
    {
        // Use effective user if it's different (mimicry)
        $user = User::getEffectiveUser();

        $selectedWarehouseId = $request->get('warehouse_id');
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            return $user->camp_id;
        } elseif ($request->has('warehouse_id') && $request->warehouse_id != '') {
            return $request->warehouse_id;
        }
        return null;
    }

    private function ensureStockSponsors()
    {
        if (Schema::hasColumn('inventory_stocks', 'sponsor_id') && Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
            InventoryStock::where('quantity', '>', 0)
                ->whereNull('sponsor_id')
                ->chunk(50, function ($stocks) {
                    foreach ($stocks as $stock) {
                        // Try immediate 'in' transaction first
                        $inTx = $stock->transactions()->where('type', 'in')->first();
                        if ($inTx && $inTx->sponsor_id) {
                            $stock->update(['sponsor_id' => $inTx->sponsor_id]);
                            continue;
                        }

                        // Try finding any 'in' transaction for the same medicine and batch that has a sponsor
                        $rootSponsorId = InventoryTransaction::where('type', 'in')
                            ->whereNotNull('sponsor_id')
                            ->whereHas('stock', function ($q) use ($stock) {
                            $q->where('medicine_id', $stock->medicine_id)
                                ->where('batch_number', $stock->batch_number);
                        })
                            ->value('sponsor_id');

                        if ($rootSponsorId) {
                            $stock->update(['sponsor_id' => $rootSponsorId]);
                        }
                    }
                });
        }
    }

    private function getAggregatedStocks(Request $request, $selectedWarehouseId)
    {
        $query = InventoryStock::with(['medicine.category', 'warehouse', 'sponsor'])
            ->where('quantity', '>', 0);

        if ($selectedWarehouseId) {
            $query->where('warehouse_id', $selectedWarehouseId);

            // "Only available at" requirement
            if ($request->has('exclusive') && $request->exclusive == '1') {
                $query->whereNotExists(function ($q) use ($selectedWarehouseId) {
                    $q->select(DB::raw(1))
                        ->from('inventory_stocks as other_stocks')
                        ->whereColumn('other_stocks.medicine_id', 'inventory_stocks.medicine_id')
                        ->where('other_stocks.warehouse_id', '!=', $selectedWarehouseId)
                        ->where('other_stocks.quantity', '>', 0);
                });
            }
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->whereHas('medicine', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status === 'low_stock') {
                $query->whereHas('medicine', function ($q) {
                    $q->whereRaw('inventory_stocks.quantity <= medicines.min_stock_level');
                });
            } elseif ($status === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($status === 'near_expiry') {
                $query->whereBetween('expiry_date', [now(), now()->addDays(90)]);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('medicine', function ($mq) use ($search) {
                    $mq->where('name', 'like', '%' . $search . '%')
                        ->orWhere('generic_name', 'like', '%' . $search . '%');
                })->orWhere('batch_number', 'like', '%' . $search . '%');
            });
        }

        return $query->get()
            ->groupBy(function ($stock) {
                return $stock->medicine_id . '-' . $stock->warehouse_id . '-' . $stock->batch_number . '-' . $stock->expiry_date->format('Y-m-d');
            })
            ->map(function ($group) {
                $mainStock = $group->first();
                $mainStock->quantity = $group->sum('quantity');
                return $mainStock;
            })
            ->sortBy(function ($stock) {
                return $stock->medicine->name ?? '';
            })
            ->values();
    }

    private function getLowStockMedicines(Request $request, $selectedWarehouseId)
    {
        $lowStockQuery = Medicine::with('category');
        return $lowStockQuery->get()->filter(function ($medicine) use ($selectedWarehouseId, $request) {
            /** @var \App\Models\Medicine $medicine */

            // If exclusivity is requested, check if it's available elsewhere first
            if ($selectedWarehouseId && $request->has('exclusive') && $request->exclusive == '1') {
                $existsElsewhere = $medicine->stocks()
                    ->where('warehouse_id', '!=', $selectedWarehouseId)
                    ->where('quantity', '>', 0)
                    ->exists();
                if ($existsElsewhere)
                    return false;
            }

            if ($selectedWarehouseId) {
                $quantity = $medicine->stocks()->where('warehouse_id', $selectedWarehouseId)->sum('quantity');
            } else {
                $quantity = $medicine->totalStock;
            }
            return $quantity <= $medicine->min_stock_level && ($selectedWarehouseId ? $quantity > 0 : true);
        });
    }

    private function getAccessibleWarehouses($user)
    {
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            return InventoryWarehouse::where('id', $user->camp_id)->where('is_active', true)->get();
        } else {
            return InventoryWarehouse::where('is_active', true)->get();
        }
    }

    private function getMedicineChartData(Request $request, $selectedWarehouseId)
    {
        return Medicine::with('category')
            ->get()
            ->map(function ($medicine) use ($selectedWarehouseId, $request) {
                /** @var \App\Models\Medicine $medicine */

                // If exclusivity is requested, check if it's available elsewhere first
                if ($selectedWarehouseId && $request->has('exclusive') && $request->exclusive == '1') {
                    $existsElsewhere = $medicine->stocks()
                        ->where('warehouse_id', '!=', $selectedWarehouseId)
                        ->where('quantity', '>', 0)
                        ->exists();
                    if ($existsElsewhere) {
                        return ['name' => $medicine->name, 'quantity' => 0, 'unit' => $medicine->unit];
                    }
                }

                if ($selectedWarehouseId) {
                    $quantity = $medicine->stocks()->where('warehouse_id', $selectedWarehouseId)->sum('quantity');
                } else {
                    $quantity = $medicine->totalStock;
                }

                return [
                    'name' => $medicine->name,
                    'quantity' => $quantity,
                    'unit' => $medicine->unit
                ];
            })
            ->filter(function ($item) {
                return $item['quantity'] > 0; // Only show medicines with stock
            })
            ->sortByDesc('quantity')
            ->take(10) // Show top 10 medicines
            ->values();
    }

    /**
     * Clear due amount for a medicine distribution.
     */
    public function clearDue(Request $request, $id)
    {
        $distribution = MedicineDistribution::findOrFail($id);

        // Ensure due amount is greater than 0
        if ($distribution->due_amount <= 0) {
            return back()->with('error', 'No due amount to clear.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $distribution->due_amount,
            'payment_method' => 'required|string|in:cash,upi,card',
            'notes' => 'nullable|string'
        ]);

        $amount = $validated['amount'];

        $distribution->amount_paid += $amount;
        $distribution->due_amount = max(0, $distribution->due_amount - $amount);

        $distribution->save();

        return back()->with('success', 'Due amount updated successfully.');
    }

    // --- Private Helper Methods ---

    /**
     * Calculate total inventory value.
     */
    private function calculateTotalValue($stocks)
    {
        $totalValue = 0;
        foreach ($stocks as $stock) {
            if ($stock->quantity > 0 && $stock->medicine) {
                $unitPrice = ($stock->medicine->market_price > 0 && $stock->medicine->market_price_unit_count > 0)
                    ? ($stock->medicine->market_price / $stock->medicine->market_price_unit_count)
                    : 0;
                $totalValue += $unitPrice * $stock->quantity;
            }
        }
        return $totalValue;
    }

    private function calculateStockHealth($stocks)
    {
        $lowStockCount = 0;
        $nearExpiryCount = 0;
        $expiredCount = 0;
        $threeMonthsFromNow = now()->addMonths(3);
        $medicineStockSum = [];
        foreach ($stocks as $stock) {
            if ($stock->expiry_date->isPast()) {
                $expiredCount++;
            } elseif ($stock->expiry_date->lte($threeMonthsFromNow)) {
                $nearExpiryCount++;
            }
            if (!isset($medicineStockSum[$stock->medicine_id])) {
                $medicineStockSum[$stock->medicine_id] = [
                    'total_qty' => 0,
                    'min_level' => $stock->medicine->min_stock_level ?? 0
                ];
            }
            $medicineStockSum[$stock->medicine_id]['total_qty'] += $stock->quantity;
        }
        foreach ($medicineStockSum as $data) {
            if ($data['total_qty'] <= $data['min_level']) {
                $lowStockCount++;
            }
        }
        return compact('lowStockCount', 'nearExpiryCount', 'expiredCount');
    }

    private function prepareCategoryChart($stocks)
    {
        $categoryDataRaw = [];
        foreach ($stocks as $stock) {
            if ($stock->medicine && $stock->medicine->category) {
                $catName = $stock->medicine->category->name;
                if (!isset($categoryDataRaw[$catName])) {
                    $categoryDataRaw[$catName] = 0;
                }
                $unitPrice = ($stock->medicine->market_price > 0 && $stock->medicine->market_price_unit_count > 0)
                    ? ($stock->medicine->market_price / $stock->medicine->market_price_unit_count)
                    : 0;
                $categoryDataRaw[$catName] += $unitPrice * $stock->quantity;
            }
        }
        return collect($categoryDataRaw)
            ->map(function ($value, $key) {
                return ['name' => $key, 'value' => round($value, 2)];
            })->values();
    }

    private function prepareWarehouseChart($stocks, $selectedWarehouseId)
    {
        if ($selectedWarehouseId) {
            return [];
        }
        $warehouseDataRaw = [];
        foreach ($stocks as $stock) {
            $whName = $stock->warehouse->name ?? 'Unknown';
            if (!isset($warehouseDataRaw[$whName])) {
                $warehouseDataRaw[$whName] = 0;
            }
            $unitPrice = ($stock->medicine->market_price > 0 && $stock->medicine->market_price_unit_count > 0)
                ? ($stock->medicine->market_price / $stock->medicine->market_price_unit_count)
                : 0;
            $warehouseDataRaw[$whName] += $unitPrice * $stock->quantity;
        }
        return collect($warehouseDataRaw)
            ->map(function ($value, $key) {
                return ['name' => $key, 'value' => round($value, 2)];
            })->sortByDesc('value')->take(5)->values();
    }

    private function prepareTrendChart($selectedWarehouseId)
    {
        $dates = collect(range(29, 0))->map(function ($days) {
            return now()->subDays($days)->format('Y-m-d');
        });
        $transactions = InventoryTransaction::whereDate('created_at', '>=', now()->subDays(30))
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('warehouse_id', $selectedWarehouseId);
            })
            ->selectRaw('DATE(created_at) as date, type, COUNT(*) as count')
            ->groupBy('date', 'type')
            ->get();
        return [
            'labels' => $dates->map(function ($d) {
                return \Carbon\Carbon::parse($d)->format('M d');
            }),
            'in' => $dates->map(function ($date) use ($transactions) {
                return $transactions->where('date', $date)->where('type', 'in')->first()->count ?? 0;
            }),
            'out' => $dates->map(function ($date) use ($transactions) {
                return $transactions->where('date', $date)->where('type', 'out')->first()->count ?? 0;
            }),
            'dispense' => $dates->map(function ($date) use ($transactions) {
                return $transactions->where('date', $date)->where('type', 'dispense')->first()->count ?? 0;
            }),
        ];
    }

    private function getTopMovers($selectedWarehouseId)
    {
        $topMovers = MedicineDistributionItem::whereDate('created_at', '>=', now()->subDays(30))
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->whereHas('distribution.camp', function ($cq) use ($selectedWarehouseId) {
                    $cq->where('id', $selectedWarehouseId);
                });
            })
            ->select('medicine_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('medicine_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->with('medicine')
            ->get();
        $consumptionStats = $this->getConsumptionStats();
        $topMovers->each(function ($item) use ($consumptionStats, $selectedWarehouseId) {
            $avgDaily = ($consumptionStats[$item->medicine_id] ?? 0) / 30;
            $medicine = $item->medicine;
            if ($medicine) {
                if ($selectedWarehouseId) {
                    $stockQuery = $medicine->stocks()->where('warehouse_id', $selectedWarehouseId);
                    $currentStock = $stockQuery->sum('quantity');
                } else {
                    $currentStock = $medicine->totalStock;
                }
                $item->coverage_days = $avgDaily > 0 ? round($currentStock / $avgDaily) : 999;
            } else {
                $item->coverage_days = 0;
            }
        });
        return $topMovers;
    }

    private function getConsumptionStats()
    {
        return MedicineDistributionItem::whereDate('created_at', '>=', now()->subDays(30))
            ->select('medicine_id', DB::raw('SUM(quantity) as total_dispensed'))
            ->groupBy('medicine_id')
            ->pluck('total_dispensed', 'medicine_id')
            ->toArray();
    }

    private function getRecentActivity($selectedWarehouseId)
    {
        return InventoryTransaction::latest()
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('warehouse_id', $selectedWarehouseId);
            })
            ->take(5)
            ->with(['user.profile', 'stock.medicine'])
            ->get();
    }

    private function getMedicineValueChartData($selectedWarehouseId)
    {
        return InventoryStock::query()
            ->join('medicines', 'inventory_stocks.medicine_id', '=', 'medicines.id')
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('inventory_stocks.warehouse_id', $selectedWarehouseId);
            })
            ->selectRaw('medicines.name, SUM(inventory_stocks.quantity * (medicines.market_price / GREATEST(COALESCE(medicines.market_price_unit_count, 1), 1))) as value')
            ->where('inventory_stocks.quantity', '>', 0)
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('value')
            ->get();
    }

    private function getCategoryQtyChartData($stocks)
    {
        $categoryQtyDataRaw = [];
        foreach ($stocks as $stock) {
            if ($stock->medicine && $stock->medicine->category) {
                $catName = $stock->medicine->category->name;
                if (!isset($categoryQtyDataRaw[$catName])) {
                    $categoryQtyDataRaw[$catName] = 0;
                }
                $categoryQtyDataRaw[$catName] += $stock->quantity;
            }
        }
        return collect($categoryQtyDataRaw)
            ->map(function ($value, $key) {
                return ['name' => $key, 'value' => $value];
            })->sortByDesc('value')->take(5)->values();
    }

    private function getSponsorChartData($stocks)
    {
        $sponsorData = [];
        foreach ($stocks as $stock) {
            $sponsorName = $stock->sponsor->name ?? 'Direct Purchase/Unknown';
            if (!isset($sponsorData[$sponsorName])) {
                $sponsorData[$sponsorName] = 0;
            }
            $unitPrice = ($stock->medicine && $stock->medicine->market_price > 0 && $stock->medicine->market_price_unit_count > 0)
                ? ($stock->medicine->market_price / $stock->medicine->market_price_unit_count)
                : 0;
            $sponsorData[$sponsorName] += $unitPrice * $stock->quantity;
        }
        return collect($sponsorData)
            ->map(function ($value, $key) {
                return ['name' => $key, 'value' => round($value, 2)];
            })
            ->sortByDesc('value')
            ->take(5)
            ->values();
    }

    private function getExpiryBreakdown($stocks)
    {
        $expiryBreakdown = [
            '30_days' => 0,
            '60_days' => 0,
            '90_days' => 0,
        ];
        $now = now();
        $thirtyDays = now()->addDays(30);
        $sixtyDays = now()->addDays(60);
        $ninetyDays = now()->addDays(90);
        foreach ($stocks as $stock) {
            if ($stock->quantity > 0) {
                if ($stock->expiry_date->gt($now)) {
                    if ($stock->expiry_date->lte($thirtyDays)) {
                        $expiryBreakdown['30_days']++;
                    } elseif ($stock->expiry_date->lte($sixtyDays)) {
                        $expiryBreakdown['60_days']++;
                    } elseif ($stock->expiry_date->lte($ninetyDays)) {
                        $expiryBreakdown['90_days']++;
                    }
                }
            }
        }
        return $expiryBreakdown;
    }

    private function getRecentDues()
    {
        return MedicineDistribution::whereIn('payment_method', ['due', 'credit', 'later'])
            ->where('final_amount', '>', 0)
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();
    }

    private function attachCoverageDays($medicines, $selectedWarehouseId)
    {
        $consumptionStats = $this->getConsumptionStats();
        $medicines->each(function ($medicine) use ($consumptionStats, $selectedWarehouseId) {
            $avgDaily = ($consumptionStats[$medicine->id] ?? 0) / 30;
            $currentStock = $selectedWarehouseId
                ? $medicine->stocks()->where('warehouse_id', $selectedWarehouseId)->sum('quantity')
                : $medicine->totalStock;
            $medicine->coverage_days = $avgDaily > 0 ? round($currentStock / $avgDaily) : 999;
        });
        return $medicines;
    }

    private function getTodaySales($selectedWarehouseId)
    {
        return MedicineDistribution::whereDate('created_at', now())
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('camp_id', $selectedWarehouseId);
            })
            ->sum('final_amount');
    }

    private function getTodayPurchases($selectedWarehouseId)
    {
        $todayPurchasesRaw = InventoryTransaction::where('type', 'in')
            ->whereDate('created_at', now())
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('warehouse_id', $selectedWarehouseId);
            })
            ->with('stock.medicine')
            ->get();
        $todayPurchases = 0;
        foreach ($todayPurchasesRaw as $tx) {
            $med = $tx->stock->medicine;
            if ($med && $med->market_price_unit_count > 0) {
                $unitPrice = $med->market_price / $med->market_price_unit_count;
                $todayPurchases += $unitPrice * $tx->quantity;
            }
        }
        return $todayPurchases;
    }

    private function getPaymentMethods($selectedWarehouseId)
    {
        if (!Schema::hasColumn('medicine_distributions', 'payment_method')) {
            return [];
        }
        return MedicineDistribution::whereDate('created_at', now())
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('camp_id', $selectedWarehouseId);
            })
            ->select('payment_method', DB::raw('sum(final_amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();
    }

    private function getReceivables($selectedWarehouseId)
    {
        if (!Schema::hasColumn('medicine_distributions', 'payment_method')) {
            return 0;
        }
        return MedicineDistribution::whereIn('payment_method', ['credit', 'due', 'later'])
            ->when($selectedWarehouseId, function ($q) use ($selectedWarehouseId) {
                return $q->where('camp_id', $selectedWarehouseId);
            })
            ->sum('final_amount');
    }

    private function getDeadStockCount($selectedWarehouseId)
    {
        return Medicine::whereHas('stocks', function ($q) use ($selectedWarehouseId) {
            $q->where('quantity', '>', 0);
            if ($selectedWarehouseId) {
                $q->where('warehouse_id', $selectedWarehouseId);
            }
        })
            ->whereDoesntHave('distributionItems', function ($q) {
                $q->where('created_at', '>=', now()->subDays(90));
            })
            ->count();
    }
}
