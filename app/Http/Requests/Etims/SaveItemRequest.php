<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveItemRequest extends FormRequest
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
            'tracking_number' => 'required|string|unique:items',
            "item_code" => 'required|string|unique:items|max:20',
            "item_classification_code" => 'required|string|max:10',
            "item_type_code" => 'required|string|max:5',
            "item_name" => 'required|string|max:200',
            "item_standard_name" => 'nullable|string|max:200',
            "country_of_origin_code" => 'required|string|max:5',
            "packaging_unit" => 'required|numeric',
            "packaging_unit_code" => 'required|string|max:255',
            "quantity_unit_code" => 'required|string|max:5',
            "tax_type_code" => 'required|string|max:5',
            "type" => 'required|string',
            "batch_number" => 'nullable|string|max:10',
            "barcode" => 'nullable|string|max:255',
            "default_unit_price" => 'required|numeric',
            "group_1_price" => 'nullable|numeric',
            "group_2_price" => 'nullable|numeric',
            "group_3_price" => 'nullable|numeric',
            "group_4_price" => 'nullable|numeric',
            "group_5_price" => 'nullable|numeric',
            "additional_information" => 'nullable|string|max:7',
            "safety_quantity" => 'nullable|numeric',
            "insurance_applicable" => 'nullable|string|max:1',
            "used_unused" => 'nullable|string|max:1',
            "opening_balance" => "nullable|numeric",
            "taxable_amount" => Rule::when($this->opening_balance != null, 'required|numeric'),
            "tax_amount" => Rule::when($this->opening_balance != null, 'required|numeric'),
            "total_amount" => Rule::when($this->opening_balance != null, 'required|numeric'),
            "stock_tracking_number" => Rule::when($this->opening_balance != null, 'required|string'),
            "stock_balance_reason" => "nullable|string|max:255",
        ];
    }
}
