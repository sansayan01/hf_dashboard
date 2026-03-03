<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for the patient.
     */
    public function index(Survey $patient)
    {
        // Permission Check
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

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
        $currentUser = auth()->user();
        // Permission Check
        if (!$currentUser->isSuperAdmin() && !\App\Models\RolePermission::check($currentUser->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::getEffectiveUser();
        $allowedIds = $user->getDataVisibilityIds();

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

        // Apply Geographic Filters
        if ($request->filled('district')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('district', $request->district);
            });
        }

        if ($request->filled('block')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        if ($request->filled('gp')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('gram_panchayat', $request->gp);
            });
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
     * Export a listing of all appointments the user has access to.
     */
    public function export(Request $request)
    {
        // Permission Check
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::getEffectiveUser();
        $allowedIds = $user->getDataVisibilityIds();

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

        // Apply Geographic Filters
        if ($request->filled('district')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('district', $request->district);
            });
        }

        if ($request->filled('block')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        if ($request->filled('gp')) {
            $query->whereHas('creator.profile', function ($q) use ($request) {
                $q->where('gram_panchayat', $request->gp);
            });
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

        $appointments = $query->latest('appointment_date')->latest('appointment_time')->get();

        $filename = "appointments_" . $view . "_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($appointments) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'SL',
                'Appt ID',
                'Patient Name',
                'Patient ID',
                'Phone',
                'Appointment Date',
                'Appointment Time',
                'Clinic Type',
                'Location',
                'Status',
                'Recorded By',
                'Employee ID'
            ]);

            foreach ($appointments as $index => $a) {
                fputcsv($file, [
                    $index + 1,
                    $a->appointment_id,
                    $a->survey->full_name ?? 'N/A',
                    $a->survey->patient_id ?? 'N/A',
                    $a->survey->phone_number ?? 'N/A',
                    $a->appointment_date->format('Y-m-d'),
                    \Carbon\Carbon::parse($a->appointment_time)->format('h:i A'),
                    $a->doctor_type,
                    $a->location,
                    ucfirst(str_replace('_', ' ', $a->status)),
                    $a->creator->profile?->full_name ?? 'N/A',
                    $a->creator->employee_id ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(Survey $patient)
    {
        $user = User::getEffectiveUser();
        // Permission Check
        if (!$user->isSuperAdmin() && !\App\Models\RolePermission::check($user->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

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
        $user = User::getEffectiveUser();
        // Permission Check
        if (!$user->isSuperAdmin() && !\App\Models\RolePermission::check($user->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        // Check access
        if (!auth()->user()->canAccess($patient->creator) && auth()->user()->id !== $patient->created_by) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'doctor_type' => 'required|string|max:255',
            'doctor_type_other' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Process doctor type
        $doctorType = $validated['doctor_type'];
        if ($doctorType === 'Any other' && $request->filled('doctor_type_other')) {
            $doctorType = $request->doctor_type_other;
        }

        // Force date to be a simple Y-m-d string to prevent timezone shifts
        try {
            $appointmentDate = \Illuminate\Support\Carbon::parse($validated['appointment_date'])->toDateString();
        } catch (\Exception $e) {
            $appointmentDate = $validated['appointment_date'];
        }

        $appointment = new Appointment([
            'doctor_type' => $doctorType,
            'location' => $validated['location'],
            'appointment_date' => $appointmentDate,
            'appointment_time' => $validated['appointment_time'],
        ]);
        $appointment->survey_id = $patient->id;
        $appointment->created_by = $user->id;
        $appointment->save();

        \App\Models\ActivityLog::logActivity(
            action: 'appointment_created',
            description: "New appointment scheduled for {$patient->full_name} for {$appointment->appointment_date}",
            modelType: 'App\Models\Appointment',
            modelId: $appointment->id
        );

        return redirect()->route('patients.index')
            ->with('success', 'Appointment scheduled successfully.')
            ->with('view_appointment_url', route('patients.appointments.index', $patient->id));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Permission Check
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        // Check access
        $user = auth()->user();
        if ($user->id !== $appointment->created_by && !$user->canAccess($appointment->creator)) {
            abort(403, 'Unauthorized access.');
        }

        $patient = $appointment->survey;
        return view('appointments.edit', compact('appointment', 'patient'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Permission Check
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        // Check access
        $user = auth()->user();
        if ($user->id !== $appointment->created_by && !$user->canAccess($appointment->creator)) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'doctor_type' => 'required|string|max:255',
            'doctor_type_other' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Process doctor type
        $doctorType = $validated['doctor_type'];
        if ($doctorType === 'Any other' && $request->filled('doctor_type_other')) {
            $doctorType = $request->doctor_type_other;
        }

        // Force date to be a simple Y-m-d string to prevent timezone shifts
        try {
            $appointmentDate = \Illuminate\Support\Carbon::parse($validated['appointment_date'])->toDateString();
        } catch (\Exception $e) {
            $appointmentDate = $validated['appointment_date'];
        }

        $appointment->update([
            'doctor_type' => $doctorType,
            'location' => $validated['location'],
            'appointment_date' => $appointmentDate,
            'appointment_time' => $validated['appointment_time'],
        ]);

        \App\Models\ActivityLog::logActivity(
            action: 'appointment_updated',
            description: "Appointment updated for {$appointment->survey->full_name} to {$appointment->appointment_date}",
            modelType: 'App\Models\Appointment',
            modelId: $appointment->id
        );

        return redirect()->route('patients.index')
            ->with('success', 'Appointment updated successfully.')
            ->with('view_appointment_url', route('patients.appointments.index', $appointment->survey_id));
    }

    /**
     * Mark the specified appointment as successful.
     */
    public function complete(Appointment $appointment)
    {
        // Check access - Powered by RolePermission
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403);
        }

        $appointment->update(['status' => 'successful']);

        // DAB salary mode: Credit DA amount to the user who created this appointment if they are in DAB mode
        $creator = $appointment->creator;
        $eligibleRoles = ['ro', 'rm', 'bm', 'dm'];

        if ($creator && in_array($creator->designation, $eligibleRoles) && $creator->isDabMode()) {
            $appointmentDate = $appointment->appointment_date ?? now()->toDateString();

            // Get configured DA amount (fallback to ₹20 if not configured)
            $config = $creator->getCurrentIncentive(\Carbon\Carbon::parse($appointmentDate));
            $daAmount = ($config && $config->da_amount > 0) ? $config->da_amount : 20;

            $attendance = \App\Models\Attendance::firstOrNew([
                'user_id' => $creator->id,
                'date' => \Carbon\Carbon::parse($appointmentDate)->startOfDay(),
            ]);

            // If this is a new attendance record, initialize it
            if (!$attendance->exists) {
                $attendance->marked_by = auth()->id() ?? $creator->id;
                $attendance->status = 'present';
                $attendance->incentive_amount = 0;
                $attendance->medicines_amount = 0;
                $attendance->pathology_amount = 0;
                $attendance->membership_amount = 0;
                $attendance->ots_amount = 0;
                $attendance->ta_amount = 0;
            }

            // Add configured DA amount to ta_amount (DAB earnings stored in ta_amount field)
            $attendance->ta_amount = ($attendance->ta_amount ?? 0) + $daAmount;
            $attendance->save(); // Triggers total_amount recalculation via boot hook
        }

        \App\Models\ActivityLog::logActivity(
            action: 'appointment_completed',
            description: "Appointment marked as successful for {$appointment->survey->full_name}",
            modelType: 'App\Models\Appointment',
            modelId: $appointment->id
        );

        return redirect()->back()->with('success', 'Appointment marked as successful.');
    }

    /**
     * Delete the specified appointment.
     */
    public function destroy(Appointment $appointment)
    {
        // Permission Check
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        // Check access
        $user = auth()->user();
        if ($user->id !== $appointment->created_by && !$user->canAccess($appointment->creator)) {
            abort(403);
        }

        // Only allow deleting upcoming or missed reported appointments
        if (!in_array($appointment->status, ['scheduled', 'missed_reported'])) {
            return redirect()->back()->with('error', 'Cannot delete finalized appointments.');
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Report the specified appointment as missed (Staff action).
     */
    public function reportMissed(Appointment $appointment)
    {
        // Check access
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403);
        }

        $appointment->update(['status' => 'not_attended']);

        \App\Models\ActivityLog::logActivity(
            action: 'appointment_missed',
            description: "Appointment reported as not attended for {$appointment->survey->full_name}",
            modelType: 'App\Models\Appointment',
            modelId: $appointment->id
        );

        return redirect()->back()->with('success', 'Appointment finalized as Not Attended.');
    }

    /**
     * Confirm the specified appointment as not attended (Super Admin action).
     */
    public function confirmMissed(Appointment $appointment)
    {
        if (!auth()->user()->isSuperAdmin() && !\App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments')) {
            abort(403, 'Unauthorized access.');
        }

        $appointment->update(['status' => 'not_attended']);

        return redirect()->back()->with('success', 'Appointment confirmed as Not Attended.');
    }
}
