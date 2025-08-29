<?php

namespace App\Http\Requests\Backend\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductSaveRequest extends FormRequest
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
            "title.*"=>"required",
            "product_code"=>"required",
            "price"=>"required",
            "quantity"=>"required",
            "category_id"=>"required"
        ];
    }
}
