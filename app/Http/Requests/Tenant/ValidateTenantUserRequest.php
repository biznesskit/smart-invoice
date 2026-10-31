<?php

namespace App\Http\Requests\Tenant;

use App\Helpers\Utilities;
use Illuminate\Foundation\Http\FormRequest;

class ValidateTenantUserRequest extends FormRequest
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
            'phone' => Utilities::cleanPhoneNumber($this['phone']),
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
            'tracking_number' => 'nullable|string',
            'first_name'=> 'required|string',
            'tenant_id'=> 'nullable|numeric',
            'company_pin'=> 'nullable|string',
            'last_name'=> 'nullable|string',
            'email' => 'required|email|unique:landlord.tenants,business_email',
        
            'phone' => 'nullable|numeric|unique:landlord.tenants,business_phone',
            'password' => 'required|string',
            // 'hashed' => 'nullable|numeric'
        ];
    }
}
