<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'tenant_id' => 'required|string',
            'name' => 'required|string',
            'business_type' => 'required|string',
            'business_type_name' => 'required|string',
            'country' => 'required|string',
            'country_name' => 'required|string',
        ];
    }
}
