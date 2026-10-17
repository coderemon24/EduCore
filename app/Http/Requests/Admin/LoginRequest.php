<?php

namespace App\Http\Requests\Admin;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required_without:email', 'nullable', 'string'],
            'email' => ['required_without:login', 'nullable', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get the raw login identifier (username or email).
     */
    public function loginIdentifier(): string
    {
        return trim((string) ($this->input('login') ?? $this->input('email')));
    }

    /**
     * Determine whether the identifier is an email address or username.
     */
    public function loginField(): string
    {
        return filter_var($this->loginIdentifier(), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    /**
     * Attempt to authenticate the request's credentials with rate limiting against the admin guard.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $errorField = $this->has('login') ? 'login' : 'email';
        $identifier = $this->loginIdentifier();
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');

        // 1. Attempt platform Admin authentication (admins table via admin guard)
        $adminCredentials = [
            $this->loginField() => $identifier,
            'password' => $password,
        ];

        if (Auth::guard('admin')->attempt($adminCredentials, $remember)) {
            session()->forget('current_school_id');
            RateLimiter::clear($this->throttleKey());

            return;
        }

        // 2. Attempt school User authentication (users table via web guard)
        $userCredentials = [
            'email' => $identifier,
            'password' => $password,
        ];

        if (Auth::guard('web')->attempt($userCredentials, $remember)) {
            $user = Auth::guard('web')->user();

            if (! $user || ! $user->isActive()) {
                Auth::guard('web')->logout();
                RateLimiter::hit($this->throttleKey(), 60);

                throw ValidationException::withMessages([
                    $errorField => 'Your account is currently inactive. Please contact your school administrator.',
                ]);
            }

            if ($user->school_id) {
                session(['current_school_id' => $user->school_id]);
                app()->instance('current_school_id', $user->school_id);
            }

            RateLimiter::clear($this->throttleKey());

            return;
        }

        RateLimiter::hit($this->throttleKey(), 60);

        throw ValidationException::withMessages([
            $errorField => trans('auth.failed'),
        ]);
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
        $errorField = $this->has('login') ? 'login' : 'email';

        throw ValidationException::withMessages([
            $errorField => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->loginIdentifier()).'|'.$this->ip());
    }
}
