<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('Invito per Noleggio Auto Autofficina Mirisciotti')
                    ->view('mails.invitation')
                    ->with([
                        'invitationUrl' => route('register.invitation', ['token' => $this->invitation->token]),
                        'expiresAt' => $this->invitation->expires_at->format('d/m/Y H:i'),
                        'companyName' => config('app.name', 'Car Rental Management')
                    ]);
    }
}
