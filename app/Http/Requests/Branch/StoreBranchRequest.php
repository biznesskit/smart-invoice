<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
            'tracking_number' => 'required|string|unique:branches',
            'name' => 'required|string|unique:branches',
            'location' => 'nullable|string',
            "etims_branch_id" =>'nullable|string',
            "etims_branch_name" =>'nullable|string',
            "etims_branch_status_code" =>'nullable|string',
        ];
    }
}
