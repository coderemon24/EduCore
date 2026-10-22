<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Sign In | EduCore School Management System</title>

    <!-- Google Fonts: Noto Sans Bengali & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Styles -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Noto Sans Bengali', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-100 selection:bg-indigo-600 selection:text-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- LEFT COLUMN: School Management Showcase (Visible on lg screens) -->
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative flex-col justify-between bg-slate-900 text-white p-10 xl:p-14 border-r border-slate-800">

            <!-- School Brand Header -->
            <div class="flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-lg bg-indigo-600 flex items-center justify-center p-2 shadow-sm shrink-0">
                    <img src="{{ asset('admin-assets/images/laravel-school-transparent-white.png') }}"
                         alt="EduCore Crest"
                         class="h-full w-full object-contain brightness-0 invert">
                </div>
                <div>
                    <span class="block text-lg font-bold tracking-tight text-white leading-tight">EduCore Academy</span>
                    <span class="block text-xs font-medium text-slate-400">School Management System</span>
                </div>
            </div>

            <!-- Campus Image & Institutional Focus -->
            <div class="my-auto py-8 max-w-lg">
                <div class=" mb-6">
                    <img src="{{ asset('admin-assets/images/laravel-school-transparent-white.png') }}"
                         alt="School Campus"
                         class="w-100 h-44 xl:h-38 object-cover">
                </div>

                <h1 class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight leading-snug mb-2">
                    School Administration & Academic Governance.
                </h1>

                <p class="text-xs xl:text-sm text-slate-400 leading-relaxed">
                    Dedicated gateway for principals, academic deans, and administrative staff to manage admissions, faculty records, and institutional operations.
                </p>
            </div>

            <!-- Simple Institutional Footnote -->
            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <span>EduCore SMS Portal</span>
                <span class="text-slate-500">&copy; {{ date('Y') }} All Rights Reserved</span>
            </div>
        </div>

        <!-- RIGHT COLUMN: Minimalist Login Form -->
        <div class="flex-1 flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-10 xl:px-16 bg-slate-50">

            <div class="w-full max-w-md">

                <!-- Mobile School Branding -->
                <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-lg bg-indigo-600 flex items-center justify-center p-2 shadow-xs shrink-0">
                        <img src="{{ asset('admin-assets/images/laravel-school-transparent.png') }}"
                             alt="EduCore Crest"
                             class="h-full w-full object-contain brightness-0 invert">
                    </div>
                    <div class="text-left">
                        <span class="block text-base font-bold text-slate-900 leading-tight">EduCore Academy</span>
                        <span class="block text-xs text-slate-500 font-medium">School Management Portal</span>
                    </div>
                </div>

                <!-- Main Login Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sm:p-8">

                    <!-- Card Header -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Admin Sign In</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Enter your credentials to access the administration console.
                        </p>
                    </div>

                    <!-- Session Status Notice -->
                    @if (session('status'))
                        <div class="mb-4 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs text-emerald-800">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                            <div class="flex items-center gap-1.5 font-semibold mb-1">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Sign In Failed</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Authentication Form -->
                    <form method="POST" action="{{ route('admin.login.store') }}" id="admin-login-form" class="space-y-4">
                        @csrf

                        <!-- Username or Email Field -->
                        <div>
                            <label for="login" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Username or Email
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text"
                                       id="login"
                                       name="login"
                                       value="{{ old('login', old('email')) }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       placeholder="Username or email address"
                                       class="w-full rounded-lg border {{ $errors->has('login') || $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 bg-white' }} py-2.5 pl-9 pr-3 text-xs font-normal text-slate-900 placeholder-slate-400 transition hover:border-slate-400 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600/15">
                            </div>
                        </div>

                        <!-- Password Field with Accessible Toggle -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-semibold text-slate-700">
                                    Password
                                </label>
                            </div>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       placeholder="••••••••••••"
                                       class="w-full rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 bg-white' }} py-2.5 pl-9 pr-10 text-xs font-normal text-slate-900 placeholder-slate-400 transition hover:border-slate-400 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600/15">

                                <button type="button"
                                        id="toggle-password-btn"
                                        aria-label="Toggle password visibility"
                                        aria-pressed="false"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <svg id="eye-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-slash-icon" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox"
                                       name="remember"
                                       id="remember"
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 cursor-pointer">
                                <span class="text-xs text-slate-600 font-medium">Remember me</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                    id="btn-submit-login"
                                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white py-2.5 px-4 text-xs font-semibold shadow-xs transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 cursor-pointer">
                                <svg id="btn-submit-spinner" class="animate-spin h-3.5 w-3.5 text-white hidden" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span id="btn-submit-text">Sign In</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Development Quick Fill (Local Environment Only) -->
                    @if (app()->environment('local'))
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Dev account helper:</span>
                            <button type="button"
                                    id="btn-quick-fill"
                                    class="inline-flex items-center gap-1 rounded border border-slate-200 bg-slate-50 hover:bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-700 transition cursor-pointer">
                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Quick Fill (admin)</span>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Footer Notice -->
                <div class="mt-6 text-center text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} EduCore Academy. Authorized Personnel Only.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Authentication Console -->
    <script src="{{ asset('admin-assets/js/admin-login.js') }}" defer></script>
</body>
</html>
