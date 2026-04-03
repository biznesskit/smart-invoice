<?php

namespace App\Http\Requests\Etims;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCreditNoteRequest extends FormRequest
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

         $branchId = null;
        if ($this->tracking_number) {
            $branchId = Branch::where('tracking_number', $this->tracking_number)->value('branch_id');
        }


        return [
            'tracking_number' => 'required|string|unique:invoices',
            'stock_tracking_number' => 'required|string',
            // 'purchase_invoice_number' => 'required|numeric|maxDigits:50',
            // 'invoice_number'=> 'required|numeric|unique:invoices',
            // 'invoice_number' => [
            //     'required',
            //     'numeric'
                // Rule::unique('invoices')->where(fn ($query) =>
                //     $query->where('branch_id', $branchId)
                // )
            // ],
            'original_invoice_number'=> 'required|numeric',
            // 'customer_id'=> 'nullable|numeric',
            // 'customer_kra_pin'=> 'nullable|string|min:11|max:11',
            // 'supplier_kra_pin'=> 'nullable|string|min:11|max:11',
            // 'customer_name'=> 'nullable|string|max:60',
            'sales_type_code'=> 'required|string|max:5',
            'sale_status_code'=> 'required|string|max:5',
            'receipt_type_code'=> 'required|string|max:5',
            'payment_type_code'=> 'required|string|max:5',
            'validated_date' => 'required|string|max:14',
            'sale_date' => 'required|string|max:8',
            'stock_released_date' => 'nullable|string|max:14',
            'cancel_requested_date'=> 'nullable|string|max:14',
            'canceled_date'=> 'nullable|string|max:14',
            'credit_note_date'=> 'nullable|string|max:14',
            'credit_note_reason_code'=> 'nullable|string|max:5',
            'taxable_amount_A'=> 'required|numeric',
            'taxable_amount_B'=> 'required|numeric',
            'taxable_amount_C1'=> 'required|numeric',
            'taxable_amount_C2'=> 'required|numeric',
            'taxable_amount_C3'=> 'required|numeric',
            'taxable_amount_D'=> 'required|numeric',
            'taxable_amount_Rvat'=> 'required|numeric',
            'taxable_amount_E'=> 'required|numeric',
            'taxable_amount_Tot'=> 'required|numeric',
            'tax_rate_A'=> 'required|numeric',
            'tax_rate_B'=> 'required|numeric',
            'tax_rate_C1'=> 'required|numeric',
            'tax_rate_C2'=> 'required|numeric',
            'tax_rate_C3'=> 'required|numeric',
            'tax_rate_D'=> 'required|numeric',
            'tax_rate_Rvat'=> 'required|numeric',
            'tax_rate_E'=> 'required|numeric',
            'tax_rate_Tot'=> 'required|numeric',
            'tax_amount_A'=> 'required|numeric',
            'tax_amount_B'=> 'required|numeric',
            'tax_amount_C1'=> 'required|numeric',
            'tax_amount_C2'=> 'required|numeric',
            'tax_amount_C3'=> 'required|numeric',
            'tax_amount_D'=> 'required|numeric',
            'tax_amount_Rvat'=> 'required|numeric',
            'tax_amount_E'=> 'required|numeric',
            'tax_amount_Tot'=> 'required|numeric',
            'total_taxable_amount'=> 'required|numeric',
            'total_tax_amount'=> 'required|numeric',
            'total_amount'=> 'required|numeric',
            'purchase_acceptance_status'=> 'required|string|max:1',
            'trade_name'=> 'nullable|string|max:20',
            'address'=> 'nullable|string|max:200',
            'top_message'=> 'nullable|string|max:20',
            'bottom_message'=> 'nullable|string|max:20',
            'remark'=> 'nullable|string|max:400',
            "item_list" => 'required|array|min:1',
            'item_list.*.item_classification_code' => 'required|string|max:10',
            'item_list.*.item_code' => 'required|string|max:20',
            'item_list.*.tracking_number' => 'required|string|max:255',
            'item_list.*.item_name' => 'required|string|max:200',
            'item_list.*.barcode' => 'nullable|string|max:20',
            'item_list.*.packaging_unit_code' => 'required|string|max:5',
            'item_list.*.packaging_unit' => 'required|numeric',
            'item_list.*.quantity_unit_code' => 'required|string|max:5',
            // 'item_list.*.quantity_unit' => 'required|numeric',
            'item_list.*.unit_price' => 'required|numeric',
            'item_list.*.supply_amount' => 'required|numeric',
            'item_list.*.discount_rate' => 'required|numeric',
            'item_list.*.discount_amount' => 'required|numeric',
            'item_list.*.insurance_company_code' => 'nullable|string|max:10',
            'item_list.*.insurance_company_name' => 'nullable|string|max:100',
            'item_list.*.insurance_rate' => 'nullable|numeric',
            'item_list.*.insurance_amount' => 'nullable|numeric',
            'item_list.*.tax_type_code' => 'required|string|max:5',
            'item_list.*.taxable_amount' => 'required|numeric',
            'item_list.*.tax_amount' => 'required|numeric',
            'item_list.*.total_amount' => 'required|numeric',
            "item_list.*.remaining_quantity" => 'required|numeric',
        ];
    }
}
