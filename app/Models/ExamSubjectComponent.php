<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSubjectComponent extends Model
{
    use HasFactory;

    protected $table = 'exam_subject_components';

    protected $fillable = [
        'exam_subject_id',
        'name',
        'max_marks',
        'pass_marks',
        'order_index',
    ];

    protected $casts = [
        'max_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
        'order_index' => 'integer',
    ];

    public function examSubject(): BelongsTo
    {
        return $this->belongsTo(ExamSubject::class);
    }

    public function markComponents(): HasMany
    {
        return $this->hasMany(ExamMarkComponent::class);
    }
}
