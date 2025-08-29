<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use App\Rules\UserEmailVerifyRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [new UserEmailVerifyRule, 'required', 'email', 'exists:user_temps,email'],
            'otp_code' => 'required|digits:6',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'email.required' => trans('api.email.required'),
            'email.email' => trans('api.email.email'),
            'email.exists' => trans('api.email.exists'),
            'otp_code.required' => trans('api.otp_code.required'),
            'otp_code.digits' => trans('api.otp_code.digits'),
        ];
    }
}
