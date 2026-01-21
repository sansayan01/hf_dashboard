<?php

namespace App\Http\Controllers;

use App\Models\InventoryStock;
use App\Models\InventoryTransaction;
use App\Models\Medicine;
use App\Models\Survey;
use App\Models\InventoryWarehouse;
use App\Models\InventorySponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display stock overview.
     */
    public function index(Request $request)
    {
        $query = InventoryStock::with(['medicine.category', 'warehouse'])
            ->where('quantity', '>', 0);

        if ($request->has('warehouse_id') && $request->warehouse_id != '') {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $stocks = $query->orderBy('expiry_date')->get();

        $lowStockMedicines = Medicine::with('category')
            ->get()
            ->filter(function ($medicine) {
                return $medicine->totalStock <= $medicine->min_stock_level;
            });

        $warehouses = InventoryWarehouse::where('is_active', true)->get();

        // Get medicine quantities for chart
        $medicineData = Medicine::with('category')
            ->get()
            ->map(function ($medicine) {
                return [
                    'name' => $medicine->name,
                    'quantity' => $medicine->totalStock,
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
                'batch_number' => $validated['batch_number'],
                'expiry_date' => $validated['expiry_date'],
                'quantity' => $validated['quantity'],
            ]);

            InventoryTransaction::create([
                'stock_id' => $stock->id,
                'warehouse_id' => $validated['warehouse_id'],
                'sponsor_id' => $validated['sponsor_id'] ?? null,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'user_id' => auth()->id(),
                'notes' => 'Stock Received',
            ]);
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Stock added successfully.');
    }

    /**
     * Display transaction history.
     */
    public function transactions()
    {
        $transactions = InventoryTransaction::with(['stock.medicine', 'user', 'patient', 'warehouse', 'sponsor'])
            ->latest()
            ->paginate(20);

        return view('inventory.transactions', compact('transactions'));
    }

    /**
     * Show dispense form.
     */
    public function dispense($patientId = null)
    {
        $patient = $patientId ? Survey::findOrFail($patientId) : null;
        $patients = Survey::orderBy('full_name')->get();
        $warehouses = InventoryWarehouse::where('is_active', true)->get();
        $medicines = Medicine::whereHas('stocks', function ($q) {
            $q->where('quantity', '>', 0);
        })->with([
                    'stocks' => function ($q) {
                        $q->where('quantity', '>', 0)->orderBy('expiry_date');
                    }
                ])->get();

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

                    foreach ($stocks as $stock) {
                        if ($qtyToDispense <= 0)
                            break;

                        $decrement = min($stock->quantity, $qtyToDispense);
                        $stock->decrement('quantity', $decrement);

                        InventoryTransaction::create([
                            'stock_id' => $stock->id,
                            'warehouse_id' => $validated['warehouse_id'],
                            'type' => 'dispense',
                            'quantity' => $decrement,
                            'user_id' => auth()->id(),
                            'patient_id' => $validated['patient_id'],
                            'notes' => $validated['notes'],
                        ]);

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
     */
    public function destroyTransaction(InventoryTransaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
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
     */
    public function updateTransaction(Request $request, InventoryTransaction $transaction)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($transaction, $validated) {
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
        $warehouses = InventoryWarehouse::where('is_active', true)->get();
        $medicines = Medicine::whereHas('stocks', function ($q) {
            $q->where('quantity', '>', 0);
        })->with([
                    'stocks' => function ($q) {
                        $q->where('quantity', '>', 0)->orderBy('expiry_date');
                    }
                ])->get();

        $preSelectedStock = null;
        if ($request->has('stock_id')) {
            $preSelectedStock = InventoryStock::with('medicine', 'warehouse')->find($request->stock_id);
        }

        return view('inventory.transfer', compact('warehouses', 'medicines', 'preSelectedStock'));
    }

    /**
     * Process stock transfer between warehouses.
     */
    public function processTransfer(Request $request)
    {
        // Check if transfer_all is enabled
        $transferAll = $request->input('transfer_all') == '1';
        // Check if multi-item transfer
        $hasItems = $request->has('items');

        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:inventory_warehouses,id',
            'to_warehouse_id' => 'required|exists:inventory_warehouses,id|different:from_warehouse_id',
            'transfer_all' => 'nullable',
            'items' => $hasItems ? 'required|array|min:1' : 'nullable',
            'items.*.stock_id' => $hasItems ? 'required|exists:inventory_stocks,id' : 'nullable',
            'items.*.quantity' => $hasItems ? 'required|integer|min:1' : 'nullable',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $transferAll, $hasItems) {
                if ($transferAll) {
                    // Transfer ALL stock from the source warehouse
                    $allStocks = InventoryStock::where('warehouse_id', $validated['from_warehouse_id'])
                        ->where('quantity', '>', 0)
                        ->get();

                    if ($allStocks->isEmpty()) {
                        throw new \Exception("No stock available to transfer from this location.");
                    }

                    foreach ($allStocks as $sourceStock) {
                        $transferQty = $sourceStock->quantity;

                        // Deduct from source
                        $sourceStock->decrement('quantity', $transferQty);

                        // Find or create destination stock
                        $destStock = InventoryStock::where('medicine_id', $sourceStock->medicine_id)
                            ->where('warehouse_id', $validated['to_warehouse_id'])
                            ->where('batch_number', $sourceStock->batch_number)
                            ->where('expiry_date', $sourceStock->expiry_date)
                            ->first();

                        if ($destStock) {
                            $destStock->increment('quantity', $transferQty);
                        } else {
                            $destStock = InventoryStock::create([
                                'medicine_id' => $sourceStock->medicine_id,
                                'warehouse_id' => $validated['to_warehouse_id'],
                                'batch_number' => $sourceStock->batch_number,
                                'expiry_date' => $sourceStock->expiry_date,
                                'quantity' => $transferQty,
                            ]);
                        }

                        // Log transactions
                        InventoryTransaction::create([
                            'stock_id' => $sourceStock->id,
                            'warehouse_id' => $validated['from_warehouse_id'],
                            'type' => 'out',
                            'quantity' => $transferQty,
                            'user_id' => auth()->id(),
                            'notes' => "Bulk transfer to " . InventoryWarehouse::find($validated['to_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);

                        InventoryTransaction::create([
                            'stock_id' => $destStock->id,
                            'warehouse_id' => $validated['to_warehouse_id'],
                            'type' => 'in',
                            'quantity' => $transferQty,
                            'user_id' => auth()->id(),
                            'notes' => "Bulk transfer from " . InventoryWarehouse::find($validated['from_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);
                    }

                    $message = "All stock (" . $allStocks->count() . " items) transferred successfully.";
                } elseif ($hasItems && isset($validated['items'])) {
                    // Multi-item transfer from warehouses
                    $itemCount = 0;

                    foreach ($validated['items'] as $item) {
                        $sourceStock = InventoryStock::findOrFail($item['stock_id']);

                        if ($sourceStock->warehouse_id != $validated['from_warehouse_id']) {
                            throw new \Exception("Stock does not belong to the source warehouse.");
                        }

                        if ($sourceStock->quantity < $item['quantity']) {
                            throw new \Exception("Insufficient stock for transfer (Stock ID: {$sourceStock->id}).");
                        }

                        // Deduct from source
                        $sourceStock->decrement('quantity', $item['quantity']);

                        // Find or create destination stock
                        $destStock = InventoryStock::where('medicine_id', $sourceStock->medicine_id)
                            ->where('warehouse_id', $validated['to_warehouse_id'])
                            ->where('batch_number', $sourceStock->batch_number)
                            ->where('expiry_date', $sourceStock->expiry_date)
                            ->first();

                        if ($destStock) {
                            $destStock->increment('quantity', $item['quantity']);
                        } else {
                            $destStock = InventoryStock::create([
                                'medicine_id' => $sourceStock->medicine_id,
                                'warehouse_id' => $validated['to_warehouse_id'],
                                'batch_number' => $sourceStock->batch_number,
                                'expiry_date' => $sourceStock->expiry_date,
                                'quantity' => $item['quantity'],
                            ]);
                        }

                        // Log transactions
                        InventoryTransaction::create([
                            'stock_id' => $sourceStock->id,
                            'warehouse_id' => $validated['from_warehouse_id'],
                            'type' => 'out',
                            'quantity' => $item['quantity'],
                            'user_id' => auth()->id(),
                            'notes' => "Multi-item transfer to " . InventoryWarehouse::find($validated['to_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);

                        InventoryTransaction::create([
                            'stock_id' => $destStock->id,
                            'warehouse_id' => $validated['to_warehouse_id'],
                            'type' => 'in',
                            'quantity' => $item['quantity'],
                            'user_id' => auth()->id(),
                            'notes' => "Multi-item transfer from " . InventoryWarehouse::find($validated['from_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                        ]);

                        $itemCount++;
                    }

                    $message = "Successfully transferred {$itemCount} item(s).";
                } else {
                    // Single stock transfer (existing logic)
                    $sourceStock = InventoryStock::findOrFail($validated['stock_id']);

                    if ($sourceStock->warehouse_id != $validated['from_warehouse_id']) {
                        throw new \Exception("Stock does not belong to the source warehouse.");
                    }

                    if ($sourceStock->quantity < $validated['quantity']) {
                        throw new \Exception("Insufficient stock for transfer.");
                    }

                    // 1. Deduct from source
                    $sourceStock->decrement('quantity', $validated['quantity']);

                    // 2. Find or create destination stock
                    $destStock = InventoryStock::where('medicine_id', $sourceStock->medicine_id)
                        ->where('warehouse_id', $validated['to_warehouse_id'])
                        ->where('batch_number', $sourceStock->batch_number)
                        ->where('expiry_date', $sourceStock->expiry_date)
                        ->first();

                    if ($destStock) {
                        $destStock->increment('quantity', $validated['quantity']);
                    } else {
                        $destStock = InventoryStock::create([
                            'medicine_id' => $sourceStock->medicine_id,
                            'warehouse_id' => $validated['to_warehouse_id'],
                            'batch_number' => $sourceStock->batch_number,
                            'expiry_date' => $sourceStock->expiry_date,
                            'quantity' => $validated['quantity'],
                        ]);
                    }

                    // 3. Log transactions
                    InventoryTransaction::create([
                        'stock_id' => $sourceStock->id,
                        'warehouse_id' => $validated['from_warehouse_id'],
                        'type' => 'out',
                        'quantity' => $validated['quantity'],
                        'user_id' => auth()->id(),
                        'notes' => "Transfer to " . InventoryWarehouse::find($validated['to_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                    ]);

                    InventoryTransaction::create([
                        'stock_id' => $destStock->id,
                        'warehouse_id' => $validated['to_warehouse_id'],
                        'type' => 'in',
                        'quantity' => $validated['quantity'],
                        'user_id' => auth()->id(),
                        'notes' => "Transfer from " . InventoryWarehouse::find($validated['from_warehouse_id'])->name . ". " . ($validated['notes'] ?? ''),
                    ]);

                    $message = 'Stock transferred successfully.';
                }
            });

            return redirect()->route('inventory.index')
                ->with('success', $message ?? 'Stock transferred successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
