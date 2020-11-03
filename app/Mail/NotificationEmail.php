<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Message from notification
     */
    public $message;

    /**
     * Trading bot this notification is for
     */
    public $trade_bot_id;

    /**
     * Create a new message instance.
     */
    public function __construct($message, $trade_bot_id)
    {
        $this->message = $message;
        $this->trade_bot_id = $trade_bot_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.notification')
                    ->subject("Trading Bot " . $this->trade_bot_id);
    }
}
