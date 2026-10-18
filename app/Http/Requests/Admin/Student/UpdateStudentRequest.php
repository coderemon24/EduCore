<?php

namespace App\Http\Requests\Admin\Student;

use App\Models\School;
use App\Models\Student;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        $student = $this->route('student');
        $studentId = $student instanceof Student ? $student->id : $student;

        return [
            // Core Identity
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'admission_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'admission_number')
                    ->where('school_id', $schoolId)
                    ->ignore($studentId),
            ],
            'admission_date' => ['required', 'date'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'blood_group' => ['nullable', 'string', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'religion' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive,graduated,transferred,suspended'],
            'optional_subject_id' => [
                'nullable',
                'integer',
                Rule::exists('subjects', 'id')->where('school_id', $schoolId),
            ],

            // Guardian Details
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'guardian_relation' => ['nullable', 'string', 'in:father,mother,legal_guardian,other'],
            'guardian_email' => ['nullable', 'email', 'max:100'],
            'guardian_occupation' => ['nullable', 'string', 'max:100'],
            'guardian_national_id' => ['nullable', 'string', 'max:50'],
            'guardian_address' => ['nullable', 'string', 'max:500'],

            // Residential Addresses
            'current_address' => ['nullable', 'string', 'max:500'],
            'permanent_address' => ['nullable', 'string', 'max:500'],
        ];
    }
}
