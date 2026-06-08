<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
            'designation' => 'nullable',
            'image' => ['nullable','exclude_if:image,null'],
            'short_des' => 'nullable',
            'fb_link' => 'nullable',
            'tw_link' => 'nullable',
            'ingm_link' => 'nullable',
            'lnkd_link' => 'nullable',
            'duration' => 'nullable',
            'phone' => 'nullable',
        ];
    }
}
