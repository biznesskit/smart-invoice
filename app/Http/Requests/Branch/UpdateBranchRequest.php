<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'name' => 'required|string|unique:branches,name,' . $this->branch->id,
            'location' => 'nullable|string',
            'mpesa_till_no' => 'nullable|string',
            'mpesa_paybill_no' => 'nullable|string',
            'mpesa_paybill_account_no' => 'nullable|string',
            'bank_account_no' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'media' => 'nullable|array|min:1',
            'opening_hrs' => 'nullable|string',
            'closing_hrs' => 'nullable|string',
            'kra_pin' => 'nullable|string',
            'etims_branch_code' => 'nullable|string',
            'etims_cmc_key' => 'nullable|string',
            'etims_device_serial_number' => 'nullable|string',
            'incharge_of_stock_transfers'=>'nullable|numeric',
            
            'stock_transfer_staff' => 'nullable|numeric',


           

        ];
    }
}
