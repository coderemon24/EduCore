<?php

namespace App\Http\Requests\Admin\Academic;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClassSubjectAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() || Auth::guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (School::value('id') ?? 1);

        return [
            'academic_class_id' => [
                'required',
                'integer',
                Rule::exists('academic_classes', 'id')->where('school_id', $schoolId),
            ],
            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => [
                'integer',
                Rule::exists('subjects', 'id')->where('school_id', $schoolId),
            ],
            'academic_year_id' => [
                'nullable',
                'integer',
                Rule::exists('academic_years', 'id')->where('school_id', $schoolId),
            ],
            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('staff', 'id')->where('school_id', $schoolId)->where('is_teaching', 1),
            ],
            'type' => ['required', 'string', 'in:compulsory,optional,elective'],
            'full_marks' => ['required', 'integer', 'min:1', 'max:500'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:full_marks'],
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
            'academic_class_id' => 'academic class',
            'subject_ids' => 'assigned subjects',
            'academic_year_id' => 'academic year session',
            'type' => 'curriculum assignment type',
            'full_marks' => 'full marks',
            'pass_marks' => 'passing marks threshold',
        ];
    }
}
