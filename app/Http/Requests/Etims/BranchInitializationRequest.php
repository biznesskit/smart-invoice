<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class BranchInitializationRequest extends FormRequest
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
            'kra_pin'=> 'nullable|string|min:10|max:10', // tobe deprecated
            'company_kra_pin'=> 'nullable|string|min:10|max:10',
            'branch_code'=>'numeric|required|digits:3',
            'device_serial_number'=>'string|required',
            'solution_type'=>'string|nullable',
        ];
    }
}
