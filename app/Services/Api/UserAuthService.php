<?php
namespace App\Services\Api;

use App\Mail\UserMailVerfied;
use App\Models\PasswordReset;
use Carbon\Carbon;


use App\Models\User;
use App\Models\UserTemp;
use App\Models\UserVerify;
use Illuminate\Support\Facades\Mail;

class UserAuthService{
    public function sendOtp(UserTemp $user, array $data){
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(2);

        UserVerify::updateOrCreate(
            ['email' => $data['email']],
            ['otp_code' => $otp, 'expires_at' => $expiresAt]
        );

       return Mail::to($data['email'])->send(new UserMailVerfied($otp));
    }

    public function moveTempToUsers(UserVerify $data){
            $user = new User;
            $userTemp = UserTemp::where('email', $data['email'])->first();
            $user->password = $userTemp->password;
            $user->email = $userTemp->email;
            $user->name = $userTemp->name;
            $user->phone = $userTemp->phone;
            $user->email_verified_at = now();
            $userTemp->delete();
            $user->save();
            return $user;
    }
}



