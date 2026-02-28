<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use App\Helpers\QrCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MembershipCardController extends Controller
{
    /**
     * Build the QR code base64 data for a patient's verification URL.
     */
    private function generateQrBase64(Survey $patient): string
    {
        // Determine the correct base URL
        $baseUrl = rtrim(url('/'), '/');

        // If APP_URL is localhost but we're actually on a domain, use the domain
        if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $requestHost = request()->getSchemeAndHttpHost();
            if (!str_contains($requestHost, 'localhost') && !str_contains($requestHost, '127.0.0.1')) {
                $baseUrl = $requestHost;
            } else {
                $baseUrl = 'http://192.168.0.6/HF/public';
            }
        }

        $verifyUrl = rtrim($baseUrl, '/') . '/verify/member/' . $patient->patient_id;

        // Generate locally using pure PHP (no API calls, no external dependencies)
        return QrCode::toBase64Png($verifyUrl, 3, 1);
    }

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

        // Generate QR code locally
        $qrBase64 = $this->generateQrBase64($patient);

        // Generate PDF
        $pdf = Pdf::loadView('membership.cards.pvc', compact('patient', 'qrBase64'));

        // Standard PVC dimensions: 85.6mm x 53.98mm
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

        // Generate QR code locally
        $qrBase64 = $this->generateQrBase64($patient);

        $pdf = Pdf::loadView('membership.cards.pvc', compact('patient', 'qrBase64'));
        $pdf->setPaper([0, 0, 242.646, 153.018], 'landscape');

        return $pdf->stream("PVC_Card_{$patient->patient_id}.pdf");
    }
}
