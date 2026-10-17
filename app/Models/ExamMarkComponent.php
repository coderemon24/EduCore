<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamMarkComponent extends Model
{
    use HasFactory;

    protected $table = 'exam_mark_components';

    protected $fillable = [
        'exam_mark_id',
        'exam_subject_component_id',
        'marks_obtained',
        'is_passed',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_passed' => 'boolean',
    ];

    public function examMark(): BelongsTo
    {
        return $this->belongsTo(ExamMark::class);
    }

    public function examSubjectComponent(): BelongsTo
    {
        return $this->belongsTo(ExamSubjectComponent::class);
    }
}
