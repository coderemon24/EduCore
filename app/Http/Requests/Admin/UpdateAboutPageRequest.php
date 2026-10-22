<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAboutPageRequest extends FormRequest
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
        return [
            // Header & Hero
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'established_year' => 'nullable|string|max:50',
            'banner_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',

            // History & Narrative
            'history_heading' => 'nullable|string|max:255',
            'history_content' => 'nullable|string',
            'history_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',

            // Vision, Mission & Values
            'vision_title' => 'nullable|string|max:255',
            'vision_text' => 'nullable|string',
            'mission_title' => 'nullable|string|max:255',
            'mission_text' => 'nullable|string',
            'values_title' => 'nullable|string|max:255',
            'values_list' => 'nullable|array',
            'values_list.*' => 'nullable|string|max:255',

            // Repeaters (Array validation)
            'milestones' => 'nullable|array',
            'milestones.*.year' => 'nullable|string|max:50',
            'milestones.*.title' => 'nullable|string|max:255',
            'milestones.*.description' => 'nullable|string',

            'statistics' => 'nullable|array',
            'statistics.*.label' => 'nullable|string|max:100',
            'statistics.*.value' => 'nullable|string|max:50',
            'statistics.*.note' => 'nullable|string|max:255',

            'at_a_glance' => 'nullable|array',
            'at_a_glance.*.key' => 'nullable|string|max:100',
            'at_a_glance.*.value' => 'nullable|string|max:255',

            'committee_members' => 'nullable|array',
            'committee_members.*.name' => 'nullable|string|max:255',
            'committee_members.*.designation' => 'nullable|string|max:255',
            'committee_members.*.role' => 'nullable|string|max:255',

            'facilities' => 'nullable|array',
            'facilities.*.title' => 'nullable|string|max:255',
            'facilities.*.description' => 'nullable|string',
            'facilities.*.icon' => 'nullable|string|max:100',

            // Dynamic SEO & Social Graph
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];
    }
}
