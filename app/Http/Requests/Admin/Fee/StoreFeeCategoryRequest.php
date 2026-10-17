<?php

namespace App\Http\Requests\Admin\Fee;

use App\Models\School;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeCategoryRequest extends FormRequest
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
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);
        $categoryId = $this->route('category')?->id ?? $this->route('category');

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('fee_categories', 'code')
                    ->where(function ($query) use ($schoolId) {
                        if ($schoolId) {
                            $query->where('school_id', $schoolId);
                        }
                    })
                    ->ignore($categoryId),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
