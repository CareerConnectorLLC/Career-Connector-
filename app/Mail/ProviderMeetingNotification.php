<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderMeetingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $providerName;
    public $providerJoinToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $providerName, $providerJoinToken)
    { 
        $this->booking = $booking;
        $this->providerName = $providerName;
        $this->providerJoinToken = $providerJoinToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Meeting is Confirmed')
                    ->markdown('emails.provider_meeting_notification', [
                        'providerJoinToken' => $this->providerJoinToken,
                    ]);
    }
}
