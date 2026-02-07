<?php

namespace App\Http\Controllers;

use App\Models\InventoryStock;
use App\Models\InventoryTransaction;
use App\Models\InventoryWarehouse;
use App\Models\Medicine;
use App\Models\MedicineDistribution;
use App\Models\MedicineDistributionItem;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MedicineDistributionController extends Controller
{
    public function create(Survey $patient)
    {
        $user = Auth::user();

        // Load the patient's creator (RO) for display
        $patient->load('creator.profile');

        // Pharmacists and Office In-Charges can only see their assigned camp
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $camps = InventoryWarehouse::where('id', $user->camp_id)
                ->where('type', InventoryWarehouse::TYPE_CAMP)
                ->where('is_active', true)
                ->get();
        } else {
            // For other users, show all active camps
            $camps = InventoryWarehouse::where('type', InventoryWarehouse::TYPE_CAMP)
                ->where('is_active', true)
                ->get();
        }

        return view('medicine.distribute', compact('patient', 'camps'));
    }

    public function searchMedicine(Request $request)
    {
        $search = $request->query('q');
        $campId = $request->query('camp_id');

        if (!$search || !$campId) {
            return response()->json([]);
        }

        // Search medicines that have stock in the selected camp
        $medicines = Medicine::with([
            'stocks' => function ($q) use ($campId) {
                $q->where('warehouse_id', $campId)
                    ->where('quantity', '>', 0);
            }
        ])
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%");
            })
            ->whereHas('stocks', function ($q) use ($campId) {
                $q->where('warehouse_id', $campId)
                    ->where('quantity', '>', 0);
            })
            ->limit(20)
            ->get()
            ->map(function ($medicine) use ($campId) {
                // Calculate total stock for this camp precisely
                $availableStock = (int) $medicine->stocks
                    ->where('warehouse_id', $campId)
                    ->where('quantity', '>', 0)
                    ->sum('quantity');

                // Calculate price per unit (e.g., per tablet)
                $unitPrice = $medicine->market_price;
                if ($medicine->market_price_unit_count > 1) {
                    $unitPrice = $medicine->market_price / $medicine->market_price_unit_count;
                }

                return [
                    'id' => $medicine->id,
                    'text' => $medicine->name . ($medicine->generic_name ? ' [' . $medicine->generic_name . ']' : '') . ' (' . ($medicine->dosage ?? $medicine->unit) . ') - Stock: ' . $availableStock,
                    'market_price' => number_format($unitPrice, 2, '.', ''),
                    'available_stock' => $availableStock
                ];
            });

        return response()->json($medicines);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:surveys,id',
            'camp_id' => 'required|exists:inventory_warehouses,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $campId = $validated['camp_id'];

        try {
            DB::beginTransaction();

            $distribution = new MedicineDistribution();
            $distribution->patient_id = $validated['patient_id'];
            $distribution->camp_id = $campId;
            $distribution->pharmacist_id = $user->id;
            $distribution->total_amount = 0; // Will update after calculating items
            $distribution->save();

            $totalAmount = 0;

            foreach ($validated['medicines'] as $item) {
                $medicine = Medicine::findOrFail($item['id']);
                $quantityToDispense = $item['quantity'];

                // Check Stock
                $availableStock = InventoryStock::where('warehouse_id', $campId)
                    ->where('medicine_id', $medicine->id)
                    ->sum('quantity');

                if ($availableStock < $quantityToDispense) {
                    throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$availableStock}, Requested: {$quantityToDispense}");
                }

                // Deduct Stock (FIFO - Earliest Expiry First)
                $stocks = InventoryStock::where('warehouse_id', $campId)
                    ->where('medicine_id', $medicine->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                $remainingToDeduct = $quantityToDispense;

                foreach ($stocks as $stock) {
                    /** @var InventoryStock $stock */
                    if ($remainingToDeduct <= 0)
                        break;

                    $deduct = min($stock->quantity, $remainingToDeduct);
                    $stock->decrement('quantity', $deduct);

                    $remainingToDeduct -= $deduct;

                    // Record transaction for the log
                    $transactionData = [
                        'stock_id' => $stock->id,
                        'type' => 'dispense',
                        'quantity' => $deduct,
                        'user_id' => $user->id,
                        'patient_id' => $validated['patient_id'],
                        'notes' => 'Dispensed via Distribution #' . $distribution->id,
                    ];

                    // Safely add columns that might be missing in older schemas
                    if (Schema::hasColumn('inventory_transactions', 'warehouse_id')) {
                        $transactionData['warehouse_id'] = $campId;
                    }
                    if (Schema::hasColumn('inventory_transactions', 'sponsor_id')) {
                        $transactionData['sponsor_id'] = $stock->sponsor_id;
                    }
                    if (Schema::hasColumn('inventory_transactions', 'distribution_id')) {
                        $transactionData['distribution_id'] = $distribution->id;
                    }

                    InventoryTransaction::create($transactionData);
                }

                $price = $medicine->market_price ?? 0;
                if (($medicine->market_price_unit_count ?? 1) > 1) {
                    $price = $medicine->market_price / $medicine->market_price_unit_count;
                }

                $lineTotal = round($price * $quantityToDispense, 2);

                $distItem = new MedicineDistributionItem();
                $distItem->distribution_id = $distribution->id;
                $distItem->medicine_id = $medicine->id;
                $distItem->quantity = $quantityToDispense;
                $distItem->unit_price = $price;
                $distItem->total_price = $lineTotal;
                $distItem->save();

                $totalAmount += $lineTotal;
            }

            $discountPercentage = $request->has('discount_percentage') && $request->discount_percentage !== null
                ? (float) $request->discount_percentage
                : ($totalAmount > 300 ? 20 : 18);

            $discountAmount = round(($totalAmount * $discountPercentage) / 100, 2);
            $finalAmount = $totalAmount - $discountAmount;

            $distribution->total_amount = $totalAmount;
            $distribution->discount_percentage = $discountPercentage;
            $distribution->discount_amount = $discountAmount;
            $distribution->final_amount = $finalAmount;
            $distribution->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('medicine.invoice', $distribution->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $distribution = MedicineDistribution::with(['patient', 'items.medicine', 'camp', 'pharmacist'])->findOrFail($id);
        return view('medicine.invoice', compact('distribution'));
    }

    /**
     * Show the edit form for a distribution
     */
    public function edit($id)
    {
        $distribution = MedicineDistribution::with(['patient.creator.profile', 'items.medicine', 'camp', 'pharmacist'])->findOrFail($id);

        // Get current stock for medicines in this camp (for display purposes)
        $stocks = [];
        $medicineIds = $distribution->items->pluck('medicine_id')->toArray();
        $stockRecords = InventoryStock::where('warehouse_id', $distribution->camp_id)
            ->whereIn('medicine_id', $medicineIds)
            ->selectRaw('medicine_id, SUM(quantity) as total_stock')
            ->groupBy('medicine_id')
            ->get();

        foreach ($stockRecords as $record) {
            $stocks[$record->medicine_id] = $record->total_stock;
        }

        return view('medicine.edit', compact('distribution', 'stocks'));
    }

    /**
     * Update a medicine distribution record
     * Handles adding new items, removing existing items, and updating quantities
     */
    public function update(Request $request, $id)
    {
        $distribution = MedicineDistribution::with('items')->findOrFail($id);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.item_id' => 'nullable',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $processedMedicineIds = [];

            foreach ($validated['items'] as $itemData) {
                $medicineId = $itemData['medicine_id'];
                $newQuantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];

                $processedMedicineIds[] = $medicineId;

                // Check if this is an existing item or a new one
                /** @var MedicineDistributionItem|null $existingItem */
                $existingItem = $distribution->items->where('medicine_id', $medicineId)->first();

                if ($existingItem) {
                    // Update existing item
                    $oldQuantity = $existingItem->quantity;
                    $quantityDiff = $newQuantity - $oldQuantity;

                    if ($quantityDiff > 0) {
                        // Need more stock - check availability
                        $availableStock = InventoryStock::where('warehouse_id', $distribution->camp_id)
                            ->where('medicine_id', $medicineId)
                            ->sum('quantity');

                        if ($availableStock < $quantityDiff) {
                            $medicine = Medicine::find($medicineId);
                            throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$availableStock}, Requested additional: {$quantityDiff}");
                        }

                        // Deduct additional stock (FIFO)
                        $stocks = InventoryStock::where('warehouse_id', $distribution->camp_id)
                            ->where('medicine_id', $medicineId)
                            ->where('quantity', '>', 0)
                            ->orderBy('expiry_date', 'asc')
                            ->get();

                        $remainingToDeduct = $quantityDiff;
                        /** @var InventoryStock $stock */
                        foreach ($stocks as $stock) {
                            if ($remainingToDeduct <= 0)
                                break;
                            $deduct = min($stock->quantity, $remainingToDeduct);
                            $stock->decrement('quantity', $deduct);
                            $remainingToDeduct -= $deduct;
                        }
                    } elseif ($quantityDiff < 0) {
                        // Return stock
                        /** @var InventoryStock $stock */
                        $stock = InventoryStock::where('warehouse_id', $distribution->camp_id)
                            ->where('medicine_id', $medicineId)
                            ->first();
                        if ($stock) {
                            $stock->increment('quantity', abs($quantityDiff));
                        }
                    }

                    $existingItem->quantity = $newQuantity;
                    $existingItem->total_price = round($unitPrice * $newQuantity, 2);
                    $existingItem->save();

                    $totalAmount += $existingItem->total_price;
                } else {
                    // Add new item
                    $medicine = Medicine::findOrFail($medicineId);

                    // Check stock availability
                    $availableStock = InventoryStock::where('warehouse_id', $distribution->camp_id)
                        ->where('medicine_id', $medicineId)
                        ->sum('quantity');

                    if ($availableStock < $newQuantity) {
                        throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$availableStock}, Requested: {$newQuantity}");
                    }

                    // Deduct stock (FIFO)
                    $stocks = InventoryStock::where('warehouse_id', $distribution->camp_id)
                        ->where('medicine_id', $medicineId)
                        ->where('quantity', '>', 0)
                        ->orderBy('expiry_date', 'asc')
                        ->get();

                    $remainingToDeduct = $newQuantity;
                    /** @var InventoryStock $stock */
                    foreach ($stocks as $stock) {
                        if ($remainingToDeduct <= 0)
                            break;
                        $deduct = min($stock->quantity, $remainingToDeduct);
                        $stock->decrement('quantity', $deduct);

                        // Create inventory transaction for tracking
                        InventoryTransaction::create([
                            'stock_id' => $stock->id,
                            'warehouse_id' => $distribution->camp_id,
                            'type' => 'dispense',
                            'quantity' => $deduct,
                            'user_id' => Auth::id(),
                            'patient_id' => $distribution->patient_id,
                            'distribution_id' => $distribution->id,
                            'notes' => 'Distribution #' . $distribution->id . ' (edited)',
                            'sponsor_id' => $stock->sponsor_id,
                        ]);

                        $remainingToDeduct -= $deduct;
                    }

                    // Create new distribution item
                    $lineTotal = round($unitPrice * $newQuantity, 2);
                    $newItem = new MedicineDistributionItem();
                    $newItem->distribution_id = $distribution->id;
                    $newItem->medicine_id = $medicineId;
                    $newItem->quantity = $newQuantity;
                    $newItem->unit_price = $unitPrice;
                    $newItem->total_price = $lineTotal;
                    $newItem->save();

                    $totalAmount += $lineTotal;
                }
            }

            // Remove items that were deleted (not in the submitted list)
            $itemsToRemove = $distribution->items->whereNotIn('medicine_id', $processedMedicineIds);
            foreach ($itemsToRemove as $itemToRemove) {
                // Return stock for removed items
                /** @var InventoryStock $stock */
                $stock = InventoryStock::where('warehouse_id', $distribution->camp_id)
                    ->where('medicine_id', $itemToRemove->medicine_id)
                    ->first();
                if ($stock) {
                    $stock->increment('quantity', $itemToRemove->quantity);
                }

                // Delete related inventory transactions
                InventoryTransaction::where('distribution_id', $distribution->id)
                    ->whereHas('stock', function ($q) use ($itemToRemove) {
                        $q->where('medicine_id', $itemToRemove->medicine_id);
                    })
                    ->delete();

                $itemToRemove->delete();
            }

            // Recalculate distribution totals
            $discountPercentage = $request->has('discount_percentage') && $request->discount_percentage !== null
                ? (float) $request->discount_percentage
                : ($totalAmount > 300 ? 20 : 18);

            $discountAmount = round(($totalAmount * $discountPercentage) / 100, 2);
            $finalAmount = $totalAmount - $discountAmount;

            $distribution->total_amount = $totalAmount;
            $distribution->discount_percentage = $discountPercentage;
            $distribution->discount_amount = $discountAmount;
            $distribution->final_amount = $finalAmount;
            $distribution->save();

            DB::commit();

            return redirect()->route('inventory.transactions', ['view' => 'dispenses'])
                ->with('success', 'Distribution updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a medicine distribution and revert stock
     */
    public function destroy($id)
    {
        $distribution = MedicineDistribution::with('items')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Revert stock for each item
            foreach ($distribution->items as $item) {
                // Find stock record for this medicine in the camp
                $stock = InventoryStock::where('warehouse_id', $distribution->camp_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                if ($stock) {
                    $stock->increment('quantity', $item->quantity);
                } else {
                    // If no stock record exists, create one
                    InventoryStock::create([
                        'warehouse_id' => $distribution->camp_id,
                        'medicine_id' => $item->medicine_id,
                        'quantity' => $item->quantity,
                        'batch_number' => 'RESTORED-' . $distribution->id,
                        'expiry_date' => now()->addYear(),
                    ]);
                }
            }

            // Delete related inventory transactions
            InventoryTransaction::where('notes', 'like', '%Distribution #' . $distribution->id . '%')
                ->orWhere('distribution_id', $distribution->id)
                ->delete();

            // Delete distribution items
            $distribution->items()->delete();

            // Delete distribution
            $distribution->delete();

            DB::commit();

            return redirect()->route('inventory.transactions', ['view' => 'dispenses'])
                ->with('success', 'Distribution deleted and stock reverted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete distribution: ' . $e->getMessage());
        }
    }
}
