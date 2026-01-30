<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetails extends FormRequest
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
            'first'=>[],
            'second'=>[],
            'third'=>[],
            'fourth'=>[],
            'name'=>['string'],
            'price'=>['numeric'],
            'description'=>['string','max:255'],
            'category_id'=>['numeric'],
            'image'=>[],
            'size'=>['string'],
            'color'=>['string'],
            'total'=>['numeric'],
            'stock'=>['numeric'],
            'additional_info'=>['string','max:255'],
            'rate'=>['numeric']
        ];
    }
    public function messages()
    {
        return [];
    }
}
