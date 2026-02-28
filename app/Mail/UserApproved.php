<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The approver instance.
     *
     * @var \App\Models\User
     */
    public $approver;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, User $approver)
    {
        $this->user = $user;
        $this->approver = $approver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Approved - Welcome to Humanity Foundation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.joining_letter', [
                'user' => $this->user,
                'is_pdf' => true // Pass a flag to indicate it's for PDF generation
            ]);

            // Set paper size to A4
            $pdf->setPaper('a4', 'portrait');

            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'Offer_Letter_' . $this->user->employee_id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF for attachment: ' . $e->getMessage());
            return [];
        }
    }
}
