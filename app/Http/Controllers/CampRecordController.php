<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CampRecord;

class CampRecordController extends Controller
{
    private function authorizeSuperAdmin()
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can access the finances section.');
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();
        $records = CampRecord::latest()->get();
        return view('camp_records.index', compact('records'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('camp_records.create');
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'camp_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'rm' => 'nullable|string|max:255',
            'date' => 'required|date',
            'patients_count' => 'nullable|integer',
            'medicine_mrp' => 'nullable|numeric',
            'medicine_discount' => 'nullable|numeric',
            'billing_price' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'doctor_name' => 'nullable|string|max:255',
            'pathologist' => 'nullable|string|max:255',
            'pharmacists_name' => 'nullable|string|max:255',
            'expenses' => 'nullable|numeric',
            'net_profit_loss' => 'nullable|numeric',
        ]);

        CampRecord::create($validated);

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record created successfully.');
    }

    public function edit(CampRecord $campRecord)
    {
        $this->authorizeSuperAdmin();
        return view('camp_records.edit', compact('campRecord'));
    }

    public function update(Request $request, CampRecord $campRecord)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'camp_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'rm' => 'nullable|string|max:255',
            'date' => 'required|date',
            'patients_count' => 'nullable|integer',
            'medicine_mrp' => 'nullable|numeric',
            'medicine_discount' => 'nullable|numeric',
            'billing_price' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'doctor_name' => 'nullable|string|max:255',
            'pathologist' => 'nullable|string|max:255',
            'pharmacists_name' => 'nullable|string|max:255',
            'expenses' => 'nullable|numeric',
            'net_profit_loss' => 'nullable|numeric',
        ]);

        $campRecord->update($validated);

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record updated successfully.');
    }

    public function destroy(CampRecord $campRecord)
    {
        $this->authorizeSuperAdmin();
        $campRecord->delete();

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record deleted successfully.');
    }
}
