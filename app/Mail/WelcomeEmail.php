<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        
    }

    public function build()
    {
        return $this->subject('Abunə olduğunuz üçün təşəkkürlər')
        ->markdown('emails.welcome');
    }
}