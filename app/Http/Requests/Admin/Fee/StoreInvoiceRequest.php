<?php

namespace App\Http\Requests\Admin\Fee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
        $rules = [
            'generation_type' => ['required', 'in:single,bulk'],
            'title' => ['required', 'string', 'max:150'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->input('generation_type') === 'single') {
            $rules['student_id'] = ['required', 'exists:students,id'];
            $rules['items'] = ['required', 'array', 'min:1'];
            $rules['items.*.fee_category_id'] = ['required', 'exists:fee_categories,id'];
            $rules['items.*.name'] = ['required', 'string', 'max:150'];
            $rules['items.*.amount'] = ['required', 'numeric', 'min:0.01', 'max:99999999.99'];
        } else {
            $rules['academic_year_id'] = ['required', 'exists:academic_years,id'];
            $rules['academic_class_id'] = ['required', 'exists:academic_classes,id'];
            $rules['section_id'] = ['nullable', 'exists:sections,id'];
            $rules['fee_structure_ids'] = ['required', 'array', 'min:1'];
            $rules['fee_structure_ids.*'] = ['exists:fee_structures,id'];
        }

        return $rules;
    }
}
