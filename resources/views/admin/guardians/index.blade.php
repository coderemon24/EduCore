@extends('layouts.admin')

@section('title', 'Parents & Guardians | Administration')

@section('admin-content')
<div class="space-y-4">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Parents &amp; Guardians</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Parents &amp; Guardians</h1>
            <p class="text-xs text-slate-500">
                Manage student parents, phone numbers, and family relationships.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4zm0-2a5 5 0 100-10 5 5 0 000 10z" />
                </svg>
                <span>Students</span>
            </a>
            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Student</span>
            </a>
        </div>
    </div>

    {{-- Filter Toolbar: Status Tabs & Real-time Search --}}
    <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs space-y-3">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            {{-- Status Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium pb-1 sm:pb-0">
                @php
                    $tabs = [
                        'active' => ['label' => 'Active', 'count' => $counts['active'] ?? 0],
                        'archived' => ['label' => 'Trash', 'count' => $counts['archived'] ?? 0],
                        'all' => ['label' => 'All', 'count' => $counts['all'] ?? 0],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    @php
                        $isSelected = ($status === $key);
                        $isTrash = ($key === 'archived');
                    @endphp
                    <a href="{{ route('admin.guardians.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                       class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 transition-colors whitespace-nowrap cursor-pointer {{ $isSelected ? ($isTrash ? 'bg-rose-600 text-white font-semibold' : 'bg-violet-600 text-white font-semibold') : ($isTrash ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100') }}">
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded px-1.5 py-0.2 text-[10px] font-semibold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrash ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Real-time Search Box --}}
            <div class="relative w-full sm:w-72">
                <input type="text"
                       id="guardian-search-input"
                       value="{{ $search }}"
                       autocomplete="off"
                       placeholder="Search name, phone, student..."
                       class="w-full rounded-md border border-slate-200 bg-white pl-8 pr-8 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                {{-- Loading Spinner (visible during real-time fetch) --}}
                <div id="search-spinner" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-3.5 w-3.5 text-violet-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>

                {{-- Clear Button --}}
                <button type="button"
                        id="search-clear-btn"
                        onclick="clearGuardianSearch()"
                        class="{{ empty($search) ? 'hidden' : '' }} absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Bulk Action Bar (Hidden until rows are selected) --}}
        <div id="bulk-action-bar" class="hidden flex items-center justify-between gap-3 p-2.5 rounded-md bg-slate-900 text-white text-xs transition">
            <div class="flex items-center gap-2">
                <span id="bulk-count-badge" class="font-bold bg-white/20 px-2 py-0.5 rounded text-[11px]">0</span>
                <span>selected</span>
            </div>
            <div class="flex items-center gap-2">
                @if($status === 'archived')
                    <button type="button"
                            onclick="executeBulkAction('restore')"
                            class="inline-flex items-center gap-1.5 rounded bg-emerald-600 hover:bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Restore</span>
                    </button>
                    <button type="button"
                            onclick="executeBulkAction('force_delete')"
                            class="inline-flex items-center gap-1.5 rounded bg-rose-600 hover:bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Permanently</span>
                    </button>
                @else
                    <button type="button"
                            onclick="executeBulkAction('delete')"
                            class="inline-flex items-center gap-1.5 rounded bg-rose-600 hover:bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Move to Trash</span>
                    </button>
                    @if($status === 'all')
                        <button type="button"
                                onclick="executeBulkAction('force_delete')"
                                class="inline-flex items-center gap-1.5 rounded bg-rose-800 hover:bg-rose-700 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                            <span>Delete Permanently</span>
                        </button>
                    @endif
                @endif
                <button type="button"
                        onclick="clearAllSelections()"
                        class="rounded px-2 py-1 text-xs text-slate-300 hover:text-white transition cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Table Container (Dynamically updated via real-time search) --}}
    <div id="guardians-table-container" class="rounded-lg border border-slate-200 bg-white shadow-2xs overflow-hidden">
        @include('admin.guardians.partials.table')
    </div>

</div>

{{-- Hidden Form for Bulk Action Submission --}}
<form id="bulk-action-form" method="POST" action="{{ route('admin.guardians.bulk-action') }}" class="hidden">
    @csrf
    <input type="hidden" name="action" id="bulk-action-input">
    <div id="bulk-ids-container"></div>
</form>

