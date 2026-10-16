<?php

namespace App\Http\Requests\Admin\Academic;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
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
        $sectionId = $this->route('section') ? $this->route('section')->id : null;
        $classId = $this->input('academic_class_id');

        return [
            'academic_class_id' => [
                'required',
                'integer',
                Rule::exists('academic_classes', 'id')->where('school_id', $schoolId),
            ],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sections', 'name')
                    ->where('school_id', $schoolId)
                    ->where('academic_class_id', $classId)
                    ->ignore($sectionId),
            ],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,inactive'],
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
            'academic_class_id' => 'academic class / grade',
            'name' => 'section name / identifier',
            'capacity' => 'student capacity',
            'room_number' => 'room / hall number',
            'status' => 'section status',
        ];
    }
}
