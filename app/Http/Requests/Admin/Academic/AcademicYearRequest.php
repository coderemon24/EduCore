<?php

namespace App\Http\Requests\Admin\Academic;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AcademicYearRequest extends FormRequest
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
        $academicYearId = $this->route('year') ? $this->route('year')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('academic_years', 'name')
                    ->where('school_id', $schoolId)
                    ->ignore($academicYearId),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'is_current' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'name' => 'session name / title',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'status' => 'session status',
            'notes' => 'session notes',
        ];
    }
}
