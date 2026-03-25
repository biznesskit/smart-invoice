<?php

namespace App\Http\Requests\Etims;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImportItemRequest extends FormRequest
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
            "task_code" => 'required|string|max:50',
            "declaration_date" => 'required|string|max:8',
            "item_sequence" => 'numeric|required|maxDigits:10',
            "hs_code" => 'required|string|max:17',
            "item_classification_code" => 'required|string|max:10',
            "item_code" => 'required|string|max:20',
            "import_item_status_code" => 'required|string|max:5',
            "item_name" => 'required|string|max:500',
            "remark" => 'nullable|string|max:400',
        ];
    }
}
