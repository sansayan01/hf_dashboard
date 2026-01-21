<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryWarehouse;

class InventoryCampController extends Controller
{
    public function index()
    {
        $camps = InventoryWarehouse::where('type', InventoryWarehouse::TYPE_CAMP)->get();
        return view('inventory.camps.index', compact('camps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_warehouses,name',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['type'] = InventoryWarehouse::TYPE_CAMP;

        InventoryWarehouse::create($validated);

        return redirect()->route('inventory.camps.index')
            ->with('success', 'Camp created successfully.');
    }

    public function update(Request $request, InventoryWarehouse $camp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_warehouses,name,' . $camp->id,
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $camp->update($validated);

        return redirect()->route('inventory.camps.index')
            ->with('success', 'Camp updated successfully.');
    }

    public function destroy(InventoryWarehouse $camp)
    {
        if ($camp->stocks()->where('quantity', '>', 0)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete camp with active stock quantity.');
        }

        $camp->delete();
        return redirect()->route('inventory.camps.index')
            ->with('success', 'Camp deleted successfully.');
    }
}
