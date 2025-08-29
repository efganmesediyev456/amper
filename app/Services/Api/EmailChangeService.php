<?php
namespace App\Services\Api;

use App\Models\ChangeEmailOtp;
use App\Models\User;
use App\Notifications\Api\EmailChangeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EmailChangeService
{
    public function sendOtp($email)
    {
        $otp = random_int(1000, 9999);
        $user = Auth::user();

        ChangeEmailOtp::create([
                'user_id' => $user->id,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'new_email' => $email
        ]);
        
        // Notification::send($user, new EmailChangeNotification($otp));
        Notification::route('mail', $email)->notify(new EmailChangeNotification($otp));
        return $otp; 
    }

    public function changeEmail(User $user, $newEmail, $otp)
    {
        DB::transaction(function () use ($user, $newEmail, $otp) {
            $otpRecord = ChangeEmailOtp::where('user_id', $user->id)
                ->where('otp', $otp)
                ->where('expires_at', '>', now())
                ->where('is_used', false)
                ->first();


            if (!$otpRecord) {
                throw new \Exception('Keçərsiz və ya müddəti bitmiş OTP kodu.');
            }

            $user->email = $otpRecord->new_email;
            $user->save();

            $otpRecord->is_used = true;
            $otpRecord->save();
        });
    }
}
