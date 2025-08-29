<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacancySaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vacancy_title' => 'required|array',
            'vacancy_location' => 'required|array',
            'description' => 'required|array',
            'slug' => 'required|array',
            'vacany_start_at' => 'required|date',
            'vacany_expired_at' => 'required|date|after_or_equal:vacany_start_at',
        ];
    }

    public function messages()
    {
        return [
            'vacancy_title.required' => 'Vakansiya adı tələb olunur',
            'vacancy_location.required' => 'Ünvan tələb olunur',
            'description.required' => 'Təsvir tələb olunur',
            'slug.required' => 'Slug tələb olunur',
            'vacany_start_at.required' => 'Başlama tarixi tələb olunur',
            'vacany_start_at.date' => 'Başlama tarixi düzgün tarix formatında olmalıdır',
            'vacany_expired_at.required' => 'Bitmə tarixi tələb olunur',
            'vacany_expired_at.date' => 'Bitmə tarixi düzgün tarix formatında olmalıdır',
            'vacany_expired_at.after_or_equal' => 'Bitmə tarixi başlama tarixindən sonra və ya eyni olmalıdır',
            'image.image' => 'Şəkil formatı düzgün deyil',
            'image.mimes' => 'Şəkil jpeg, png, jpg və ya gif formatında olmalıdır',
            'image.max' => 'Şəkil 2MB-dan böyük ola bilməz',
            'file.mimes' => 'Fayl pdf, doc və ya docx formatında olmalıdır',
            'file.max' => 'Fayl 5MB-dan böyük ola bilməz',
        ];
    }
}