<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketNumber;
    public $ticketInfo;
    public $ticketStatus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketNumber, $ticketInfo, $ticketStatus)
    {
        $this->ticketNumber = $ticketNumber;
        $this->ticketInfo = $ticketInfo;
        $this->ticketStatus = $ticketStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticket')
            ->with([
                'ticketNumber' => $this->ticketNumber,
                'ticketInfo' => $this->ticketInfo,
                'ticketStatus' => $this->ticketStatus,
            ]);
    }
}
