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
            ->whereIn('created_by', $allowedIds);

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

        $surveys = $query->latest()->get();

        return view('surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Store a newly created survey in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|size:10',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
            'health_issue_category' => 'required|string',
            'health_issue_other' => 'nullable|string',
        ]);

        // Combine health issues
        $healthIssues = $validated['health_issue_category'];
        if ($healthIssues === 'Any other' && $request->filled('health_issue_other')) {
            $healthIssues = $request->health_issue_other;
        }

        $survey = new Survey();
        $survey->full_name = $validated['full_name'];
        $survey->age = $validated['age'];
        $survey->gender = $validated['gender'];
        $survey->phone_number = $validated['phone_number'];
        $survey->address = $validated['address'];
        $survey->pin = $validated['pin'];
        $survey->health_issues = $healthIssues;
        $survey->created_by = Auth::id();
        $survey->save();

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
            'health_issue_category' => 'required|string',
            'health_issue_other' => 'nullable|string',
        ]);

        // Combine health issues
        $healthIssues = $validated['health_issue_category'];
        if ($healthIssues === 'Any other' && $request->filled('health_issue_other')) {
            $healthIssues = $request->health_issue_other;
        }

        $survey->full_name = $validated['full_name'];
        $survey->age = $validated['age'];
        $survey->gender = $validated['gender'];
        $survey->phone_number = $validated['phone_number'];
        $survey->address = $validated['address'];
        $survey->pin = $validated['pin'];
        $survey->health_issues = $healthIssues;
        $survey->save();

        return redirect()->route('surveys.index')->with('success', 'Survey updated successfully!');
    }
}
