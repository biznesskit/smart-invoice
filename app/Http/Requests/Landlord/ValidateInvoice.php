<?php

namespace App\Http\Requests\Landlord;

use App\Helpers\Landlord;
use Illuminate\Foundation\Http\FormRequest;

class ValidateInvoice extends FormRequest
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
            'amount_recieved' => 'required|numeric',
            'payment_method' => 'required|string',
            'transaction_reference' => 'required|string ', //|unique:payments',
            'due_date' => 'nullable|date',
            'send_notification' => 'nullable'
        ];
    }
}
