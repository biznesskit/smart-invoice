<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Utilities;

class ValidateSubscriptionRequest extends FormRequest
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
            'method' => 'required|max:255',
            'phone' => 'required_if:method,==,mpesa|numeric|digits_between:10,15',
            'package_id' => 'required|numeric',
            'amount_due' => 'required|numeric',
            'addon_ids' => 'nullable|array',
            'no_of_users' => 'required|numeric',
            'no_of_branches' => 'required|numeric',
            'validity_in_days' => 'required|numeric',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => Utilities::cleanPhoneNumber($this->phone),
        ]);
    }

}
