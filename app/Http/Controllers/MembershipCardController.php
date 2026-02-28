<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MembershipCardController extends Controller
{
    /**
     * Generate and download the PVC card for a premium member.
     */
    public function download(Survey $patient)
    {
        // Check access
        $user = User::getEffectiveUser();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator) && !$user->isSuperAdmin()) {
            abort(403);
        }

        if (!$patient->is_member) {
            return back()->with('error', 'Only premium members can have a PVC card.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('membership.cards.pvc', compact('patient'));

        // Ensure remote images (QR APIs) work on live server
        $options = $pdf->getDomPDF()->getOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Standard PVC dimensions: 85.6mm x 53.98mm
        // In points (1mm = 2.83465pts): 242.646pts x 153.018pts
        // For landscape: 242.646 (width) x 153.018 (height)
        $pdf->setPaper([0, 0, 242.646, 153.018], 'landscape');

        return $pdf->download("PVC_Card_{$patient->patient_id}.pdf");
    }

    /**
     * Stream the PVC card for a premium member (useful for preview).
     */
    public function stream(Survey $patient)
    {
        // Check access
        $user = User::getEffectiveUser();
        if ($user->id !== $patient->created_by && !$user->canAccess($patient->creator) && !$user->isSuperAdmin()) {
            abort(403);
        }

        if (!$patient->is_member) {
            abort(404, 'Not a premium member.');
        }

        $pdf = Pdf::loadView('membership.cards.pvc', compact('patient'));

        // Ensure remote images (QR APIs) work on live server
        $options = $pdf->getDomPDF()->getOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $pdf->setPaper([0, 0, 242.646, 153.018], 'landscape');

        return $pdf->stream("PVC_Card_{$patient->patient_id}.pdf");
    }
}
