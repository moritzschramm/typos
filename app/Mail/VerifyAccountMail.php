<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyAccountMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $verifyUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verifyUrl)
    {
      $this->verifyUrl = $verifyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->from(config('mail.from.noreply'))->markdown('emails.verification');
    }
}
