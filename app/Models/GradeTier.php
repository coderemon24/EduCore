<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeTier extends Model
{
    use HasFactory;

    protected $table = 'grade_tiers';

    protected $fillable = [
        'grading_scale_id',
        'grade',
        'point',
        'min_percentage',
        'max_percentage',
        'is_pass',
        'remarks',
        'order_index',
    ];

    protected $casts = [
        'point' => 'decimal:2',
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
        'is_pass' => 'boolean',
        'order_index' => 'integer',
    ];

    public function gradingScale(): BelongsTo
    {
        return $this->belongsTo(GradingScale::class);
    }
}
