<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicHoliday extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_holidays';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'academic_year_id',
        'title',
        'start_date',
        'end_date',
        'type',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Scope query to holidays that cover a specific date.
     */
    public function scopeCoveringDate(Builder $query, Carbon|string $date): Builder
    {
        $d = $date instanceof Carbon ? $date->toDateString() : $date;

        return $query->where('start_date', '<=', $d)
            ->where('end_date', '>=', $d);
    }

    /**
     * Scope query to holidays within a given date window.
     */
    public function scopeBetweenDates(Builder $query, Carbon|string $start, Carbon|string $end): Builder
    {
        $s = $start instanceof Carbon ? $start->toDateString() : $start;
        $e = $end instanceof Carbon ? $end->toDateString() : $end;

        return $query->where(function ($q) use ($s, $e) {
            $q->whereBetween('start_date', [$s, $e])
                ->orWhereBetween('end_date', [$s, $e])
                ->orWhere(function ($sub) use ($s, $e) {
                    $sub->where('start_date', '<=', $s)
                        ->where('end_date', '>=', $e);
                });
        });
    }

    /**
     * Get the academic year session for this holiday.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
