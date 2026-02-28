<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class MemberVerificationController extends Controller
{
    /**
     * Show the public verification profile for a member.
     *
     * @param string $id patient_id
     */
    public function show($id)
    {
        // Find the patient by patient_id instead of auto-incrementing ID
        $patient = Survey::where('patient_id', $id)
            ->with([
                'appointments' => function ($q) {
                    $q->latest();
                },
                'medicineDistributions' => function ($q) {
                    $q->with('items.medicine')->latest();
                },
                'pathologyTests' => function ($q) {
                    $q->latest();
                }
            ])
            ->first();

        // If not found or not a premium member, show a nice invalid page
        // (We do not want to reveal internal data to random scanners)
        if (!$patient || !$patient->is_member) {
            return view('membership.public_verify', [
                'isValid' => false,
                'patient' => null
            ]);
        }

        // Return the clean public profile
        return view('membership.public_verify', [
            'isValid' => true,
            'patient' => $patient
        ]);
    }
}
