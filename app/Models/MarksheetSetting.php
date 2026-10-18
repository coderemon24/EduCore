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
        'padding_top',
        'padding_right',
        'padding_bottom',
        'padding_left',
    ];

    protected $casts = [
        'show_highest_marks' => 'boolean',
        'show_attendance_report' => 'boolean',
        'show_extra_activities' => 'boolean',
        'show_gpa_grading_scale' => 'boolean',
    ];

    public function getPaddingTopCssAttribute(): string
    {
        return $this->formatPaddingUnit($this->padding_top, '10mm');
    }

    public function getPaddingRightCssAttribute(): string
    {
        return $this->formatPaddingUnit($this->padding_right, '12mm');
    }

    public function getPaddingBottomCssAttribute(): string
    {
        return $this->formatPaddingUnit($this->padding_bottom, '10mm');
    }

    public function getPaddingLeftCssAttribute(): string
    {
        return $this->formatPaddingUnit($this->padding_left, '12mm');
    }

    public function getPaddingCssAttribute(): string
    {
        return "{$this->padding_top_css} {$this->padding_right_css} {$this->padding_bottom_css} {$this->padding_left_css}";
    }

    protected function formatPaddingUnit(?string $value, string $default = '0mm'): string
    {
        $val = trim((string) $value);
        if ($val === '') {
            return $default;
        }

        return is_numeric($val) ? $val.'mm' : $val;
    }
}
