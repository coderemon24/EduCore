<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'email',
        'password',
        'status',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the school institution that owns this user account.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the staff profile linked to this user account, if any.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get the student profile linked to this user account, if any.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the guardian profile linked to this user account, if any.
     */
    public function guardian(): HasOne
    {
        return $this->hasOne(Guardian::class);
    }

    /**
     * Determine primary institutional portal role for navigation and dashboards.
     */
    public function portalRole(): string
    {
        $roles = $this->relationLoaded('roles') ? $this->roles : $this->roles()->get();
        $slugs = $roles->pluck('slug')->toArray();

        if (in_array('student', $slugs, true)) {
            return 'student';
        }
        if (in_array('guardian', $slugs, true)) {
            return 'guardian';
        }
        if (in_array('teacher', $slugs, true)) {
            return 'teacher';
        }
        if (in_array('school_admin', $slugs, true)) {
            return 'school_admin';
        }
        if (in_array('staff_member', $slugs, true) || in_array('accountant', $slugs, true)) {
            return 'staff_member';
        }

        // Relation-based fallback if roles table isn't populated
        if ($this->relationLoaded('student') ? $this->student !== null : $this->student()->exists()) {
            return 'student';
        }
        if ($this->relationLoaded('guardian') ? $this->guardian !== null : $this->guardian()->exists()) {
            return 'guardian';
        }
        if ($this->relationLoaded('staff') ? $this->staff !== null : $this->staff()->exists()) {
            $staff = $this->relationLoaded('staff') ? $this->staff : $this->staff()->first();
            return $staff?->is_teaching ? 'teacher' : 'staff_member';
        }

        return $slugs[0] ?? 'student';
    }

    /**
     * The institutional roles assigned to this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Determine if the user has a specific role by slug.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    /**
     * Determine if the user has a specific permission by slug.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    /**
     * Check if the user account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get user avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }

        return asset('admin-assets/images/default-avatar.png');
    }
}
