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

    public $newbie;
    public $upline;

    /**
     * Create a new message instance.
     */
    public function __construct(User $newbie, User $upline)
    {
        $this->newbie = $newbie;
        $this->upline = $upline;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pending Offer Letter for Team Member: ' . $this->newbie->profile->full_name,
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
                'user' => $this->newbie,
                'is_pdf' => true
            ]);

            $pdf->setPaper('a4', 'portrait');

            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'Offer_Letter_' . $this->newbie->employee_id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF for OfferLetterToUpline: ' . $e->getMessage());
            return [];
        }
    }
}
