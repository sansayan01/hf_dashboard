<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class MembershipController extends Controller
{
    /**
     * Display a listing of premium (membership) patients.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

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
    public function show($id)
    {
        $patient = Survey::findOrFail($id);

        // Check access
        $user = auth()->user();
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
    public function register(Request $request, $id)
    {
        $patient = Survey::findOrFail($id);

        // Check access
        $user = auth()->user();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relative_name' => 'nullable|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'aadhar_number' => 'nullable|string|size:12',
            'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'blood_group' => 'nullable|string|max:5',
            'district' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'gp' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'past_diseases' => 'nullable|string',
            'health_issue_category' => 'nullable|array',
            'health_issue_other' => 'nullable|string',
            'insurance_loan_req' => 'nullable|string',
        ]);

        // Combine health issues
        $healthIssuesArr = $validated['health_issue_category'] ?? [];
        $healthIssuesArr = array_filter($healthIssuesArr, fn($val) => $val !== 'Any other');
        if ($request->filled('health_issue_other')) {
            $healthIssuesArr[] = $request->health_issue_other;
        }
        $healthIssues = implode(', ', $healthIssuesArr);

        // Update record and upgrade to member
        $patient->fill($validated);
        $patient->health_issues = $healthIssues;
        $patient->is_member = true;

        // Generate and assign new Membership ID
        $patient->patient_id = Survey::generateMembershipId();

        $patient->save();

        \App\Models\ActivityLog::logActivity(
            action: 'member_registered',
            description: "Patient upgraded to Member: {$patient->full_name} ({$patient->patient_id})",
            modelType: 'App\Models\Survey',
            modelId: $patient->id
        );

        return redirect()->route('membership.index')->with('success', 'Member registered successfully! Record moved to Membership section.');
    }
}
