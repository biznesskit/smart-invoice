<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class SaveItemCompositionRequest extends FormRequest
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
            'tracking_number' => 'required|unique:item_compositions',
            "composition_item_code" => 'required|string|max:20',
            "composition_item_name" => 'required|string|max:255',
            "total_taxable_amount" => "required|numeric",
            "total_tax_amount" => "required|numeric",
            "total_amount" => "required|numeric",
            "item_list" => 'required|array|min:1',
            'item_list.*.item_code' => 'required|string|max:20',
            "item_list.*.quantity" => 'required|numeric',
            "item_list.*.remaining_quantity" => 'required|numeric',
            'item_list.*.unit_price' => 'required|numeric',
            'item_list.*.discount_rate' => 'required|numeric',
            'item_list.*.discount_amount' => 'required|numeric',
            'item_list.*.tracking_number' => 'required',
            'item_list.*.taxable_amount' => 'required|numeric',
            'item_list.*.tax_amount' => 'required|numeric',
            'item_list.*.total_amount' => 'required|numeric',
        ];
    }
}
