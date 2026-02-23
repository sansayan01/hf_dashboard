<?php

namespace App\Http\Controllers;

use App\Models\InventoryWarehouse;
use App\Models\PathologyTest;
use App\Models\Survey;
use App\Models\User;
use App\Services\IncentiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PathologyTestController extends Controller
{
    public function create(Survey $patient)
    {
        $user = Auth::user();

        // Load the patient's creator (RO) for display and incentive
        $patient->load('creator.profile');

        // Fetch camps - same logic as MedicineDistributionController
        if (($user->designation === 'staff' || $user->isOfficeInCharge()) && $user->camp_id) {
            $camps = InventoryWarehouse::where('id', $user->camp_id)
                ->where('type', InventoryWarehouse::TYPE_CAMP)
                ->where('is_active', true)
                ->get();
        } else {
            $camps = InventoryWarehouse::where('type', InventoryWarehouse::TYPE_CAMP)
                ->where('is_active', true)
                ->get();
        }

        return view('pathology.create', compact('patient', 'camps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:surveys,id',
            'camp_id' => 'required|exists:inventory_warehouses,id',
            'test_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'nullable|in:cash,upi',
            'date' => 'required|date',
        ]);

        $patient = Survey::findOrFail($validated['patient_id']);
        $user = Auth::user();

        // The incentive goes to the person who registered the patient (RO)
        $ro = $patient->creator;

        try {
            DB::beginTransaction();

            $amount = (float) $validated['amount'];
            $discountPercentage = isset($validated['discount_percentage']) ? (float) $validated['discount_percentage'] : 0;
            $discountAmount = ($amount * $discountPercentage) / 100;
            $finalAmount = $amount - $discountAmount;

            $amountPaid = (float) ($request->amount_paid ?? $finalAmount);
            $dueAmount = $finalAmount - $amountPaid;

            $pathologyTest = new PathologyTest();
            $pathologyTest->patient_id = $patient->id;
            $pathologyTest->camp_id = $validated['camp_id'];
            $pathologyTest->test_name = $validated['test_name'];
            $pathologyTest->amount = $amount;
            $pathologyTest->discount_percentage = $discountPercentage;
            $pathologyTest->discount_amount = $discountAmount;
            $pathologyTest->final_amount = $finalAmount;
            $pathologyTest->payment_method = $validated['payment_method'] ?? 'cash';
            $pathologyTest->amount_paid = $amountPaid;
            $pathologyTest->due_amount = $dueAmount > 0 ? $dueAmount : 0;
            $pathologyTest->created_by = $user->id;
            $pathologyTest->ro_id = $ro->id;
            $pathologyTest->date = $validated['date'];
            $pathologyTest->save();

            DB::commit();

            // Apply incentive to the RO (Relationship Officer)
            // The category 'pathology' is already handled in IncentiveService
            app(IncentiveService::class)->applyIncentive($ro, 'pathology', $finalAmount);

            return response()->json([
                'success' => true,
                'message' => 'Pathology test recorded successfully!',
                'redirect_url' => route('patients.show', $patient->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(PathologyTest $pathologyTest)
    {
        try {
            $patientId = $pathologyTest->patient_id;
            $pathologyTest->delete();
            return redirect()->route('patients.show', $patientId)->with('success', 'Pathology test record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete record: ' . $e->getMessage());
        }
    }
}
