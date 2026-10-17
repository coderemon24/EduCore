<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class StaffAttendance extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staff_attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'staff_id',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
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
     * Scope query to a specific date.
     */
    public function scopeForDate(Builder $query, Carbon|string $date): Builder
    {
        return $query->where('attendance_date', $date instanceof Carbon ? $date->toDateString() : $date);
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
     * Scope query to on_leave status.
     */
    public function scopeOnLeave(Builder $query): Builder
    {
        return $query->where('status', 'on_leave');
    }

    /**
     * Get the staff member associated with the attendance record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
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
