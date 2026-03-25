<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Utilities;
use Illuminate\Validation\Rule;

class UpdateAddon extends FormRequest
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
            'name' => ['required',Rule::unique('addons')->ignore($this->addon)],
            'price' => 'required|numeric',
            'validity' => 'required|numeric',
            'description' => 'string|nullable'
        ];
    }

   

}
