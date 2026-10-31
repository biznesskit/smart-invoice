<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class SaveBranchInsurance extends FormRequest
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
            'tracking_number' => 'required|string|unique:insurances',
            'insurance_company_code' => 'required|string|max:10',
            'insurance_company_name'=> 'required|string|max:100',
            'insurance_premium_rate'=> 'required|numeric|string|maxDigits:3',
            'used_unused'=> 'nullable|string|max:1|min:1',
        ];

    }
}