{{-- Real-Time Search & Bulk Selection Script --}}
<script>
    let searchDebounceTimer = null;
    let searchAbortController = null;
    const currentStatus = @json($status);

    function initGuardianEventListeners() {
        const selectAll = document.getElementById('select-all-guardians');
        const rowCheckboxes = document.querySelectorAll('.guardian-row-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const isChecked = this.checked;
                rowCheckboxes.forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkActionBar();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateBulkActionBar();
            });
        });

        // Intercept pagination clicks to load smoothly via AJAX
        const paginationLinks = document.querySelectorAll('.guardian-pagination-links a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                loadGuardiansUrl(this.href);
            });
        });
    }

    function updateBulkActionBar() {
        const selected = document.querySelectorAll('.guardian-row-checkbox:checked');
        const bar = document.getElementById('bulk-action-bar');
        const badge = document.getElementById('bulk-count-badge');
        const selectAll = document.getElementById('select-all-guardians');
        const totalRows = document.querySelectorAll('.guardian-row-checkbox').length;

        if (selected.length > 0) {
            bar.classList.remove('hidden');
            badge.textContent = selected.length;
            if (selectAll) {
                selectAll.checked = (selected.length === totalRows && totalRows > 0);
            }
        } else {
            bar.classList.add('hidden');
            badge.textContent = '0';
            if (selectAll) {
                selectAll.checked = false;
            }
        }
    }

    function clearAllSelections() {
        const selectAll = document.getElementById('select-all-guardians');
        if (selectAll) selectAll.checked = false;
        document.querySelectorAll('.guardian-row-checkbox').forEach(cb => cb.checked = false);
        updateBulkActionBar();
    }

    window.confirmGuardianAction = function(event, form, type, name) {
        if (event) event.preventDefault();

        let title = 'Are you sure?';
        let text = `Do you want to proceed with ${name}?`;
        let icon = 'warning';
        let confirmBtnText = 'Yes, proceed';
        let confirmBtnColor = '#e11d48';

        if (type === 'delete') {
            title = 'Move to Trash?';
            text = `Move ${name} to trash?`;
            icon = 'warning';
            confirmBtnText = 'Yes, move to trash';
            confirmBtnColor = '#e11d48';
        } else if (type === 'restore') {
            title = 'Restore Parent?';
            text = `Restore ${name} back to active list?`;
            icon = 'question';
            confirmBtnText = 'Yes, restore';
            confirmBtnColor = '#059669';
        } else if (type === 'force_delete') {
            title = 'Delete Permanently?';
            text = `Permanently delete ${name}? This cannot be undone!`;
            icon = 'error';
            confirmBtnText = 'Yes, delete permanently';
            confirmBtnColor = '#be123c';
        }

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                form.submit();
            }
        }
    };

    function executeBulkAction(action) {
        const selected = document.querySelectorAll('.guardian-row-checkbox:checked');
        if (selected.length === 0) return;

        let title = 'Are you sure?';
        let text = `Apply action to ${selected.length} parent record(s)?`;
        let icon = 'warning';
        let confirmBtnText = 'Yes, continue';
        let confirmBtnColor = '#e11d48';

        if (action === 'delete') {
            title = 'Move Selected to Trash?';
            text = `Move ${selected.length} parent record(s) to trash?`;
            icon = 'warning';
            confirmBtnText = 'Yes, move to trash';
            confirmBtnColor = '#e11d48';
        } else if (action === 'restore') {
            title = 'Restore Selected?';
            text = `Restore ${selected.length} parent record(s) back to active list?`;
            icon = 'question';
            confirmBtnText = 'Yes, restore';
            confirmBtnColor = '#059669';
        } else if (action === 'force_delete') {
            title = 'Delete Permanently?';
            text = `Permanently delete ${selected.length} parent record(s)? This cannot be undone!`;
            icon = 'error';
            confirmBtnText = 'Yes, delete permanently';
            confirmBtnColor = '#be123c';
        }

        const performSubmit = () => {
            const form = document.getElementById('bulk-action-form');
            document.getElementById('bulk-action-input').value = action;
            const container = document.getElementById('bulk-ids-container');
            container.innerHTML = '';

            selected.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'ids[]';
                hidden.value = cb.value;
                container.appendChild(hidden);
            });

            form.submit();
        };

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performSubmit();
                }
            });
        } else {
            if (confirm(text)) {
                performSubmit();
            }
        }
    }

    function loadGuardiansUrl(url) {
        const spinner = document.getElementById('search-spinner');
        const clearBtn = document.getElementById('search-clear-btn');
        const searchInput = document.getElementById('guardian-search-input');

        if (spinner) spinner.classList.remove('hidden');
        if (clearBtn && searchInput && searchInput.value) clearBtn.classList.add('hidden');

        if (searchAbortController) {
            searchAbortController.abort();
        }
        searchAbortController = new AbortController();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: searchAbortController.signal
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('guardians-table-container').innerHTML = html;
            initGuardianEventListeners();
            clearAllSelections();
            window.history.replaceState(null, '', url);
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Failed to load table:', err);
            }
        })
        .finally(() => {
            if (spinner) spinner.classList.add('hidden');
            if (clearBtn && searchInput && searchInput.value) clearBtn.classList.remove('hidden');
        });
    }

    function clearGuardianSearch() {
        const input = document.getElementById('guardian-search-input');
        if (input) {
            input.value = '';
            input.focus();
        }
        const clearBtn = document.getElementById('search-clear-btn');
        if (clearBtn) clearBtn.classList.add('hidden');

        const url = new URL(window.location.origin + window.location.pathname);
        url.searchParams.set('status', currentStatus);
        loadGuardiansUrl(url.toString());
    }

    document.addEventListener('DOMContentLoaded', function () {
        initGuardianEventListeners();

        const searchInput = document.getElementById('guardian-search-input');
        const clearBtn = document.getElementById('search-clear-btn');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                if (clearBtn) {
                    if (query.length > 0) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }

                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    const url = new URL(window.location.origin + window.location.pathname);
                    url.searchParams.set('status', currentStatus);
                    if (query) {
                        url.searchParams.set('search', query);
                    }
                    url.searchParams.set('page', '1');
                    loadGuardiansUrl(url.toString());
                }, 250);
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    clearGuardianSearch();
                }
            });
        }
    });
</script>
@endsection
