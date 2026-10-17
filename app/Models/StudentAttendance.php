<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class StudentAttendance extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'academic_year_id',
        'academic_class_id',
        'section_id',
        'student_id',
        'attendance_date',
        'status',
        'remarks',
        'recorded_by_id',
        'updated_by_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
        ];
    }

    /**
     * Scope query to present status.
     */
    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope query to absent status.
     */
    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('status', 'absent');
    }

    /**
     * Scope query to late status.
     */
    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', 'late');
    }

    /**
     * Scope query to excused status.
     */
    public function scopeExcused(Builder $query): Builder
    {
        return $query->where('status', 'excused');
    }

    /**
     * Scope query by a specific attendance date.
     */
    public function scopeForDate(Builder $query, Carbon|string $date): Builder
    {
        return $query->where('attendance_date', $date instanceof Carbon ? $date->toDateString() : $date);
    }

    /**
     * Scope query by section ID.
     */
    public function scopeForSection(Builder $query, int $sectionId): Builder
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Get the student associated with the attendance record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the academic class.
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    /**
     * Get the section.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the academic year session.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the user who recorded this attendance.
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }

    /**
     * Get the user who last updated this attendance.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    /**
     * Get all audit logs for this attendance record.
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AttendanceAuditLog::class, 'attendable');
    }
}
