<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksheetSetting extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'marksheet_settings';

    protected $fillable = [
        'school_id',
        'background_image_path',
        'school_logo_path',
        'principal_signature_path',
        'headmaster_title',
        'show_highest_marks',
        'show_attendance_report',
        'show_extra_activities',
        'show_gpa_grading_scale',
        'header_title',
        'sub_header_title',
        'custom_css',
    ];

    protected $casts = [
        'show_highest_marks' => 'boolean',
        'show_attendance_report' => 'boolean',
        'show_extra_activities' => 'boolean',
        'show_gpa_grading_scale' => 'boolean',
    ];
}
