@extends('layouts.admin')

@section('title', 'Assign Subjects | Academic Structure')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.assign-subjects.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Assign Subjects</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Assign</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Assign Subjects to Class</h1>
            <p class="text-xs text-slate-500 mt-0.5">Map curriculum subjects to a specific grade level and configure examination criteria.</p>
        </div>

        <a href="{{ route('admin.academic.assign-subjects.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Assignments
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.assign-subjects.store') }}" class="space-y-5">
            @csrf

            <!-- Target Class Dropdown -->
            <div>
                <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Target Academic Class / Grade <span class="text-rose-500">*</span>
                </label>
                <select name="academic_class_id"
                        id="academic_class_id"
                        required
                        class="select2 w-full rounded-lg border {{ $errors->has('academic_class_id') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800">
                    <option value="">-- Select Target Class --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ (string)old('academic_class_id', $defaultClassId) === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                @error('academic_class_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjects Multi-Select -->
            <div>
                <label for="subject_ids" class="block text-xs font-semibold text-slate-700 mb-1">
                    Select Subjects to Assign <span class="text-rose-500">*</span>
                </label>
                <select name="subject_ids[]"
                        id="subject_ids"
                        multiple="multiple"
                        required
                        data-placeholder="Search and choose one or more subjects..."
                        class="select2 w-full rounded-lg border {{ $errors->has('subject_ids') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800">
                    @foreach ($subjects as $s)
                        <option value="{{ $s->id }}" {{ in_array($s->id, (array)old('subject_ids', [])) ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->code }}) - [{{ ucfirst($s->type) }}]
                        </option>
                    @endforeach
                </select>
                @error('subject_ids')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
                @error('subject_ids.*')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-[11px] text-slate-400">You can assign multiple subjects simultaneously. Existing assignments will be safely updated.</p>
            </div>

            <!-- Academic Year Session (Optional) -->
            <div>
                <label for="academic_year_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Academic Session Override <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <select name="academic_year_id"
                        id="academic_year_id"
                        class="select2 w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800">
                    <option value="">All Sessions (General Standard Curriculum)</option>
                    @foreach ($academicYears as $y)
                        <option value="{{ $y->id }}" {{ (string)old('academic_year_id') === (string)$y->id ? 'selected' : '' }}>
                            {{ $y->name }} {{ $y->is_current ? '(Active Session)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-[11px] text-slate-400">Leave blank if this curriculum applies to all academic sessions without yearly syllabus variation.</p>
            </div>

            <!-- Curriculum Type -->
            <div>
                <label for="type" class="block text-xs font-semibold text-slate-700 mb-1">
                    Curriculum Subject Type <span class="text-rose-500">*</span>
                </label>
                <select name="type"
                        id="type"
                        required
                        class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="compulsory" {{ old('type', 'compulsory') === 'compulsory' ? 'selected' : '' }}>Compulsory / Mandatory</option>
                    <option value="optional" {{ old('type') === 'optional' ? 'selected' : '' }}>Optional (Student can opt in)</option>
                    <option value="elective" {{ old('type') === 'elective' ? 'selected' : '' }}>Elective / Stream Specific</option>
                </select>
                @error('type')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assigned Subject Teacher (Optional) -->
            <div>
                <label for="teacher_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Subject Teacher <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <select name="teacher_id"
                        id="teacher_id"
                        class="select2 w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="">None (Unassigned)</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ (string)old('teacher_id') === (string)$teacher->id ? 'selected' : '' }}>
                            {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->staff_code }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Marks Configuration Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Full Marks -->
                <div>
                    <label for="full_marks" class="block text-xs font-semibold text-slate-700 mb-1">
                        Full Marks <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="full_marks"
                           id="full_marks"
                           value="{{ old('full_marks', 100) }}"
                           min="1"
                           max="500"
                           required
                           class="w-full rounded-lg border {{ $errors->has('full_marks') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('full_marks')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pass Marks -->
                <div>
                    <label for="pass_marks" class="block text-xs font-semibold text-slate-700 mb-1">
                        Pass Marks Threshold <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="pass_marks"
                           id="pass_marks"
                           value="{{ old('pass_marks', 33) }}"
                           min="0"
                           max="500"
                           required
                           class="w-full rounded-lg border {{ $errors->has('pass_marks') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('pass_marks')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.assign-subjects.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Assign Subjects
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
