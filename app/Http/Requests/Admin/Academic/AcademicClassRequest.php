<?php

namespace App\Http\Requests\Admin\Academic;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AcademicClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (School::value('id') ?? 1);
        $classId = $this->route('class') ? $this->route('class')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('academic_classes', 'name')
                    ->where('school_id', $schoolId)
                    ->ignore($classId),
            ],
            'numeric_code' => ['nullable', 'integer', 'min:0', 'max:100'],
            'order_index' => ['required', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'class name / grade title',
            'numeric_code' => 'numeric grade code',
            'order_index' => 'sorting order index',
            'status' => 'class status',
            'description' => 'description',
        ];
    }
}
