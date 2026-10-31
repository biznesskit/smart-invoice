<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class SaveBranchCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'kra_pin' => strtoupper($this->kra_pin),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tracking_number' => 'required|string|unique:customers',
            'kra_pin' => 'required|string|unique:customers|min:10|max:10',
            'name' => 'required|string|max:60',
            'address'=> 'nullable|string|max:300',
            'phone'=> 'nullable|numeric|string|maxDigits:20',
            'email'=> 'nullable|string|max:50',
            'fax_number'=> 'nullable|string|max:20',
            'used_unused'=> 'nullable|string|max:1|min:1',
            'remark'=> 'nullable|string|max:1000',
        ];
    }
}
