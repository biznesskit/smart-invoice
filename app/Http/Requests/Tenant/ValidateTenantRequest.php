<?php

namespace App\Http\Requests\Tenant;

use App\Helpers\Utilities;
use Illuminate\Foundation\Http\FormRequest;

class ValidateTenantRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'business_phone' => Utilities::cleanPhoneNumber($this['business_phone']),
             'kra_pin' => strtoupper($this['kra_pin']),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'business_type' => 'required|string',
            'tracking_number' => 'nullable|string',
            'kra_pin' => 'required|string|unique:landlord.tenants|min:11|max:11|regex:/^[A-Z]{1}[0-9]{9}[A-Z]{1}+$/',
            'kra_pin' => [    'required',    'string',    'unique:landlord.tenants',    'regex:/^[A-Z][0-9]{9}[A-Z]$/'],

            'referral_code' => 'nullable|string',
            'business_phone'=>'nullable|string|unique:landlord.tenants,business_phone',
            'business_email'=>'nullable|email|unique:landlord.tenants,business_email'
        ];
    }


}
