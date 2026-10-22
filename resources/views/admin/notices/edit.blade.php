@extends('layouts.admin')

@section('title', 'Edit Notice | Administration')

@section('admin-content')
<div class="space-y-4">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.notices.index') }}" class="text-slate-600 hover:text-violet-600 transition">Notices</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Edit Notice</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Edit Notice</h1>
            <p class="text-xs text-slate-500 truncate max-w-xl">
                {{ $notice->title }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.notices.show', $notice) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <span>View</span>
            </a>
            <a href="{{ route('admin.notices.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <span>Back</span>
            </a>
        </div>
    </div>

    {{-- Validation Errors Alert --}}
    @if ($errors->any())
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-3.5 text-xs text-rose-700 space-y-1">
            <p class="font-bold text-rose-800">Please check the required fields:</p>
            <ul class="list-disc list-inside text-[11px] space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.notices.update', $notice) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        @csrf
        @method('PUT')

        {{-- Left: Content (2 cols) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-3.5">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">
                        Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $notice->title) }}"
                           required
                           class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition">
                    @error('title')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="content" class="block text-xs font-semibold text-slate-700">
                            Content <span class="text-rose-500">*</span>
                        </label>
                        <span id="char-count" class="text-[11px] text-slate-400">0 characters</span>
                    </div>
                    <textarea name="content"
                              id="content"
                              rows="12"
                              class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition leading-relaxed">{{ old('content', $notice->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Attachment --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-3">
                <label class="block text-xs font-semibold text-slate-700">
                    Attachment
                </label>

                @if ($notice->attachment_path)
                    <div class="flex items-center justify-between p-2.5 rounded border border-slate-200 bg-slate-50">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="h-4 w-4 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-xs text-slate-800 font-medium truncate">{{ $notice->attachment_name ?? 'File' }}</span>
                            <a href="{{ asset('storage/' . $notice->attachment_path) }}" target="_blank" class="text-[11px] text-violet-600 hover:underline">
                                View
                            </a>
                        </div>
                        <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 cursor-pointer">
                            <input type="checkbox" name="remove_attachment" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>Remove</span>
                        </label>
                    </div>
                @endif

                <div>
                    <input type="file"
                           name="attachment"
                           id="attachment-input"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    <p id="attachment-name" class="text-[11px] text-violet-700 font-medium mt-1.5 hidden"></p>
                </div>
                @error('attachment')
                    <p class="text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Right: Settings (1 col) --}}
        <div class="space-y-4">

            {{-- Target Audience & Category --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-3">
                <h3 class="text-xs font-bold text-slate-800 pb-2 border-b border-slate-100">Audience &amp; Type</h3>

                {{-- Category --}}
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 mb-1">
                        Category <span class="text-rose-500">*</span>
                    </label>
                    <select name="type"
                            id="type"
                            required
                            class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        @foreach ($types as $val => $lbl)
                            <option value="{{ $val }}" {{ old('type', $notice->type) === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Target Audience --}}
                <div>
                    <label for="target_audience" class="block text-xs font-semibold text-slate-700 mb-1">
                        Audience <span class="text-rose-500">*</span>
                    </label>
                    <select name="target_audience"
                            id="target_audience"
                            required
                            onchange="handleAudienceChange(this.value)"
                            class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        @foreach ($audiences as $val => $lbl)
                            <option value="{{ $val }}" {{ old('target_audience', $notice->target_audience) === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Class Specific --}}
                <div id="class-target-container" class="{{ old('target_audience', $notice->target_audience) === 'class_specific' ? 'block' : 'hidden' }} space-y-1">
                    <label for="academic_class_id" class="block text-xs font-semibold text-slate-700">
                        Class <span class="text-rose-500">*</span>
                    </label>
                    <select name="academic_class_id"
                            id="academic_class_id"
                            class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ old('academic_class_id', $notice->academic_class_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Publishing Schedule --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-3">
                <h3 class="text-xs font-bold text-slate-800 pb-2 border-b border-slate-100">Schedule &amp; Visibility</h3>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            required
                            class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        @foreach ($statuses as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $notice->status) === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Publish Date --}}
                <div>
                    <label for="published_at" class="block text-xs font-semibold text-slate-700 mb-1">
                        Publish Date
                    </label>
                    <input type="date"
                           name="published_at"
                           id="published_at"
                           value="{{ old('published_at', $notice->published_at ? $notice->published_at->format('Y-m-d') : '') }}"
                           class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                </div>

                {{-- Expiry Date --}}
                <div>
                    <label for="expires_at" class="block text-xs font-semibold text-slate-700 mb-1">
                        Expiry Date <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <input type="date"
                           name="expires_at"
                           id="expires_at"
                           value="{{ old('expires_at', $notice->expires_at ? $notice->expires_at->format('Y-m-d') : '') }}"
                           class="w-full rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                </div>

                {{-- Pin Checkbox --}}
                <div class="pt-2 border-t border-slate-100">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="is_pinned"
                               value="1"
                               {{ old('is_pinned', $notice->is_pinned) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-medium text-slate-700">Pin to top of list</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-2">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 rounded-md bg-violet-600 hover:bg-violet-700 px-4 py-2 text-xs font-semibold text-white transition cursor-pointer">
                    Update Notice
                </button>
                <a href="{{ route('admin.notices.index') }}"
                   class="w-full inline-flex items-center justify-center rounded-md border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-medium text-slate-700 transition cursor-pointer">
                    Cancel
                </a>
            </div>

        </div>
    </form>

</div>

@push('scripts')
<script src="{{ asset('admin-assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    function handleAudienceChange(val) {
        const container = document.getElementById('class-target-container');
        const classSelect = document.getElementById('academic_class_id');
        if (val === 'class_specific') {
            container.classList.remove('hidden');
            classSelect.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            classSelect.removeAttribute('required');
        }
    }

    function initNoticeTinyMCE() {
        if (typeof tinymce === 'undefined') return;

        tinymce.init({
            selector: '#content',
            base_url: '{{ asset('admin-assets/vendor/tinymce') }}',
            suffix: '.min',
            height: 380,
            menubar: 'edit insert view format table tools',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link table code fullscreen',
            content_style: "body { font-family: 'Noto Sans Bengali', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; font-size: 13px; color: #1e293b; line-height: 1.6; padding: 12px; }",
            branding: false,
            promotion: false,
            statusbar: true,
            license_key: 'gpl',
            setup: function (editor) {
                const updateCount = () => {
                    const charCount = document.getElementById('char-count');
                    if (charCount) {
                        const text = editor.getContent({ format: 'text' }).trim();
                        charCount.textContent = `${text.length} characters`;
                    }
                };
                editor.on('init', updateCount);
                editor.on('input change keyup NodeChange SetContent', function () {
                    editor.save();
                    updateCount();
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initNoticeTinyMCE();

        const form = document.querySelector('form');
        const fileInput = document.getElementById('attachment-input');
        const fileName = document.getElementById('attachment-name');

        if (form) {
            form.addEventListener('submit', function (e) {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
                const editor = typeof tinymce !== 'undefined' ? tinymce.get('content') : null;
                const textContent = editor ? editor.getContent({ format: 'text' }).trim() : document.getElementById('content').value.trim();
                if (!textContent) {
                    e.preventDefault();
                    if (window.toastMagic) {
                        window.toastMagic.error({
                            heading: 'Validation Error',
                            description: 'Notice content is required.'
                        });
                    } else {
                        alert('Notice content is required.');
                    }
                    if (editor) {
                        editor.focus();
                    }
                    return false;
                }
            });
        }

        if (fileInput && fileName) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    fileName.textContent = `New file: ${this.files[0].name} (${(this.files[0].size / 1024).toFixed(0)} KB)`;
                    fileName.classList.remove('hidden');
                } else {
                    fileName.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
@endsection
