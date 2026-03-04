<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\NewbieInvitation;
use App\Mail\OfferLetterToUpline;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegistrationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsApp): void
    {
        $user = User::with(['profile', 'parent.profile'])->find($this->userId);

        if (!$user) {
            return;
        }

        try {
            // Generate PDF for WhatsApp link
            $pdfUrl = null;
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.joining_letter', [
                    'user' => $user,
                    'is_pdf' => true
                ]);
                $pdf->setPaper('a4', 'portrait');

                $fileName = 'offer_letters/Unsigned_Offer_Letter_' . $user->employee_id . '_' . time() . '.pdf';
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $pdf->output());

                $pdfUrl = url('/') . '/storage-render/' . $fileName;
            } catch (\Exception $e) {
                \Log::error('RegistrationNotificationJob PDF Generation failed: ' . $e->getMessage());
            }

            // 1. Notify Registrar & Upline via WhatsApp
            $whatsApp->notifyNewbieRegistration($user);

            if ($user->parent) {
                $whatsApp->notifyUplineNewRegistration($user->parent, $user, $pdfUrl);

                // 2. Send Offer Letter (unsigned) to Upline ONLY via Email
                Mail::to($user->parent->email)->send(new \App\Mail\OfferLetterToUpline($user, $user->parent));
            }
        } catch (\Exception $e) {
            Log::error('RegistrationNotificationJob failed for user ' . $this->userId . ': ' . $e->getMessage());
        }
    }
}
