<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventorySponsor;

class InventorySponsorController extends Controller
{
    public function index()
    {
        $sponsors = InventorySponsor::all();
        return view('inventory.sponsors.index', compact('sponsors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        InventorySponsor::create($validated);

        return redirect()->route('inventory.sponsors.index')
            ->with('success', 'Sponsor created successfully.');
    }

    public function update(Request $request, InventorySponsor $sponsor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $sponsor->update($validated);

        return redirect()->route('inventory.sponsors.index')
            ->with('success', 'Sponsor updated successfully.');
    }

    public function destroy(InventorySponsor $sponsor)
    {
        if ($sponsor->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete sponsor with existing transactions.');
        }

        $sponsor->delete();
        return redirect()->route('inventory.sponsors.index')
            ->with('success', 'Sponsor deleted successfully.');
    }
}
