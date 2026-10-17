<?php

namespace App\Http\Requests\Admin\Attendance;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentAttendanceRequest extends FormRequest
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
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->user()?->school_id ?? session('current_school_id') ?? School::value('id'));

        return [
            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')->where(function ($query) use ($schoolId) {
                    if ($schoolId) {
                        $query->where('school_id', $schoolId);
                    }
                }),
            ],
            'academic_class_id' => [
                'required',
                'integer',
                Rule::exists('academic_classes', 'id')->where(function ($query) use ($schoolId) {
                    if ($schoolId) {
                        $query->where('school_id', $schoolId);
                    }
                }),
            ],
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(function ($query) use ($schoolId) {
                    $query->where('academic_class_id', $this->input('academic_class_id'));
                    if ($schoolId) {
                        $query->where('school_id', $schoolId);
                    }
                }),
            ],
            'attendance_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'records' => ['required', 'array', 'min:1'],
            'records.*.student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')->where(function ($query) use ($schoolId) {
                    if ($schoolId) {
                        $query->where('school_id', $schoolId);
                    }
                }),
            ],
            'records.*.status' => [
                'required',
                'string',
                Rule::in(['present', 'absent', 'late', 'half_day', 'excused']),
            ],
            'records.*.remarks' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'attendance_date.before_or_equal' => 'Attendance cannot be marked for future dates.',
            'records.required' => 'No student roster was provided to record attendance.',
            'records.*.status.in' => 'Attendance status must be Present, Absent, Late, Half-Day, or Excused.',
        ];
    }
}
