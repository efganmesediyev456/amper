<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class RegisterUserRequest extends FormRequest
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
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "phone" => "required",
            "password" => [
                "required",
                "string",
                "min:8",
                "regex:/[A-Z]/",
                "regex:/[a-z]/",
                "regex:/[0-9]/",
                "regex:/[\W]/",
            ],
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "name.required" => Lang::get('api.register.name.required'),
            "email.required" => Lang::get('api.register.email.required'),
            "email.email" => Lang::get('api.register.email.email'),
            "email.unique" => Lang::get('api.register.email.unique'),
            "phone.required" => Lang::get('api.register.phone.required'),
            "password.required" => Lang::get('api.register.password.required'),
            "password.regex" => Lang::get('api.register.password.regex'),
            "password.min" => Lang::get('api.register.password.min'),
        ];
    }
}
