<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent',
            'date' => 'required|date',
        ]);

        $user = User::findOrFail($request->user_id);

        // Permission check: Only SuperAdmin or the user's RM (parent) can mark attendance
        if (!auth()->user()->isSuperAdmin() && auth()->id() !== $user->parent_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $request->date,
            ],
            [
                'marked_by' => auth()->id(),
                'status' => $request->status,
            ]
        );

        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance
        ]);
    }

    public function show(User $user)
    {
        // Permission check
        if (!auth()->user()->canAccess($user)) {
            abort(403);
        }

        $attendances = $user->attendances()
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.calendar', compact('user', 'attendances'));
    }
}
