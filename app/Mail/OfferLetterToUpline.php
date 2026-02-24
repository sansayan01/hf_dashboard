<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfferLetterToUpline extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The newbie user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The upline user instance.
     *
     * @var \App\Models\User
     */
    public $upline;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, User $upline)
    {
        $this->user = $user;
        $this->upline = $upline;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Registration: Review Offer Letter for ' . ($this->user->profile->full_name ?? 'New Member'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.offer_letter_to_upline',
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
                'is_pdf' => true
            ]);

            $pdf->setPaper('a4', 'portrait');

            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'Unsigned_Offer_Letter_' . $this->user->employee_id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF for OfferLetterToUpline attachment: ' . $e->getMessage());
            return [];
        }
    }
}
