<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class SaveBranchUserRequest extends FormRequest
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
            'tracking_number' => 'required|string|unique:users',
            'username' => 'required|string|unique:users|max:60',
            'address'=> 'nullable|string|max:300',
            'contact'=> 'numeric|string|maxDigits:20',
            'used_unused'=> 'nullable|string|max:1|min:1',
            'remark'=> 'nullable|string|max:2000', 
            'password'=> 'required|string|max:255', 
            'authority_code'=> 'nullable|string|max:100', 
        ];
    }
}
