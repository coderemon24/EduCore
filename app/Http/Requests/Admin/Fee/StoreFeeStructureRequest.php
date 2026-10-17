<?php

namespace App\Http\Requests\Admin\Fee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeeStructureRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'academic_class_id' => ['nullable', 'exists:academic_classes,id'],
            'fee_category_id' => ['required', 'exists:fee_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'frequency' => ['required', 'in:one_time,monthly,quarterly,term,yearly'],
            'due_day' => ['required', 'integer', 'min:1', 'max:31'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
