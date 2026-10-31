@extends('layouts.app')

@section('title', 'প্রাতিষ্ঠানিক পোর্টাল লগইন | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষার্থী, অভিভাবক, শিক্ষক ও কর্মচারীগণের জন্য সমন্বিত ডিজিটাল এক্সেস পোর্টাল।')

@section('content')

{{-- Breadcrumb Navigation --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <i class="fa-solid fa-house text-slate-400 text-xs"></i>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">পোর্টাল লগইন</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">সোনাতলা উচ্চ বিদ্যালয় • সাঁথিয়া, পাবনা</span>
    </div>
</div>

<div class="py-10 sm:py-16 bg-[#f8fafc] min-h-[750px] flex items-center justify-center relative overflow-hidden"
     x-data="{
         role: 'student',
         identifier: 'alexander.wright@student.educore.edu',
         password: 'password',
         showPassword: false,
         setDemo(selectedRole, demoIdent, demoPass) {
             this.role = selectedRole;
             this.identifier = demoIdent;
             this.password = demoPass;
         }
     }">

    {{-- Subtle Institutional Background Ambient Geometry --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-[#005030]/5 blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full mx-auto px-4 sm:px-6 relative z-10">

        {{-- School Emblem & Heading Block --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 sm:w-18 sm:h-18 mx-auto rounded-full bg-white border border-slate-200 p-1 shadow-xs flex items-center justify-center mb-3">
                <img src="{{ asset('assets/images/logo.png') }}"
                     alt="সোনাতলা উচ্চ বিদ্যালয়"
                     class="max-w-full max-h-full object-contain"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SHS&background=005030&color=fff&size=128';">
            </div>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] text-xs font-bold mb-2">
                <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                <span>প্রাতিষ্ঠানিক ডিজিটাল গেটওয়ে</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                পোর্টাল <span class="text-[#005030] font-bold">লগইন</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">
                শিক্ষার্থী, অভিভাবক, শিক্ষক ও কর্মচারীগণের জন্য একক সাইন-ইন পোর্টাল
            </p>
        </div>

        {{-- Main Login Card --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">

            {{-- Role Preview & One-Click Demo Helper Pills --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    দ্রুত ডেমো নির্বাচন (এক-ক্লিকে টেস্ট করুন):
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button"
                            @click="setDemo('student', 'alexander.wright@student.educore.edu', 'password')"
                            :class="role === 'student' ? 'bg-[#005030] text-white border-[#005030] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2 px-2.5 rounded-xl border text-xs font-bold text-center transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fa-solid fa-graduation-cap text-xs"></i>
                        <span>শিক্ষার্থী</span>
                    </button>

                    <button type="button"
                            @click="setDemo('guardian', 'robert.wright@guardian.test', 'password')"
                            :class="role === 'guardian' ? 'bg-[#005030] text-white border-[#005030] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2 px-2.5 rounded-xl border text-xs font-bold text-center transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fa-solid fa-users text-xs"></i>
                        <span>অভিভাবক</span>
                    </button>

                    <button type="button"
                            @click="setDemo('teacher', 'eleanor.pemberton@educore.test', 'password')"
                            :class="role === 'teacher' ? 'bg-[#005030] text-white border-[#005030] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2 px-2.5 rounded-xl border text-xs font-bold text-center transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fa-solid fa-chalkboard-user text-xs"></i>
                        <span>শিক্ষক</span>
                    </button>

                    <button type="button"
                            @click="setDemo('staff', 'rachel.kowalski@educore.test', 'password')"
                            :class="role === 'staff' ? 'bg-[#005030] text-white border-[#005030] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2 px-2.5 rounded-xl border text-xs font-bold text-center transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fa-solid fa-id-card-clip text-xs"></i>
                        <span>কর্মচারী</span>
                    </button>
                </div>

                {{-- Demo Hint Helper Text --}}
                <div class="mt-2 text-[11px] text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100 flex items-center justify-between">
                    <span x-show="role === 'student'">💡 বিকল্প আইডি: <strong>ADM-2026-0001</strong></span>
                    <span x-show="role === 'guardian'">💡 বিকল্প আইডি: <strong>+1 (555) 987-6543</strong></span>
                    <span x-show="role === 'teacher'">💡 বিকল্প আইডি: <strong>TCH-1001</strong></span>
                    <span x-show="role === 'staff'">💡 বিকল্প আইডি: <strong>STF-2001</strong></span>
                    <span class="text-slate-400">পাসওয়ার্ড: <strong class="text-slate-700">password</strong></span>
                </div>
            </div>

            {{-- Validation Error Alerts --}}
            @if ($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                        <span>লগইন ব্যর্থ হয়েছে। অনুগ্রহ করে তথ্য যাচাই করুন:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] text-rose-600 pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ Route::has('frontend.portal.login.post') ? route('frontend.portal.login.post') : url('/portal/login') }}" class="space-y-4">
                @csrf

                {{-- Multi-Identifier Input --}}
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-800 mb-1.5">
                        ইউজার আইডি / ইমেইল / ভর্তি নং / মোবাইল
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <input type="text"
                               name="login"
                               id="login"
                               x-model="identifier"
                               value="{{ old('login') }}"
                               required
                               autocomplete="username"
                               placeholder="যেমন: ADM-2026-0001 বা user@domain.com"
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005030] focus:border-[#005030] transition-colors">
                    </div>
                    @error('login')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input with Show/Hide Toggle --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-800">
                            পাসওয়ার্ড
                        </label>
                        <a href="{{ route('frontend.contact') }}#inquiry" class="text-[11px] font-semibold text-[#005030] hover:underline">
                            পাসওয়ার্ড ভুলে গেছেন?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'"
                               name="password"
                               id="password"
                               x-model="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005030] focus:border-[#005030] transition-colors">
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                aria-label="Toggle Password Visibility">
                            <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox"
                               name="remember"
                               class="w-4 h-4 rounded text-[#005030] focus:ring-[#005030] border-slate-300">
                        <span class="text-xs text-slate-600 font-medium">আমাকে মনে রাখুন</span>
                    </label>
                    <span class="text-[11px] text-slate-400">নিরাপদ এসএসএল এনক্রিপশন</span>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#005030] hover:bg-[#003822] text-white font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group active:scale-[0.99]">
                        <span>লগইন করুন</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

            </form>

            {{-- Hairline Divider --}}
            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">প্রশাসনিক সহায়তা</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            {{-- Super Admin Security Callout Box (Crucial Specification) --}}
            <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200 text-slate-700 text-xs space-y-1">
                <div class="flex items-start gap-2">
                    <span class="text-base leading-none">🛡️</span>
                    <div>
                        <span class="font-bold text-slate-900">সুপার অ্যাডমিন এক্সেস:</span>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            প্রধান প্রশাসনিক নিয়ন্ত্রণ প্যানেলে প্রবেশের জন্য অনুগ্রহ করে 
                            <a href="{{ url('/admin/login') }}" class="underline font-bold text-amber-800 hover:text-amber-950">
                                অ্যাডমিন লগইন পোর্টাল
                            </a> 
                            ব্যবহার করুন।
                        </p>
                    </div>
                </div>
            </div>

            {{-- Return to Website Link --}}
            <div class="text-center pt-1">
                <a href="{{ route('frontend.home') }}" class="text-xs font-semibold text-slate-500 hover:text-[#005030] transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>মূল বিদ্যালয় ওয়েবসাইটে ফিরে যান</span>
                </a>
            </div>

        </div>

    </div>
</div>

@endsection
