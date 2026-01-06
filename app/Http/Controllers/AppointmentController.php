<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Survey;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for the patient.
     */
    public function index(Survey $patient)
    {
        // Check access
        if (!auth()->user()->canAccess($patient->creator) && auth()->user()->id !== $patient->created_by) {
            abort(403, 'Unauthorized access.');
        }

        $appointments = $patient->appointments()->with('creator.profile')->latest()->get();

        return view('appointments.index', compact('patient', 'appointments'));
    }

    /**
     * Display a listing of all appointments the user has access to.
     */
    public function all(Request $request)
    {
        $user = auth()->user();
        $downline = $user->getAllDownline();
        $allowedIds = collect([$user])->merge($downline)->pluck('id')->toArray();

        $view = $request->get('view', 'scheduled');

        if ($view === 'successful') {
            $status = 'successful';
        } elseif ($view === 'not_attended') {
            $status = 'not_attended';
        } else {
            $status = 'scheduled';
        }

        $query = Appointment::with(['survey', 'creator.profile'])
            ->whereIn('created_by', $allowedIds)
            ->whereHas('survey');

        if ($view === 'not_attended') {
            $query->whereIn('status', ['not_attended', 'missed_reported']);
        } else {
            $query->where('status', $status);
        }

        // Filter by Date Range
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by Doctor Type / Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('doctor_type', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('survey', function ($sq) use ($search) {
                        $sq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('patient_id', 'like', "%{$search}%");
                    });
            });
        }

        $appointments = $query->latest('appointment_date')->latest('appointment_time')->paginate(20);

        return view('appointments.all', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(Survey $patient)
    {
        // Check access
        if (!auth()->user()->canAccess($patient->creator) && auth()->user()->id !== $patient->created_by) {
            abort(403, 'Unauthorized access.');
        }

        return view('appointments.create', compact('patient'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request, Survey $patient)
    {
        // Check access
        if (!auth()->user()->canAccess($patient->creator) && auth()->user()->id !== $patient->created_by) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'doctor_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Ensure only the time part is stored to avoid double time specification errors
        try {
            $validated['appointment_time'] = \Carbon\Carbon::parse($validated['appointment_time'])->format('H:i:s');
        } catch (\Exception $e) {
            // Fallback if parsing fails
        }

        $appointment = new Appointment($validated);
        $appointment->survey_id = $patient->id;
        $appointment->created_by = auth()->id();
        $appointment->save();

        return redirect()->route('patients.index')
            ->with('success', 'Appointment scheduled successfully.')
            ->with('view_appointment_url', route('patients.appointments.index', $patient->id));
    }

    /**
     * Mark the specified appointment as successful.
     */
    public function complete(Appointment $appointment)
    {
        // Check access
        $user = auth()->user();
        if ($user->id !== $appointment->created_by && !$user->canAccess($appointment->creator)) {
            abort(403);
        }

        $appointment->update(['status' => 'successful']);

        return redirect()->back()->with('success', 'Appointment marked as successful.');
    }

    /**
     * Report the specified appointment as missed (Staff action).
     */
    public function reportMissed(Appointment $appointment)
    {
        // Check access
        $user = auth()->user();
        if ($user->id !== $appointment->created_by && !$user->canAccess($appointment->creator)) {
            abort(403);
        }

        if ($user->isSuperAdmin()) {
            $appointment->update(['status' => 'not_attended']);
            $msg = 'Appointment finalized as Not Attended.';
        } else {
            $appointment->update(['status' => 'missed_reported']);
            $msg = 'Appointment marked as missed and sent for Super Admin confirmation.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Confirm the specified appointment as not attended (Super Admin action).
     */
    public function confirmMissed(Appointment $appointment)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can confirm non-attendance.');
        }

        $appointment->update(['status' => 'not_attended']);

        return redirect()->back()->with('success', 'Appointment confirmed as Not Attended.');
    }
}
