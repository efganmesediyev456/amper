<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;

    public string $token;
    public string $email;
    public string $resetUrl;

    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
        $this->resetUrl = url("/custom-password-reset?token={$token}&email={$email}");
    }

    public function build()
    {
        return $this->subject(__('api.Reset Password'))
                    ->markdown('emails.password_reset');
    }
}