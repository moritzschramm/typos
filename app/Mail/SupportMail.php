<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $email;
    protected $title;
    protected $message;
    protected $userId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $email, $message, $userId)
    {
      $this->title    = $title;
      $this->email    = $email;
      $this->message  = $message;
      $this->userId   = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email)
                    ->markdown('emails.support')
                    ->with([
                      'title'   => $this->title,
                      'email'   => $this->email,
                      'msg'     => $this->message,
                      'userId'  => $this->userId,
                    ]);
    }
}
