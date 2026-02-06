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

class InventoryController extends Controller
{
    /**
     * Display stock overview.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedWarehouseId = null;

        // Determine the active warehouse filter
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $selectedWarehouseId = $user->camp_id;
        } elseif ($request->has('warehouse_id') && $request->warehouse_id != '') {
            $selectedWarehouseId = $request->warehouse_id;
        }

        // Retroactive fix: Ensure all active stocks have a sponsor_id
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

        // Query for stocks
        $query = InventoryStock::with(['medicine.category', 'warehouse', 'sponsor'])
            ->where('quantity', '>', 0);

        if ($selectedWarehouseId) {
            $query->where('warehouse_id', $selectedWarehouseId);

            // "Only available at" requirement: Filter for medicines that exist ONLY in the selected warehouse
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

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('medicine', function ($mq) use ($search) {
                    $mq->where('name', 'like', '%' . $search . '%')
                        ->orWhere('generic_name', 'like', '%' . $search . '%');
                })->orWhere('batch_number', 'like', '%' . $search . '%');
            });
        }

        $stocks = $query->orderBy('expiry_date')->get();

        // Low stock medicines - filter by selected warehouse if applicable
        $lowStockQuery = Medicine::with('category');
        $lowStockMedicines = $lowStockQuery->get()->filter(function ($medicine) use ($selectedWarehouseId, $request) {
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

        // Warehouses - pharmacists/OICs only see their camp
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $warehouses = InventoryWarehouse::where('id', $user->camp_id)->where('is_active', true)->get();
        } else {
            $warehouses = InventoryWarehouse::where('is_active', true)->get();
        }

        // Get medicine quantities for chart - respect selected warehouse and exclusivity
        $medicineData = Medicine::with('category')
            ->get()
            ->map(function ($medicine) use ($selectedWarehouseId, $request) {
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

        return view('inventory.index', compact('stocks', 'lowStockMedicines', 'warehouses', 'medicineData'));
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
        $user = auth()->user();
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
}
