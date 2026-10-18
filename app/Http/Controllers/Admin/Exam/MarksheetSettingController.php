<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\MarksheetSetting;
use App\Models\School;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MarksheetSettingController extends Controller
{
    /**
     * Display mark sheet template and signature settings.
     */
    public function index(): View
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        $setting = MarksheetSetting::firstOrCreate(
            ['school_id' => $schoolId],
            [
                'headmaster_title' => 'Head Master',
                'show_highest_marks' => true,
                'show_attendance_report' => true,
                'show_extra_activities' => true,
                'show_gpa_grading_scale' => true,
                'padding_top' => '10mm',
                'padding_right' => '12mm',
                'padding_bottom' => '10mm',
                'padding_left' => '12mm',
            ]
        );

        return view('admin.exams.settings.marksheet', compact('setting'));
    }

    /**
     * Update mark sheet configuration and upload background / signature assets.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'headmaster_title' => ['required', 'string', 'max:100'],
            'header_title' => ['nullable', 'string', 'max:255'],
            'sub_header_title' => ['nullable', 'string', 'max:255'],
            'show_highest_marks' => ['nullable', 'boolean'],
            'show_attendance_report' => ['nullable', 'boolean'],
            'show_extra_activities' => ['nullable', 'boolean'],
            'show_gpa_grading_scale' => ['nullable', 'boolean'],
            'padding_top' => ['nullable', 'string', 'max:20'],
            'padding_right' => ['nullable', 'string', 'max:20'],
            'padding_bottom' => ['nullable', 'string', 'max:20'],
            'padding_left' => ['nullable', 'string', 'max:20'],
            'custom_css' => ['nullable', 'string', 'max:2000'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'school_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'principal_signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        $setting = MarksheetSetting::firstOrCreate(['school_id' => $schoolId]);

        $data = [
            'headmaster_title' => $validated['headmaster_title'],
            'header_title' => $validated['header_title'] ?? null,
            'sub_header_title' => $validated['sub_header_title'] ?? null,
            'show_highest_marks' => ! empty($validated['show_highest_marks']),
            'show_attendance_report' => ! empty($validated['show_attendance_report']),
            'show_extra_activities' => ! empty($validated['show_extra_activities']),
            'show_gpa_grading_scale' => ! empty($validated['show_gpa_grading_scale']),
            'padding_top' => $validated['padding_top'] ?? '10mm',
            'padding_right' => $validated['padding_right'] ?? '12mm',
            'padding_bottom' => $validated['padding_bottom'] ?? '10mm',
            'padding_left' => $validated['padding_left'] ?? '12mm',
            'custom_css' => $validated['custom_css'] ?? null,
        ];

        // Handle File Uploads
        $uploadDir = public_path('uploads/marksheet');
        if (! File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = 'bg_'.$schoolId.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $data['background_image_path'] = 'uploads/marksheet/'.$filename;
        }

        if ($request->hasFile('school_logo')) {
            $file = $request->file('school_logo');
            $filename = 'logo_'.$schoolId.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $data['school_logo_path'] = 'uploads/marksheet/'.$filename;
        }

        if ($request->hasFile('principal_signature')) {
            $file = $request->file('principal_signature');
            $filename = 'sig_'.$schoolId.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $data['principal_signature_path'] = 'uploads/marksheet/'.$filename;
        }

        $setting->update($data);

        ToastMagic::success('Marksheet & ID Card settings updated successfully.');

        return redirect()->route('admin.exams.settings.marksheet');
    }
}
