<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone'  => 'required|numeric|unique:users,phone,' . $this->user->id,
            'id_no'  => 'nullable|numeric|unique:users,id_no,' . $this->user->id,
            'roles' => 'array|min:1',
            'media' => 'nullable|array',
            'branch_id' =>'nullable|numeric',
            'physical_address'=>'nullable|string'


        ];
    }
}
