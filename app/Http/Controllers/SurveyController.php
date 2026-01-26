<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    /**
     * Display a listing of the surveys.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all members this user is allowed to see data for (Self + All Downline)
        $downline = $user->getAllDownline();
        $allowedIds = collect([$user])->merge($downline)->pluck('id')->toArray();

        $query = Survey::with('creator.profile')
            ->whereIn('created_by', $allowedIds)
            ->doesntHave('appointments');

        // NIA Logic: If show_nia=1, show records older than 30 days. Otherwise, show records 30 days or newer.
        // Or strictly: NIA is survey > 30 days with no appointment.
        // Active list is survey <= 30 days with no appointment.
        if ($request->has('show_nia') && $request->show_nia == 1) {
            $query->where('created_at', '<', now()->subDays(30));
        } else {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // Apply Filters
        if ($request->filled('collector_id')) {
            $query->where('created_by', $request->collector_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('health_issue')) {
            $issue = $request->health_issue;
            if ($issue === 'Normal') {
                // For "Normal", we might check if no issues are listed, or just allow the loose match if "Normal" is textual.
                // Given the previous step's logic, let's keep it simple for now or refine if needed.
            }
            $query->where('health_issues', 'like', "%{$issue}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply Search (Patient Name, Phone, or Collector)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($cq) use ($search) {
                        $cq->where('employee_id', 'like', "%{$search}%")
                            ->orWhereHas('profile', function ($pq) use ($search) {
                                $pq->where('full_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $limit = $request->has('view_all') ? 5000 : 20;
        $surveys = $query->latest()->paginate($limit)->withQueryString();

        // Get list of potential collectors for the filter dropdown
        // This includes the user themselves and their downline members who have submitted surveys or are capable of it
        // To be efficient, we can just grab users from the $allowedIds list who actually have surveys, or just all of them.
        // Let's pass the $downline + self as "collectors"
        $collectors = collect([$user])->merge($downline);

        return view('surveys.index', compact('surveys', 'collectors'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        $user = Auth::user();

        // Permission check
        if (!$user->isSuperAdmin() && !\App\Models\RolePermission::check($user->designation, 'can_create_surveys')) {
            abort(403, 'Unauthorized: You do not have permission to create surveys.');
        }

        // Fetch all downline members + load profiles for the selection list
        $users = $user->getAllDownline()->load('profile');

        return view('surveys.create', compact('users'));
    }

    /**
     * Store a newly created survey in storage.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Permission check
        if (!$currentUser->isSuperAdmin() && !\App\Models\RolePermission::check($currentUser->designation, 'can_create_surveys')) {
            abort(403, 'Unauthorized: You do not have permission to create surveys.');
        }

        $rules = [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'health_issue_category' => 'nullable|array',
            'health_issue_other' => 'nullable|string',
            'created_by_user' => 'nullable|exists:users,id',
        ];

        $validated = $request->validate($rules);

        // Process health issues
        $healthIssuesArr = $validated['health_issue_category'] ?? [];
        $healthIssuesArr = array_filter($healthIssuesArr, fn($val) => $val !== 'Any other');
        if ($request->filled('health_issue_other')) {
            $healthIssuesArr[] = $request->health_issue_other;
        }
        $healthIssues = implode(', ', $healthIssuesArr) ?: 'Normal';

        $createdBy = $currentUser->id;
        if ($request->filled('created_by_user')) {
            $targetUser = \App\Models\User::findOrFail($request->created_by_user);

            // Authorization Check: Target must be in requester's downline or be themselves
            if ($targetUser->id !== $currentUser->id && !$currentUser->canAccess($targetUser)) {
                abort(403, 'Unauthorized: You can only create surveys for your own team members.');
            }
            $createdBy = $targetUser->id;
        }

        $survey = new Survey();
        $survey->full_name = $validated['full_name'];
        $survey->age = $validated['age'];
        $survey->gender = $validated['gender'];
        $survey->phone_number = $validated['phone_number'];
        $survey->address = $validated['address'];
        $survey->pin = $validated['pin'];
        $survey->health_issues = $healthIssues;
        $survey->created_by = $createdBy;
        $survey->save();

        \App\Models\ActivityLog::logActivity(
            action: 'survey_created',
            description: "New health survey created for {$survey->full_name} ({$survey->patient_id})",
            modelType: 'App\Models\Survey',
            modelId: $survey->id
        );

        return redirect()->route('surveys.index')->with('success', 'Survey submitted successfully!');
    }

    /**
     * Show the form for editing the specified survey.
     */
    public function edit(Survey $survey)
    {
        // Authorization Check: Only creator or their upline can edit
        $user = Auth::user();
        if ($user->id !== $survey->created_by && !$user->canAccess($survey->creator)) {
            abort(403);
        }

        return view('surveys.edit', compact('survey'));
    }

    /**
     * Update the specified survey in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        // Authorization Check
        $user = Auth::user();
        if ($user->id !== $survey->created_by && !$user->canAccess($survey->creator)) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'health_issue_category' => 'nullable|array',
            'health_issue_other' => 'nullable|string',
        ]);

        // Process health issues
        $healthIssuesArr = $validated['health_issue_category'] ?? [];
        $healthIssuesArr = array_filter($healthIssuesArr, fn($val) => $val !== 'Any other');
        if ($request->filled('health_issue_other')) {
            $healthIssuesArr[] = $request->health_issue_other;
        }
        $healthIssues = implode(', ', $healthIssuesArr) ?: 'Normal';

        $survey->full_name = $validated['full_name'];
        $survey->age = $validated['age'];
        $survey->gender = $validated['gender'];
        $survey->phone_number = $validated['phone_number'];
        $survey->address = $validated['address'];
        $survey->pin = $validated['pin'];
        $survey->health_issues = $healthIssues;
        $survey->save();

        \App\Models\ActivityLog::logActivity(
            action: 'survey_updated',
            description: "Health survey updated for {$survey->full_name} ({$survey->patient_id})",
            modelType: 'App\Models\Survey',
            modelId: $survey->id
        );

        return redirect()->route('surveys.index')->with('success', 'Survey updated successfully!');
    }
    /**
     * Remove the specified survey from storage.
     */
    public function destroy(Survey $survey)
    {
        // Authorization Check: Only creator or their upline can delete
        $user = Auth::user();
        if ($user->id !== $survey->created_by && !$user->canAccess($survey->creator)) {
            abort(403);
        }

        // Check if survey has appointments - if so, we might not want to delete it or handle it specifically
        if ($survey->appointments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete survey that has associated clinic appointments.');
        }

        $patientName = $survey->full_name;
        $patientId = $survey->patient_id;

        $survey->delete();

        \App\Models\ActivityLog::logActivity(
            action: 'survey_deleted',
            description: "Health survey deleted for {$patientName} ({$patientId})",
            modelType: 'App\Models\Survey',
            modelId: $survey->id
        );

        return redirect()->route('surveys.index')->with('success', 'Survey deleted successfully.');
    }

    /**
     * Bulk delete surveys.
     */
    public function bulkDestroy(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can perform bulk deletions.');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No surveys selected.');
        }

        // Filter out surveys that have appointments
        $surveysToDelete = Survey::whereIn('id', $ids)->get();
        $count = 0;
        $skipped = 0;

        foreach ($surveysToDelete as $survey) {
            if ($survey->appointments()->count() === 0) {
                $survey->delete();
                $count++;
            } else {
                $skipped++;
            }
        }

        $message = "Successfully deleted {$count} surveys.";
        if ($skipped > 0) {
            $message .= " Skipped {$skipped} surveys because they have associated appointments.";
        }

        \App\Models\ActivityLog::logActivity(
            action: 'bulk_survey_deleted',
            description: "Bulk deleted {$count} health surveys. Skipped {$skipped}.",
            modelType: 'App\Models\Survey'
        );

        return redirect()->route('surveys.index')->with('success', $message);
    }
}
