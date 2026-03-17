<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RolePermission;
use App\Models\ActivityLog;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function export(Request $request)
    {
        $user = User::getEffectiveUser();

        // Permission check
        if (
            !$user->isSuperAdmin() &&
            !RolePermission::check($user->designation, 'can_create_surveys') &&
            $user->designation !== 'staff'
        ) {
            abort(403, 'Unauthorized access.');
        }

        $allowedIds = $user->getDataVisibilityIds();

        $query = Survey::with('creator.profile')
            ->whereIn('created_by', $allowedIds)
            ->where('is_member', false);

        // Apply filters (same as index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('collector_id')) {
            $query->where('created_by', $request->collector_id);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('health_issue')) {
            $query->where('health_issues', 'like', "%{$request->health_issue}%");
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $patients = $query->latest()->get();

        $filename = "patients_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($patients) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Patient ID',
                'Full Name',
                'Relative Name',
                'Age',
                'Gender',
                'Phone',
                'Address',
                'Landmark',
                'PIN',
                'Blood Group',
                'Aadhaar Number',
                'PAN Number',
                'District',
                'Block',
                'GP',
                'Past Diseases',
                'Current Health Issues',
                'Insurance/Loan Req',
                'Collector Name',
                'Collector ID',
                'Registration Date'
            ]);

            foreach ($patients as $p) {
                fputcsv($file, [
                    $p->patient_id,
                    $p->full_name,
                    $p->relative_name ?? 'N/A',
                    $p->age,
                    ucfirst($p->gender),
                    $p->phone_number,
                    $p->address,
                    $p->landmark ?? 'N/A',
                    $p->pin,
                    $p->blood_group ?? 'N/A',
                    $p->aadhar_number ?? 'N/A',
                    $p->pan_number ?? 'N/A',
                    $p->district ?? 'N/A',
                    $p->block ?? 'N/A',
                    $p->gp ?? 'N/A',
                    $p->past_diseases ?? 'None',
                    $p->health_issues ?? 'None',
                    $p->insurance_loan_req ?? 'No',
                    $p->creator->profile->full_name ?? 'N/A',
                    $p->creator->employee_id ?? 'N/A',
                    $p->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $user = User::getEffectiveUser();

        // Permission check (Allow Super Admin, users with survey permission, and Pharmacists)
        if (
            !$user->isSuperAdmin() &&
            !RolePermission::check($user->designation, 'can_create_surveys') &&
            $user->designation !== 'staff'
        ) {
            abort(403, 'Unauthorized access.');
        }


        // Get all members this user is allowed to see data for
        // Pharmacists can see all patients (they need to dispense medicine to anyone)
        // Pharmacists can see patients under their camp RM's team
        $allowedIds = $user->getDataVisibilityIds();
        $downline = $user->getAllDownline(); // Still needed for the collectors filter dropdown


        $query = Survey::with('creator.profile')
            ->whereIn('created_by', $allowedIds)
            ->where('is_member', false)
            ->has('appointments');

        // Apply Search (Patient Name, Phone, or Collector)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($cq) use ($search) {
                        $cq->where('employee_id', 'like', "%{$search}%")
                            ->orWhereHas('profile', function ($pq) use ($search) {
                                $pq->where('full_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Apply Advanced Filters
        if ($request->filled('collector_id')) {
            $query->where('created_by', $request->collector_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('health_issue')) {
            $issue = $request->health_issue;
            $query->where('health_issues', 'like', "%{$issue}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $limit = $request->has('view_all') ? 5000 : 20;
        $patients = $query->latest()->paginate($limit)->withQueryString();

        // Get list of potential collectors for the filter dropdown
        if ($user->designation === 'staff') {
            // Pharmacists see all users who have created patients
            $collectors = User::whereIn('id', $allowedIds)->with('profile')->get();
        } else {
            $collectors = collect([$user])->merge($downline);
        }

        if ($request->ajax()) {
            $stats = [
                'total' => (clone $query)->count(),
                'high_risk' => (clone $query)->where(function ($q) {
                    $q->whereNotNull('health_issues')
                        ->whereNotIn('health_issues', ['Normal', 'None', '']);
                })->count(),
                'weekly_gain' => (clone $query)->where('created_at', '>=', now()->subDays(7))->count(),
            ];

            return response()->json([
                'table_html' => view('patients.partials.table', compact('patients'))->render(),
                'pagination_html' => $patients->hasPages() ? (string) $patients->links() : '',
                'total' => $patients->total(),
                'stats' => $stats,
            ]);
        }

        return view('patients.index', compact('patients', 'collectors'));
    }

    public function create()
    {
        $user = User::getEffectiveUser();

        // Permission check
        if (!$user->isSuperAdmin() && !RolePermission::check($user->designation, 'can_create_surveys')) {
            abort(403, 'Unauthorized: You do not have permission to register new patients.');
        }

        $users = $user->getAllDownline()->load('profile');

        return view('patients.create', compact('users'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $user = User::getEffectiveUser();

        // Permission check
        if (!$user->isSuperAdmin() && !RolePermission::check($user->designation, 'can_create_surveys')) {
            abort(403, 'Unauthorized: You do not have permission to register new patients.');
        }

        $rules = [
            'full_name' => 'required|string|max:255',
            'relative_name' => 'nullable|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10|unique:surveys,phone_number',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'aadhar_number' => 'nullable|string|size:12|unique:surveys,aadhar_number',
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
            'created_by_user' => 'nullable|exists:users,id',
        ];

        $validated = $request->validate($rules);

        // Combine health issues
        $healthIssuesArr = $validated['health_issue_category'] ?? [];
        $healthIssuesArr = array_filter($healthIssuesArr, fn($val) => $val !== 'Any other');
        if ($request->filled('health_issue_other')) {
            $healthIssuesArr[] = $request->health_issue_other;
        }
        $healthIssues = implode(', ', $healthIssuesArr);

        $createdBy = $user->id;
        if ($request->filled('created_by_user')) {
            $targetUser = User::findOrFail($request->created_by_user);

            // Authorization Check: Target must be in requester's downline or be themselves
            if ($targetUser->id !== $user->id && !$user->canAccess($targetUser)) {
                abort(403, 'Unauthorized: You can only register patients for your own team members.');
            }
            $createdBy = $targetUser->id;
        }

        $patient = new Survey();
        $patient->full_name = $validated['full_name'];
        $patient->relative_name = $validated['relative_name'] ?? null;
        $patient->age = $validated['age'];
        $patient->gender = $validated['gender'];
        $patient->phone_number = $validated['phone_number'];
        $patient->address = $validated['address'];
        $patient->pin = $validated['pin'];
        $patient->aadhar_number = $validated['aadhar_number'] ?? null;
        $patient->pan_number = $validated['pan_number'] ?? null;
        $patient->blood_group = $validated['blood_group'] ?? null;
        $patient->district = $validated['district'] ?? null;
        $patient->block = $validated['block'] ?? null;
        $patient->gp = $validated['gp'] ?? null;
        $patient->landmark = $validated['landmark'] ?? null;
        $patient->past_diseases = $validated['past_diseases'] ?? null;
        $patient->health_issues = $healthIssues;
        $patient->insurance_loan_req = $validated['insurance_loan_req'] ?? 'No';
        $patient->created_by = $createdBy;
        $patient->recorded_by = $user->id; // The person actually typing

        // Retry logic for handling duplicate ID race conditions
        $maxRetries = 3;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $patient->save();
                break; // Success
            } catch (\Illuminate\Database\QueryException $e) {
                // @phpstan-ignore-next-line
                $errorInfo = $e->errorInfo ?? [];
                if (isset($errorInfo[1]) && $errorInfo[1] === 1062) { // Duplicate entry
                    $attempt++;
                    if ($attempt >= $maxRetries) {
                        throw $e; // Give up after max retries
                    }
                    // Reset patient_id to trigger regeneration in the boot event
                    $patient->patient_id = null;
                    continue;
                }
                throw $e; // Rethrow other errors
            }
        }

        ActivityLog::logActivity(
            action: 'patient_registered',
            /** @var Survey $patient */
            description: "New patient registered: {$patient->full_name} ({$patient->patient_id})",
            modelType: Survey::class,
            modelId: $patient->id
        );

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');
    }

    /**
     * Check for uniqueness via AJAX
     */
    public function checkUniqueness(Request $request)
    {
        $field = $request->field; // phone_number, aadhar_number
        $value = $request->value;
        $excludeId = $request->exclude_id;

        if (!$field || !$value) {
            return response()->json(['exists' => false]);
        }

        $allowedFields = ['phone_number', 'aadhar_number'];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['exists' => false]);
        }

        $query = Survey::where($field, $value);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $patient = $query->first();

        if ($patient) {
            return response()->json([
                'exists' => true,
                'patient' => [
                    'id' => $patient->id,
                    'full_name' => $patient->full_name,
                    'patient_id' => $patient->patient_id,
                    'phone_number' => $patient->phone_number,
                    'aadhar_number' => $patient->aadhar_number,
                    'is_member' => $patient->is_member,
                ],
                'message' => "A patient with this " . ($field === 'aadhar_number' ? 'Aadhaar' : 'Phone') . " number already exists in our database."
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Display the specified patient profile.
     */
    public function show(Survey $patient)
    {
        /** @var Survey $patient */
        // Authorization Check
        $user = Auth::user();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        $patient->load([
            'creator.profile',
            'appointments',
            'medicineDistributions' => function ($q) {
                $q->with(['items.medicine', 'camp', 'pharmacist.profile'])->latest();
            },
            'pathologyTests' => function ($q) {
                $q->with(['creator.profile', 'ro.profile'])->latest();
            }
        ]);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Survey $patient)
    {
        // Authorization Check: Only creator or their upline can edit
        $user = Auth::user();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Survey $patient)
    {
        /** @var Survey $patient */
        // Authorization Check
        $user = Auth::user();
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

        $patient->full_name = $validated['full_name'];
        $patient->relative_name = $validated['relative_name'] ?? null;
        $patient->age = $validated['age'];
        $patient->gender = $validated['gender'];
        $patient->phone_number = $validated['phone_number'];
        $patient->address = $validated['address'];
        $patient->pin = $validated['pin'];
        $patient->aadhar_number = $validated['aadhar_number'] ?? null;
        $patient->pan_number = $validated['pan_number'] ?? null;
        $patient->blood_group = $validated['blood_group'] ?? null;
        $patient->district = $validated['district'] ?? null;
        $patient->block = $validated['block'] ?? null;
        $patient->gp = $validated['gp'] ?? null;
        $patient->landmark = $validated['landmark'] ?? null;
        $patient->past_diseases = $validated['past_diseases'] ?? null;
        $patient->health_issues = $healthIssues;
        $patient->insurance_loan_req = $validated['insurance_loan_req'] ?? 'No';
        $patient->save();

        /** @var Survey $patient */
        ActivityLog::logActivity(
            action: 'patient_updated',
            description: "Patient profile updated: {$patient->full_name} ({$patient->patient_id})",
            modelType: Survey::class,
            modelId: $patient->id
        );

        return redirect()->route('patients.show', $patient->id)->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Survey $patient)
    {
        /** @var Survey $patient */
        // Authorization Check
        $user = Auth::user();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator)) {
            abort(403);
        }

        $patient->delete();

        /** @var Survey $patient */
        ActivityLog::logActivity(
            action: 'patient_deleted',
            description: "Patient record moved to bin: {$patient->full_name} ({$patient->patient_id})",
            modelType: Survey::class,
            modelId: $patient->id
        );

        return redirect()->route('patients.index')->with('success', 'Patient record moved to BIN. Can be restored within 30 days.');
    }

    /**
     * Display a listing of deleted patients (the BIN).
     */
    public function bin()
    {
        $currentUser = Auth::user();

        $query = Survey::onlyTrashed()->with('creator.profile');

        $allowedIds = $currentUser->getDataVisibilityIds();
        $query->whereIn('created_by', $allowedIds);

        $patients = $query->latest('deleted_at')->paginate(20);

        return view('patients.bin', compact('patients'));
    }

    /**
     * Restore a deleted patient record.
     */
    public function restore($id)
    {
        $patient = Survey::onlyTrashed()->findOrFail($id);
        $currentUser = Auth::user();

        // Check if user has access to the record
        if ($currentUser->id !== $patient->created_by && !$currentUser->canAccess($patient->creator)) {
            abort(403);
        }

        $patient->restore();

        /** @var Survey $patient */
        ActivityLog::logActivity(
            action: 'patient_restored',
            description: "Patient record restored from bin: {$patient->full_name} ({$patient->patient_id})",
            modelType: Survey::class,
            modelId: $patient->id
        );

        return redirect()->route('patients.bin')->with('success', 'Patient record restored successfully.');
    }

    /**
     * Permanently delete a patient record (Super Admin only).
     */
    public function forceDelete($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can permanently delete records.');
        }

        $patient = Survey::onlyTrashed()->findOrFail($id);
        $patient->forceDelete();

        return redirect()->route('patients.bin')->with('success', 'Patient record permanently deleted.');
    }
}
