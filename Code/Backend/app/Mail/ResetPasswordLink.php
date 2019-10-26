<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $token;
    protected $resetCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $token, $resetCode)
    {
        $this->name = $name;
        $this->token = $token;
        $this->resetCode = $resetCode;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.reset-password-link')
            ->with('name', $this->name)
            ->with('token', $this->token)
            ->with('resetCode', $this->resetCode)
            ->subject("Reset Password");
    }
}
