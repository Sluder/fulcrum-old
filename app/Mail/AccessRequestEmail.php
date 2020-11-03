<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * If request was approved or denied
     */
    public $decision;

    /**
     * Users name for this request
     */
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($decision, $name)
    {
        $this->decision = $decision;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = ucfirst($this->decision);

        return $this->markdown('mail.access-request')
                    ->subject("Access {$subject}");
    }
}
