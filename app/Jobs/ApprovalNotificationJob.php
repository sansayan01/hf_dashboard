<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\NewbieInvitation;
use App\Mail\UserApproved;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApprovalNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $approverId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $approverId)
    {
        $this->userId = $userId;
        $this->approverId = $approverId;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsApp): void
    {
        $user = User::with('profile')->find($this->userId);
        $approver = User::find($this->approverId);

        if (!$user || !$approver) {
            return;
        }

        try {
            // 1. Send UserApproved Email (Greeting mail with ID, Password, and Login Link)
            Mail::to($user->email)->send(new \App\Mail\UserApproved($user, $approver));

            // 2. Notify via WhatsApp (Including the Letter)
            try {
                // Generate PDF for WhatsApp link (if needed for the message)
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.joining_letter', [
                    'user' => $user,
                    'is_pdf' => true
                ]);
                $pdf->setPaper('a4', 'portrait');

                $fileName = 'offer_letters/Approved_Offer_Letter_' . $user->employee_id . '_' . time() . '.pdf';
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $pdf->output());

                $pdfUrl = url('/') . '/storage-render/' . $fileName;
                $whatsApp->notifyApprovedNewbie($user, $pdfUrl);
            } catch (\Exception $pdfEx) {
                \Log::error('ApprovalNotificationJob PDF/WhatsApp failed: ' . $pdfEx->getMessage());
                // Still proceed, email is most important
                $whatsApp->notifyApprovedNewbie($user, null);
            }

        } catch (\Exception $e) {
            Log::error('ApprovalNotificationJob failed for user ' . $this->userId . ': ' . $e->getMessage());
        }
    }
}
