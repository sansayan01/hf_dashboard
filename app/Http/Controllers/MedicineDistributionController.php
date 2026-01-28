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

class MedicineDistributionController extends Controller
{
    public function create(Survey $patient)
    {
        $user = Auth::user();

        // Pharmacists can only see their assigned camp
        if ($user->designation === 'staff' && $user->camp_id) {
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
        $medicines = Medicine::where(function ($q) use ($search) {
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
                $availableStock = (int) $medicine->stocks()
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
                    if ($remainingToDeduct <= 0)
                        break;

                    $deduct = min($stock->quantity, $remainingToDeduct);
                    $stock->quantity -= $deduct;
                    $stock->save();

                    $remainingToDeduct -= $deduct;

                    // Record transaction for the log
                    InventoryTransaction::create([
                        'stock_id' => $stock->id,
                        'warehouse_id' => $campId,
                        'sponsor_id' => $stock->sponsor_id,
                        'type' => 'dispense',
                        'quantity' => $deduct,
                        'user_id' => $user->id,
                        'patient_id' => $validated['patient_id'],
                        'distribution_id' => $distribution->id,
                        'notes' => 'Dispensed via Distribution #' . $distribution->id,
                    ]);
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

            $discountPercentage = $totalAmount > 300 ? 20 : 18;
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
}
