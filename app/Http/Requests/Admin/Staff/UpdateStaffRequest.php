<?php

namespace App\Http\Requests\Admin\Staff;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
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
        $staffId = $this->route('staff')?->id ?? $this->route('staff');

        return [
            // Core Identity
            'staff_code' => [
                'required',
                'string',
                'max:32',
                Rule::unique('staff', 'staff_code')->where('school_id', $schoolId)->ignore($staffId),
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('staff', 'email')->where('school_id', $schoolId)->ignore($staffId),
            ],
            'phone' => ['nullable', 'string', 'max:32'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],

            // Employment Details
            'is_teaching' => ['required', 'boolean'],
            'department' => ['required', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'joining_date' => ['required', 'date'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'contract_type' => ['required', 'string', 'in:permanent,probation,contract,part_time'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
            'current_address' => ['nullable', 'string', 'max:1000'],
            'permanent_address' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            // User account update if provided
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => [
                'nullable',
                Rule::exists('roles', 'id')->where('school_id', $schoolId),
            ],
        ];
    }
}
