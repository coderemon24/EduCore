<?php

namespace App\Http\Requests\Portal;

use App\Models\Guardian;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PortalLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Attempt to authenticate the institutional portal user.
     *
     * Multi-identifier resolution:
     * 1. Email (User)
     * 2. Admission Number (Student -> User)
     * 3. Staff Code (Staff -> User)
     * 4. Phone (User or Guardian -> User)
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = trim((string) $this->input('login'));
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');

        $user = $this->resolveUser($identifier);

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey(), 60);

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        if (! $user->isActive()) {
            RateLimiter::hit($this->throttleKey(), 60);

            throw ValidationException::withMessages([
                'login' => 'Your institutional portal account is currently inactive. Please contact the school administration.',
            ]);
        }

        Auth::guard('web')->login($user, $remember);

        if ($user->school_id) {
            session(['current_school_id' => $user->school_id]);
            app()->instance('current_school_id', $user->school_id);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Resolve User instance across multiple institutional identifiers.
     */
    protected function resolveUser(string $identifier): ?User
    {
        // 1. Direct email match on User
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
            if ($user) {
                return $user;
            }
        }

        $user = User::where('email', $identifier)->first();
        if ($user) {
            return $user;
        }

        // 2. Student Admission Number lookup
        $student = Student::where('admission_number', $identifier)->first();
        if ($student && $student->user_id) {
            return $student->user;
        }

        // 3. Staff Code lookup
        $staff = Staff::where('staff_code', $identifier)->first();
        if ($staff && $staff->user_id) {
            return $staff->user;
        }

        // 4. Phone match (User phone or Guardian phone)
        $userByPhone = User::where('phone', $identifier)->first();
        if ($userByPhone) {
            return $userByPhone;
        }

        $guardian = Guardian::where('phone', $identifier)->first();
        if ($guardian && $guardian->user_id) {
            return $guardian->user;
        }

        return null;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower(trim((string) $this->input('login'))).'|'.$this->ip());
    }
}

