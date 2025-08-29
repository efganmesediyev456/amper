<?php

namespace App\Http\Requests\Backend\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class CatalogSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required',
            'status' => 'nullable',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'file' => 'nullable|mimes:pdf,doc,docx',
            'date' => 'required|date'
        ];
    }
}