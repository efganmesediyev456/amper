<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => __('api.email.required'),
            'email.email' => __('api.email.email'),
            'email.exists' => __('api.email.exists'),
        ]);
        

        $status = Password::sendResetLink($request->only('email'), function ($user, $token) use($request) {
            Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($token, $user->email));
        });

        if ($status === Password::RESET_LINK_SENT) {
            return $this->responseMessage('success', __('api.Password reset link has been sent to your email address'), [], 200);
         }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    public function resetPassword(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => __('api.email.required'),
            'email.email' => __('api.email.email'),
            'email.exists' => __('api.email.exists'),
        
            'token.required' => __('api.token.required'),
        
            'password.required' => __('api.password.required'),
            'password.min' => __('api.password.min'),
            'password.confirmed' => __('api.password.confirmed'),
        ]);
        

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                
            }
        );

        $user = User::where('email', $request->email)->first();

        $this->notificationService->sendNotification(
            $user,
            'reset_password',
            [
                'message' => __("api.Password has been successfully updated")
            ]
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __('api.Password has been successfully updated')], 200);
        }

        return response()->json(['message' => __('api.Token is incorrect or expired')], 400);
    }
}
