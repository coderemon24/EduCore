<?php

namespace App\Services;

use App\Enums\SchoolStatus;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CurrentSchoolContext
{
    /**
     * Cached resolved school instance.
     */
    protected static ?School $resolvedSchool = null;

    /**
     * Resolve the active school tenant from the current HTTP request or fallback.
     */
    public static function resolve(?Request $request = null): School
    {
        if (static::$resolvedSchool !== null) {
            return static::$resolvedSchool;
        }

        $request ??= request();
        $host = $request?->getHost();

        // 1. SaaS Domain-Matching Hook:
        // When multi-tenant SaaS domains are configured, check custom domain / subdomain
        if ($host && ! in_array($host, ['localhost', '127.0.0.1', 'laravel-school.test'], true)) {
            if (Schema::hasTable('schools')) {
                $domainMatchedSchool = School::where('website', 'like', "%{$host}%")
                    ->orWhere('slug', explode('.', $host)[0])
                    ->where('status', SchoolStatus::Active)
                    ->first();

                if ($domainMatchedSchool) {
                    return static::$resolvedSchool = $domainMatchedSchool;
                }
            }
        }

        // 2. Primary / Default Active School from Database
        if (Schema::hasTable('schools')) {
            $defaultSchool = School::where('status', SchoolStatus::Active)->first()
                ?? School::first();

            if ($defaultSchool) {
                return static::$resolvedSchool = $defaultSchool;
            }
        }

        // 3. Graceful In-Memory Fallback Instance (Ensures Views & Partials Never Crash)
        $fallback = new School([
            'name' => 'সোনাতলা উচ্চ বিদ্যালয় (Sonatala High School)',
            'slug' => 'sonatala-high-school',
            'code' => '8107',
            'status' => SchoolStatus::Active,
            'email' => 'info@sonatalahighschool.edu.bd',
            'phone' => '+880 1712-345678',
            'address' => 'বেড়া-সোনাতলা, সাঁথিয়া',
            'city' => 'পাবনা',
            'state' => 'রাজশাহী',
            'country' => 'Bangladesh',
            'postal_code' => '৬৬৭০',
            'established_year' => '1968',
            'website' => 'https://sonatalahighschool.edu.bd',
        ]);
        $fallback->setAttribute('eiin', '125693');

        return static::$resolvedSchool = $fallback;
    }

    /**
     * Manually set or override the active school in context (e.g., during tests).
     */
    public static function set(?School $school): void
    {
        static::$resolvedSchool = $school;
    }

    /**
     * Get the currently resolved school.
     */
    public static function get(): School
    {
        return static::$resolvedSchool ?? static::resolve();
    }
}
