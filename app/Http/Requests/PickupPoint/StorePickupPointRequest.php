<?php

namespace App\Http\Requests\PickupPoint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StorePickupPointRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name'      => ['required'],
            'phone'     => ['required'],
            'location'   => ['required'],
            'user_id'     => ['required'],
            'status'    => ['required'],
        ];
    }
}
