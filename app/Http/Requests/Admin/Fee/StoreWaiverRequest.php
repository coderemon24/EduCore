<?php

namespace App\Http\Requests\Admin\Fee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWaiverRequest extends FormRequest
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
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'in:fixed,percentage'],
            'rate' => ['nullable', 'numeric', 'min:0.01', 'max:100'],
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ];
    }
}
