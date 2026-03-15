<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines.
     */
    public function index()
    {
        $medicines = Medicine::with('category', 'stocks')
            ->orderBy('name')
            ->get();
        return view('inventory.medicines.index', compact('medicines'));
    }

    /**
     * Export a CSV of all medicines.
     */
    public function export()
    {
        $medicines = Medicine::with('category', 'stocks')->orderBy('name')->get();

        $filename = "medicines_registry_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Name', 
            'Category', 
            'Generic Name', 
            'Unit', 
            'Dosage', 
            'Market Price', 
            'Market Price Unit Count', 
            'Price per Unit',
            'Units Per Box', 
            'Total Stock', 
            'Min Stock Level'
        ];

        $callback = function() use($medicines, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($medicines as $medicine) {
                $pricePerUnit = '';
                if ($medicine->market_price && $medicine->market_price_unit_count) {
                    $pricePerUnit = number_format($medicine->market_price / $medicine->market_price_unit_count, 2);
                }

                fputcsv($file, [
                    $medicine->name,
                    $medicine->category ? $medicine->category->name : 'Uncategorized',
                    $medicine->generic_name,
                    $medicine->unit,
                    $medicine->dosage,
                    $medicine->market_price,
                    $medicine->market_price_unit_count,
                    $pricePerUnit,
                    $medicine->units_per_box,
                    $medicine->totalStock, // Dynamic accessor
                    $medicine->min_stock_level,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        $categories = MedicineCategory::orderBy('name')->get();
        return view('inventory.medicines.create', compact('categories'));
    }

    /**
     * Store a newly created medicine in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:medicine_categories,id',
            'unit' => 'required|string|max:50',
            'dosage' => 'nullable|string|max:100',
            'market_price' => 'nullable|numeric|min:0',
            'market_price_unit_count' => 'nullable|integer|min:1',
            'units_per_box' => 'nullable|integer|min:1',
        ]);

        Medicine::create($validated);

        return redirect()->route('inventory.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::orderBy('name')->get();
        return view('inventory.medicines.edit', compact('medicine', 'categories'));
    }

    /**
     * Update the specified medicine in storage.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:medicine_categories,id',
            'unit' => 'required|string|max:50',
            'dosage' => 'nullable|string|max:100',
            'market_price' => 'nullable|numeric|min:0',
            'market_price_unit_count' => 'nullable|integer|min:1',
            'units_per_box' => 'nullable|integer|min:1',
        ]);

        $medicine->update($validated);

        return redirect()->route('inventory.medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    /**
     * Remove the specified medicine from storage.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('inventory.medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    // --- Category Management ---

    public function categoriesIndex()
    {
        $categories = MedicineCategory::withCount('medicines')->orderBy('name')->get();
        return view('inventory.categories.index', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:medicine_categories,name',
            'description' => 'nullable|string',
        ]);

        MedicineCategory::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function categoriesUpdate(Request $request, MedicineCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:medicine_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function categoriesDestroy(MedicineCategory $category)
    {
        if ($category->medicines()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated medicines.');
        }

        $category->delete();
        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
