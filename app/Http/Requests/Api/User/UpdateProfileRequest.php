<?php
namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:255'],
            'email' => [
                'sometimes', 
                'email', 
                'unique:users,email,' . auth()->id()
            ],
            'phone' => [
                'sometimes', 
                'string', 
                'unique:users,phone,' . auth()->id()
            ],

            // Şifrə dəyişikliyi üçün əlavə validasiya
            'old_password' => [
                'required_with:new_password', 
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Cari şifrə düzgün deyil');
                    }
                }
            ],
            'new_password' => [
                'required_with:old_password',
                'different:old_password', // Köhnə şifrədən fərqli olmalı
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised() // Sızıntı olan şifrələri yoxlayır
            ],
            'password_confirmation' => [
                'required_with:new_password', 
                'same:new_password' // Təkrar şifrə eyni olmalı
            ]
        ];
    }

    public function messages()
    {
        return [
            // Profil məlumatları üçün mesajlar
            'name.min' => 'Ad ən azı 2 simvol olmalıdır',
            'name.max' => 'Ad ən çox 255 simvol ola bilər',
            
            // Şifrə üçün mesajlar
            'old_password.required_with' => 'Cari şifrə tələb olunur',
            'new_password.required_with' => 'Yeni şifrə tələb olunur',
            'new_password.different' => 'Yeni şifrə köhnə şifrədən fərqli olmalıdır',
            'password_confirmation.same' => 'Şifrə təsdiqi düzgün deyil',
            
            // Şifrənin güclülüyü üçün mesajlar
            'new_password.min' => 'Şifrə ən azı 8 simvol olmalıdır',
            'new_password.letters' => 'Şifrə hərfləri ehtiva etməlidir',
            'new_password.mixed' => 'Şifrə böyük və kiçik hərfləri ehtiva etməlidir',
            'new_password.numbers' => 'Şifrə rəqəmləri ehtiva etməlidir',
            'new_password.symbols' => 'Şifrə xüsusi simvolları ehtiva etməlidir',
            'new_password.uncompromised' => 'Bu şifrə təhlükəsizdir'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->checkPasswordComplexity($validator);
        });
    }

    private function checkPasswordComplexity($validator)
    {
        $newPassword = $this->input('new_password');
        
        if ($newPassword) {
            $complexityChecks = [
                'Şifrədə ən azı bir böyük hərf olmalıdır' => 
                    preg_match('/[A-Z]/', $newPassword),
                
                'Şifrədə ən azı bir kiçik hərf olmalıdır' => 
                    preg_match('/[a-z]/', $newPassword),
                
                'Şifrədə ən azı bir rəqəm olmalıdır' => 
                    preg_match('/[0-9]/', $newPassword),
                
                'Şifrədə ən azı bir xüsusi simvol olmalıdır' => 
                    preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\\|,.<>\/?]/', $newPassword)
            ];

            foreach ($complexityChecks as $message => $passes) {
                if (!$passes) {
                    $validator->errors()->add('new_password', $message);
                }
            }
        }
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();
        
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'message' => $errors[0], 
                'errors' => $errors 
            ], 422)
        );
    }
}
