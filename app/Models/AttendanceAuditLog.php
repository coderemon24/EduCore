<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AttendanceAuditLog extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance_audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'attendable_type',
        'attendable_id',
        'action',
        'old_status',
        'new_status',
        'reason',
        'user_id',
        'ip_address',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the owning attendable model (StudentAttendance or StaffAttendance).
     */
    public function attendable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who made the correction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
