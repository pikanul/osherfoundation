<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:admins,email',
                    'password' => 'required|string|min:8|confirmed',
                    'mobile' => "required|digits:11|regex:/(01)[0-9]{9}/|unique:admins,mobile",
                    'roles' => 'required|array'
                ];
                break;

            case 'PATCH':
            case 'PUT':
                return [
                    'id'=>'required|numeric',
                    'name' => 'required|string',
                    'email' => "required|email|unique:admins,email,{$this->id}",
                    'mobile' => "required|digits:11|regex:/(01)[0-9]{9}/|unique:admins,mobile,{$this->id}",
                    'roles' => 'required|array',
                    'password' => 'nullable|string|min:8|confirmed',
                    'is_active'=>"required",Rule::in([0, 1]),
                ];
                break;
        }
    }
}
