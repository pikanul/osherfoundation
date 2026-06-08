<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagementRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'designation' => 'required',
            'description' => 'nullable',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
            'type' => 'required',
            'image' => ['nullable','exclude_if:image,null'],
        ];
    }
}
