<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeInvoice extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    protected $table = 'fee_invoices';

    protected $fillable = [
        'school_id',
        'invoice_number',
        'student_id',
        'academic_year_id',
        'academic_class_id',
        'section_id',
        'title',
        'issue_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'paid_amount',
        'due_amount',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    /**
     * The student billed.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The academic session of this invoice.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * The class of the student when invoiced.
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    /**
     * The section of the student when invoiced.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * The staff member who created the invoice.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Line items on this invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(FeeInvoiceItem::class);
    }

    /**
     * Offline payment transactions received.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * Waivers/discounts granted on this invoice.
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(FeeDiscount::class);
    }

    /**
     * Active completed payments only.
     */
    public function completedPayments(): HasMany
    {
        return $this->hasMany(FeePayment::class)->where('status', 'completed');
    }

    /**
     * Check if invoice is fully paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is partially paid.
     */
    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partially_paid';
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || ($this->due_amount > 0 && $this->due_date->isPast());
    }

    /**
     * Recalculate invoice totals and update status atomically.
     */
    public function recalculateBalances(): void
    {
        $subtotal = (float) $this->items()->sum('amount');
        $discountAmount = (float) $this->discounts()->sum('amount');
        $paidAmount = (float) $this->completedPayments()->sum('amount');
        $dueAmount = max(0.00, round($subtotal - $discountAmount - $paidAmount, 2));

        $status = 'unpaid';
        if ($dueAmount <= 0.00 && $paidAmount > 0.00) {
            $status = 'paid';
        } elseif ($paidAmount > 0.00 && $dueAmount > 0.00) {
            $status = 'partially_paid';
        } elseif ($dueAmount > 0.00 && Carbon::parse($this->due_date)->isPast()) {
            $status = 'overdue';
        }

        $this->update([
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'paid_amount' => round($paidAmount, 2),
            'due_amount' => $dueAmount,
            'status' => $status,
        ]);
    }
}
