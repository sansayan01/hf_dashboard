<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of coupon codes
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Only Super Admin can manage coupons
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can manage coupon codes.');
        }

        // Automatic cleanup of used coupons older than 7 days
        \Illuminate\Support\Facades\Artisan::call('hf:cleanup-coupons');

        $query = CouponCode::with(['generatedBy.profile', 'usedBy.profile']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->used();
            } elseif ($request->status === 'unused') {
                $query->unused();
            }
        }

        if ($request->filled('designation')) {
            if ($request->designation === 'any') {
                $query->whereNull('designation');
            } else {
                $query->where('designation', $request->designation);
            }
        }

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $coupons = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total' => CouponCode::count(),
            'unused' => CouponCode::unused()->count(),
            'used' => CouponCode::used()->count(),
            'expired' => CouponCode::unused()->where('expires_at', '<', now())->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating new coupons
     */
    public function create()
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.coupons.create');
    }

    /**
     * Store newly generated coupons
     */
    public function store(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'designation' => 'required|in:dm,bm,rm,ro,membership,any',
            'quantity' => 'required|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        // Convert "any" to null for universal coupons
        $designation = ($validated['designation'] === 'any') ? null : $validated['designation'];

        try {
            $coupons = CouponCode::generateBatch(
                $validated['quantity'],
                $designation,
                $currentUser->id,
                $validated['expires_at'] ?? null,
                $validated['notes'] ?? null
            );

            return redirect()->route('coupons.index')
                ->with('success', "Successfully generated {$validated['quantity']} coupon codes!")
                ->with('generated_codes', $coupons);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to generate coupons: ' . $e->getMessage());
        }
    }

    /**
     * Delete an unused coupon
     */
    public function destroy($id)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $coupon = CouponCode::findOrFail($id);

        if ($coupon->is_used) {
            return back()->with('error', 'Cannot delete a used coupon code.');
        }

        $coupon->delete();

        return back()->with('success', 'Coupon code deleted successfully.');
    }

    /**
     * AJAX endpoint to validate coupon code
     */
    public function validateAjax(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'designation' => 'required|in:dm,bm,rm,ro,membership',
        ]);

        $coupon = CouponCode::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code. Please check and try again.',
            ], 404);
        }

        if (!$coupon->isValid($request->designation)) {
            return response()->json([
                'valid' => false,
                'message' => $coupon->getValidationError($request->designation),
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Coupon code is valid! Payment waived.',
            'discount_percentage' => $coupon->discount_percentage,
            'original_amount' => $coupon->original_amount,
        ]);
    }

    /**
     * Export coupons to CSV
     */
    public function export(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $query = CouponCode::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->used();
            } elseif ($request->status === 'unused') {
                $query->unused();
            }
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        $coupons = $query->get();

        $filename = 'coupon_codes_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($coupons) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Code', 'Designation', 'Amount', 'Status', 'Used By', 'Used At', 'Expires At', 'Notes']);

            // Data rows
            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->code,
                    $coupon->designation ?? 'Any',
                    $coupon->original_amount ? '₹' . $coupon->original_amount : 'N/A',
                    $coupon->is_used ? 'Used' : 'Unused',
                    $coupon->usedBy ? $coupon->usedBy->profile->full_name : '',
                    $coupon->used_at ? $coupon->used_at->format('Y-m-d H:i:s') : '',
                    $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'Never',
                    $coupon->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
