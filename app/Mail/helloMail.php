<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class helloMail extends Mailable
{
    use Queueable, SerializesModels;
    public $fullname;
    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($fullname,$token,$email)
    {
        $this->fullname = $fullname;
        $this->token = $token;
        $this->email=$email;
    }

    /**
     * Get the message envelope.
     */
  
     public function build()
     {
         return $this->view('emails.helloMail')
                     ->subject('Password Reset Request')
                     ->with([
                         'fullname' => $this->fullname,
                         'token' => $this->token,
                         'url' => url('/password/reset', $this->token) . '?email=' . urlencode($this->email),
                     ]);
     }
     
}
