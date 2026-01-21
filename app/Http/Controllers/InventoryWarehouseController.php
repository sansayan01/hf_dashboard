<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryWarehouse;

class InventoryWarehouseController extends Controller
{
    public function index()
    {
        $warehouses = InventoryWarehouse::where('type', InventoryWarehouse::TYPE_WAREHOUSE)->get();
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_warehouses,name',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['type'] = InventoryWarehouse::TYPE_WAREHOUSE;

        InventoryWarehouse::create($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    public function update(Request $request, InventoryWarehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_warehouses,name,' . $warehouse->id,
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $warehouse->update($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(InventoryWarehouse $warehouse)
    {
        if ($warehouse->stocks()->where('quantity', '>', 0)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete warehouse with active stock quantity.');
        }

        $warehouse->delete();
        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}
