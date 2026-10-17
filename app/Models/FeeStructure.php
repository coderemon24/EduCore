<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeStructure extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'fee_structures';

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'academic_class_id',
        'fee_category_id',
        'name',
        'amount',
        'frequency',
        'due_day',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_day' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * The associated Academic Year.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * The targeted Academic Class (nullable = applies to all classes).
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    /**
     * The Fee Category.
     */
    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class);
    }

    /**
     * Invoice items generated from this structure.
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(FeeInvoiceItem::class);
    }
}
