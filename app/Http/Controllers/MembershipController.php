<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class MembershipController extends Controller
{
    /**
     * Display a listing of premium (membership) patients.
     */
    public function index()
    {
        // For now, empty list or just return view
        return view('membership.index');
    }

    /**
     * Show the membership details for a specific patient.
     */
    public function show($id)
    {
        $patient = Survey::findOrFail($id);
        return view('membership.show', compact('patient'));
    }
}
