<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
           'name.required' => 'The product name is required.',
           'slug.required' => 'The product slug is required.',
           'image.required' => 'The product image is required.',
           'category_id.required' => 'The category is required.',
           'price.required' => 'The price is required.',
        ];
    }
}
