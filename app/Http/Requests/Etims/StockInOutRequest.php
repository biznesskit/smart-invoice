<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class StockInOutRequest extends FormRequest
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
            'tracking_number' => 'required|string',
            "stored_and_released_type_code" => 'required|string|max:5',
            "registration_type_code" => 'required|string|max:5',
            "customer_id" => 'nullable|numeric',
            "occured_date_time" => 'required|string|min:8|max:8',
            "total_taxable_amount" => 'required|numeric',
            "total_tax_amount" => 'required|numeric',
            "total_amount" => 'required|numeric',
            "dispatching_branch_code" => 'nullable|string',
            "dispatching_branch_name" => 'nullable|string',
            'items' => 'array|required|min:1',
            'items.*.item_code' => 'nullable|string|max:20',
            'items.*.item_classification_code' => 'required|string|max:10',
            'items.*.item_name' => 'required|string|max:200',
            'items.*.barcode' => 'nullable|string|max:20',
            'items.*.packaging_unit' => 'required|numeric',
            'items.*.packaging_unit_code' => 'required|string|max:5',
            'items.*.quantity' => 'required|numeric',
            'items.*.quantity_unit_code' => 'required|string|max:5',
            'items.*.unit_price' => 'required|numeric',
            'items.*.supply_price' => 'required|numeric',
            'items.*.discount_rate' => 'nullable|numeric',
            'items.*.discount_amount' => 'nullable|numeric',
            'items.*.total_discount_amount' => 'nullable|numeric',
            'items.*.tax_type_code' => 'required|string|max:5',
            'items.*.taxable_amount' => 'required|numeric',
            'items.*.tax_amount' => 'required|numeric',
            'items.*.total_amount' => 'required|numeric',
            'items.*.remaining_quantity' => 'required|numeric',
        ];
    }
}
