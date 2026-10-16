<?php

namespace App\Models;

use App\Enums\SchoolStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schools';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'status',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'logo',
        'favicon',
        'website',
        'established_year',
        'timezone',
        'currency',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'logo_url',
        'favicon_url',
    ];

    /**
     * Get the accessible public URL for the school logo.
     */
    public function getLogoUrlAttribute(): string
    {
        if (! empty($this->logo)) {
            $relativePath = ltrim($this->logo, '/\\');
            if (file_exists(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return asset('admin-assets/images/laravel-school-transparent.png');
    }

    /**
     * Get the accessible public URL for the school favicon.
     */
    public function getFaviconUrlAttribute(): string
    {
        if (! empty($this->favicon)) {
            $relativePath = ltrim($this->favicon, '/\\');
            if (file_exists(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return $this->logo_url;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SchoolStatus::class,
            'established_year' => 'integer',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (School $school) {
            if (empty($school->slug) && ! empty($school->name)) {
                $baseSlug = Str::slug($school->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                $school->slug = $slug;
            }
        });
    }

    /**
     * Get users belonging to this school institution.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
