<?php

namespace App\Http\Requests\Admin\Notice;

use App\Models\Notice;
use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreNoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('web')->check() || Auth::guard('admin')->check();
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
            : (Auth::guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(array_keys(Notice::TYPES))],
            'target_audience' => ['required', 'string', Rule::in(array_keys(Notice::AUDIENCES))],
            'academic_class_id' => [
                'nullable',
                'required_if:target_audience,'.Notice::AUDIENCE_CLASS_SPECIFIC,
                Rule::exists('academic_classes', 'id')->where('school_id', $schoolId),
            ],
            'is_pinned' => ['nullable', 'boolean'],
            'status' => ['required', 'string', Rule::in(array_keys(Notice::STATUSES))],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'content' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_pinned')) {
            $this->merge([
                'is_pinned' => filter_var($this->is_pinned, FILTER_VALIDATE_BOOLEAN),
            ]);
        } else {
            $this->merge([
                'is_pinned' => false,
            ]);
        }
    }

    /**
     * Custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'notice title',
            'type' => 'notice category',
            'target_audience' => 'target audience',
            'academic_class_id' => 'target academic class',
            'is_pinned' => 'pinned bulletin flag',
            'status' => 'publication status',
            'published_at' => 'publishing date',
            'expires_at' => 'expiration date',
            'content' => 'notice content',
            'attachment' => 'circular document attachment',
        ];
    }
}
