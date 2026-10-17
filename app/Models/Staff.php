<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'user_id',
        'staff_code',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'is_teaching',
        'department',
        'designation',
        'qualification',
        'experience_years',
        'joining_date',
        'basic_salary',
        'contract_type',
        'status',
        'photo',
        'current_address',
        'permanent_address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_teaching' => 'boolean',
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'basic_salary' => 'decimal:2',
            'experience_years' => 'integer',
        ];
    }

    /**
     * Get the full name accessor.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Scope a query to only include teaching faculty.
     */
    public function scopeTeaching(Builder $query): Builder
    {
        return $query->where('is_teaching', true);
    }

    /**
     * Scope a query to only include non-teaching staff.
     */
    public function scopeNonTeaching(Builder $query): Builder
    {
        return $query->where('is_teaching', false);
    }

    /**
     * Scope a query to only include active staff.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the user account associated with this staff member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sections where this staff member is appointed as Class Teacher.
     */
    public function classTeacherSections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_teacher_id');
    }

    /**
     * Get the curriculum subject assignments where this staff member is assigned instructor.
     */
    public function subjectAssignments(): HasMany
    {
        return $this->hasMany(ClassSubjectAssignment::class, 'teacher_id');
    }

    /**
     * Get the photo URL with reliable default avatar fallback.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(public_path($this->photo))) {
            return asset($this->photo);
        }

        return asset('admin-assets/images/default-avatar.png');
    }
}
