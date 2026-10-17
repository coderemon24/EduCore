<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'fee_payments';

    protected $fillable = [
        'school_id',
        'receipt_number',
        'fee_invoice_id',
        'student_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'bank_name',
        'cheque_number',
        'idempotency_token',
        'status',
        'received_by',
        'reversed_by',
        'reversed_at',
        'reversal_reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'reversed_at' => 'datetime',
    ];

    /**
     * The invoice this payment settled.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    /**
     * The student on whose behalf payment was made.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The cashier / user who received payment.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * The authorized officer who reversed payment if applicable.
     */
    public function reverser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }

    /**
     * Check if payment is active and completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment was reversed.
     */
    public function isReversed(): bool
    {
        return $this->status === 'reversed';
    }
}
