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
            // 1. Send Invite Email to User
            Mail::to($user->email)->send(new NewbieInvitation($user));

            // 2. Send UserApproved Email
            Mail::to($user->email)->send(new UserApproved($user, $approver));

            // 3. Notify via WhatsApp
            $whatsApp->notifyApprovedNewbie($user);
        } catch (\Exception $e) {
            Log::error('ApprovalNotificationJob failed for user ' . $this->userId . ': ' . $e->getMessage());
        }
    }
}
