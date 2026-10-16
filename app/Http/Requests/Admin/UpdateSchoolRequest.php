<?php

namespace App\Http\Requests\Admin;

use App\Enums\SchoolStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateSchoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $admin = Auth::guard('admin')->user();

        return $admin && $admin->role === 'super_admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schoolId = $this->route('school')?->id ?? $this->route('school');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:32',
                Rule::unique('schools', 'code')->ignore($schoolId)->whereNull('deleted_at'),
            ],
            'status' => ['required', new Enum(SchoolStatus::class)],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'established_year' => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
            'timezone' => ['required', 'string', 'max:50', 'timezone:all'],
            'currency' => ['required', 'string', 'max:10'],
            'logo' => ['nullable', 'string', 'max:255'],
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
            'name' => 'institution name',
            'code' => 'campus code',
            'email' => 'official email',
            'phone' => 'phone number',
            'postal_code' => 'postal / ZIP code',
            'established_year' => 'established year',
        ];
    }
}
