<?php
namespace App\Notifications\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailChangeNotification extends Notification
{
    use Dispatchable, Queueable;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('E-poçt Dəyişdirmək Üçün OTP Kodu')
            ->line('E-poçtunuzu dəyişdirmək üçün aşağıdakı OTP kodunu istifadə edin.')
            ->line('OTP Kodu: ' . $this->otp)
            ->line('Bu kodu 5 dəqiqə ərzində istifadə etməlisiniz.');
    }
}
