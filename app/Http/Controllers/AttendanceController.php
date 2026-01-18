<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', date('Y-m-d'));

        // Only RM and Super Admin can take attendance
        if (!$user->isSuperAdmin() && !$user->isRM()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Get ROs reporting to this RM (or all ROs for Super Admin)
        if ($user->isSuperAdmin()) {
            $ros = User::where('designation', 'ro')->orderBy('employee_id')->get();
        } else {
            $ros = User::where('parent_id', $user->id)
                ->where('designation', 'ro')
                ->orderBy('employee_id')
                ->get();
        }

        // Get existing attendance for the selected date
        $attendances = Attendance::where('date', $date)
            ->whereIn('user_id', $ros->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('attendance.index', compact('ros', 'attendances', 'date'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $data = $request->input('attendance', []);

        if (!$user->isSuperAdmin() && !$user->isRM()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        foreach ($data as $roId => $details) {
            // Permission check: ensure manager can actually record for this user
            $targetUser = User::find($roId);
            if (!$targetUser || (!$user->isSuperAdmin() && $targetUser->parent_id !== $user->id)) {
                continue;
            }

            Attendance::updateOrCreate(
                ['user_id' => $roId, 'date' => $date],
                [
                    'status' => $details['status'],
                    'remarks' => $details['remarks'] ?? null,
                    'recorded_by' => $user->id
                ]
            );
        }

        return redirect()->route('attendance.index', ['date' => $date])->with('success', 'Attendance updated successfully.');
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', date('Y-m-d'));

        if (!$user->isSuperAdmin() && !$user->isRM()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $query = Attendance::with(['user', 'recorder'])
            ->whereBetween('date', [$startDate, $endDate]);

        if (!$user->isSuperAdmin()) {
            // Show only subordinates for RM
            $subordinateIds = User::where('parent_id', $user->id)->pluck('id');
            $query->whereIn('user_id', $subordinateIds);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        return view('attendance.history', compact('attendances', 'startDate', 'endDate'));
    }
}
