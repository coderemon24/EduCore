<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Notice extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    /**
     * Standard notice types.
     */
    public const TYPE_GENERAL = 'general';

    public const TYPE_ACADEMIC = 'academic';

    public const TYPE_EXAM = 'exam';

    public const TYPE_HOLIDAY = 'holiday';

    public const TYPE_EVENT = 'event';

    public const TYPE_URGENT = 'urgent';

    public const TYPES = [
        self::TYPE_GENERAL => 'General',
        self::TYPE_ACADEMIC => 'Academic',
        self::TYPE_EXAM => 'Examination',
        self::TYPE_HOLIDAY => 'Holiday',
        self::TYPE_EVENT => 'Event / Activity',
        self::TYPE_URGENT => 'Urgent Alert',
    ];

    /**
     * Target audience options.
     */
    public const AUDIENCE_ALL = 'all';

    public const AUDIENCE_TEACHERS = 'teachers';

    public const AUDIENCE_STUDENTS = 'students';

    public const AUDIENCE_GUARDIANS = 'guardians';

    public const AUDIENCE_CLASS_SPECIFIC = 'class_specific';

    public const AUDIENCES = [
        self::AUDIENCE_ALL => 'All School Community',
        self::AUDIENCE_TEACHERS => 'Teachers & Staff',
        self::AUDIENCE_STUDENTS => 'Students Only',
        self::AUDIENCE_GUARDIANS => 'Parents & Guardians',
        self::AUDIENCE_CLASS_SPECIFIC => 'Specific Class',
    ];

    /**
     * Publication status options.
     */
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_ARCHIVED => 'Archived',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notices';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'slug',
        'content',
        'type',
        'target_audience',
        'academic_class_id',
        'is_pinned',
        'status',
        'published_at',
        'expires_at',
        'attachment_path',
        'attachment_name',
        'view_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Notice $notice) {
            if (empty($notice->slug)) {
                $notice->slug = Str::slug($notice->title).'-'.Str::lower(Str::random(6));
            }
            if (empty($notice->published_at) && $notice->status === self::STATUS_PUBLISHED) {
                $notice->published_at = now();
            }
        });
    }

    /**
     * Get the author user who created this notice.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the optional specific academic class targeted by this notice.
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'academic_class_id');
    }

    /**
     * Scope query to published notices.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }

    /**
     * Scope query to pinned notices first.
     */
    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope query by target audience.
     */
    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        return $query->where('target_audience', $audience);
    }

    /**
     * Scope query by search term.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }
}
