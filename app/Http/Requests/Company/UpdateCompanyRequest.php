<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            // 'tenant_id' => 'nullable|string',
            'name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'business_type_name' => 'nullable|string',
            'country' => 'nullable|string',
            'country_name' => 'nullable|string',
            'email' => 'nullable|email|string',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
            'kra_pin' => 'nullable|string',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'send_report_time' => 'nullable|string',
        ];
    }
}
