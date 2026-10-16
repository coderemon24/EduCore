<?php

namespace App\Http\Requests\Admin\Academic;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AcademicGroupRequest extends FormRequest
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
        $groupId = $this->route('group') ? $this->route('group')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('academic_groups', 'name')
                    ->where('school_id', $schoolId)
                    ->ignore($groupId),
            ],
            'code' => ['nullable', 'string', 'max:32'],
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
            'name' => 'group / stream title',
            'code' => 'stream code',
            'status' => 'status',
            'description' => 'description',
        ];
    }
}
