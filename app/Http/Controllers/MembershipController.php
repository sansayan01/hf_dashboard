<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\User;
use App\Services\IncentiveService;

class MembershipController extends Controller
{
    /**
     * Display a listing of premium (membership) patients.
     */
    public function index(Request $request)
    {
        $user = User::getEffectiveUser();

        // Get all members this user is allowed to see
        if ($user->designation === 'staff') {
            $allowedIds = Survey::pluck('created_by')->unique()->toArray();
        } else {
            $downline = $user->getAllDownline();
            $allowedIds = collect([$user])->merge($downline)->pluck('id')->toArray();
        }

        $query = Survey::with([
            'creator.profile' => function ($q) {
                // Ensure profile is loaded for stats if needed
            }
        ])
            ->where('is_member', true)
            ->whereIn('created_by', $allowedIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(20)->withQueryString();

        return view('membership.index', compact('patients'));
    }

    /**
     * Show the membership registration form or details for a specific patient.
     */
    public function show(Survey $patient)
    {
        // Check access
        $user = User::getEffectiveUser();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        if ($patient->is_member) {
            return view('membership.show', compact('patient'));
        }

        return view('membership.registration', compact('patient'));
    }

    /**
     * Register a patient as a member.
     */
    public function register(Request $request, Survey $patient)
    {
        // Check access
        $user = User::getEffectiveUser();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relative_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10|unique:surveys,phone_number,' . $patient->id,
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'aadhar_number' => 'required|string|size:12|unique:surveys,aadhar_number,' . $patient->id,
            'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'blood_group' => 'nullable|string|max:5',
            'district' => 'required|string|max:255',
            'block' => 'required|string|max:255',
            'gp' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'past_diseases' => 'nullable|string',
            'health_issue_category' => 'nullable|array',
            'health_issue_other' => 'nullable|string',
            'insurance_loan_req' => 'nullable|string',
            'membership_fee' => 'required|numeric',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'payment_screenshot' => 'required_without:coupon_code|nullable|image|max:5120',
            'coupon_code' => 'nullable|string|exists:coupon_codes,code',
        ]);

        // Fill the model with validated data first
        $patient->fill($validated);

        $couponUsed = false;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            $code = strtoupper(trim($request->coupon_code));
            $coupon = \App\Models\CouponCode::where('code', $code)->first();

            if ($coupon && $coupon->isValid('membership')) {
                $couponUsed = true;
                $patient->payment_method = 'Coupon: ' . $coupon->code;
                $patient->membership_fee = 0;
                $patient->discount_percentage = 0;
                $patient->discount_amount = 0;
                $patient->final_amount = 0;
                $patient->amount_paid = 0;
                $patient->due_amount = 0;
            } else {
                $errorMessage = $coupon ? $coupon->getValidationError('membership') : 'Invalid coupon code. Please check and try again.';
                return back()->withInput()->with('error', $errorMessage);
            }
        }

        // Handle Screenshot Upload (if not coupon)
        if (!$couponUsed && $request->hasFile('payment_screenshot')) {
            $path = $request->file('payment_screenshot')->store('payments', 'public');

            // AI Verification for UPI Payments
            if ($request->payment_method === 'UPI' || $request->payment_method === 'UPI (QR)') {
                $aiService = app(\App\Services\AIService::class);
                $expectedAmount = (float) ($request->amount_paid ?? $request->final_amount ?? $request->membership_fee);

                $verification = $aiService->verifyPaymentScreenshot(storage_path('app/public/' . $path), $expectedAmount);

                if (!$verification['success']) {
                    // Delete the screenshot if verification fails
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    return back()->withInput()->with('error', 'AI Payment Verification Failed: ' . $verification['message'] . '. Please ensure you uploaded a clear success screenshot of the ₹' . $expectedAmount . ' payment.');
                }

                // Store transaction ID if available
                if (!empty($verification['transaction_id'])) {
                    $patient->payment_method = $request->payment_method . ' (Ref: ' . $verification['transaction_id'] . ')';
                }
            }

            $patient->payment_screenshot = $path;
        }

        // Upgrade to member
        if ($couponUsed) {
            $coupon->markAsUsed(auth()->id());
        }
        $patient->is_member = true;

        // Generate and assign new Membership ID
        $patient->patient_id = Survey::generateMembershipId();

        $patient->save();

        // Automate Incentive and Attendance
        // Like medicine and pathology, the incentive goes to the RO (patient creator)
        app(IncentiveService::class)->applyIncentive($patient->creator, 'membership', $patient->final_amount);

        \App\Models\ActivityLog::logActivity(
            action: 'member_registered',
            description: "Patient upgraded to Member: {$patient->full_name} ({$patient->patient_id})",
            modelType: 'App\Models\Survey',
            modelId: $patient->id
        );

        return redirect()->route('membership.index')->with('success', 'Member registered successfully! Record moved to Membership section.');
    }

    /**
     * Cancel a patient's membership (Admin only).
     */
    public function cancel(Request $request, Survey $patient)
    {
        // Check access
        $user = User::getEffectiveUser();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Only administrators can cancel memberships.');
        }

        $patient->is_member = false;
        // Re-generate a Patient ID with the 'HFP' prefix
        $patient->patient_id = Survey::generatePatientId();
        $patient->save();

        // Create a placeholder appointment so they appear in the "Patient" section immediately
        \App\Models\Appointment::create([
            'survey_id' => $patient->id,
            'doctor_type' => 'Membership Cancellation Transfer',
            'location' => 'Registry Transfer',
            'appointment_date' => now()->toDateString(),
            'appointment_time' => now()->toTimeString(),
            'created_by' => auth()->id(),
            'status' => 'successful'
        ]);

        \App\Models\ActivityLog::logActivity(
            action: 'member_cancelled',
            description: "Membership cancelled for: {$patient->full_name} ({$patient->patient_id})",
            modelType: 'App\Models\Survey',
            modelId: $patient->id
        );

        return redirect()->route('patients.index')->with('success', 'Membership cancelled successfully! Record moved to Patients section.');
    }
}
