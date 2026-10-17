<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamMarkHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'exam_mark_histories';

    protected $fillable = [
        'exam_mark_id',
        'previous_marks',
        'new_marks',
        'previous_status',
        'new_status',
        'reason',
        'changed_by',
        'created_at',
    ];

    protected $casts = [
        'previous_marks' => 'decimal:2',
        'new_marks' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function examMark(): BelongsTo
    {
        return $this->belongsTo(ExamMark::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
