@extends('layouts.admin')

@section('title', 'Notices | Administration')

@section('admin-content')
<div class="space-y-4">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Notice Board</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Notices</h1>
            <p class="text-xs text-slate-500">
                School circulars and announcements for staff, students, and parents.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.notices.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Notice</span>
            </a>
        </div>
    </div>

    {{-- Compact Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs">
            <span class="text-xs text-slate-500">Total</span>
            <p class="text-lg font-bold text-slate-900 mt-0.5" id="metric-total">{{ $metrics['total'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs">
            <span class="text-xs text-slate-500">Published</span>
            <p class="text-lg font-bold text-emerald-600 mt-0.5" id="metric-published">{{ $metrics['published'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs">
            <span class="text-xs text-slate-500">Pinned</span>
            <p class="text-lg font-bold text-amber-600 mt-0.5" id="metric-pinned">{{ $metrics['pinned'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs">
            <span class="text-xs text-slate-500">Drafts / Inactive</span>
            <p class="text-lg font-bold text-slate-600 mt-0.5" id="metric-draft">{{ ($metrics['draft'] ?? 0) + ($metrics['archived'] ?? 0) }}</p>
        </div>
    </div>

    {{-- Filter Toolbar: Status Tabs & Real-time Search --}}
    <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-2xs space-y-3">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            {{-- Status Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium pb-1 lg:pb-0" id="status-tabs-container">
                @php
                    $tabs = [
                        'all' => ['label' => 'All', 'count' => $metrics['total'] ?? 0],
                        'published' => ['label' => 'Published', 'count' => $metrics['published'] ?? 0],
                        'draft' => ['label' => 'Drafts', 'count' => $metrics['draft'] ?? 0],
                        'archived' => ['label' => 'Archived', 'count' => $metrics['archived'] ?? 0],
                        'trash' => ['label' => 'Trash', 'count' => $metrics['trash'] ?? 0],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    @php
                        $isSelected = ($status === $key);
                        $isTrash = ($key === 'trash');
                    @endphp
                    <a href="{{ route('admin.notices.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                       class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 transition-colors whitespace-nowrap cursor-pointer {{ $isSelected ? ($isTrash ? 'bg-rose-600 text-white font-semibold' : 'bg-violet-600 text-white font-semibold') : ($isTrash ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100') }}">
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded px-1.5 py-0.2 text-[10px] font-semibold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrash ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Dropdown Filters & Search (Inline) --}}
            <div class="flex items-center gap-2 flex-nowrap overflow-x-auto pb-1 lg:pb-0 shrink-0">
                {{-- Category Filter --}}
                <div class="w-36 sm:w-44 shrink-0">
                    <select id="notice-type-filter"
                            class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        <option value="">All Categories</option>
                        @foreach ($types as $key => $name)
                            <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Audience Filter --}}
                <div class="w-36 sm:w-44 shrink-0">
                    <select id="notice-audience-filter"
                            class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden">
                        <option value="">All Audiences</option>
                        @foreach ($audiences as $key => $name)
                            <option value="{{ $key }}" {{ request('audience') === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Box --}}
                <div class="relative w-44 sm:w-60 shrink-0">
                    <input type="text"
                           id="notice-search-input"
                           value="{{ $search }}"
                           autocomplete="off"
                           placeholder="Search notices..."
                           class="w-full h-[38px] rounded-lg border border-slate-200 bg-slate-50/70 pl-8 pr-8 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-600 focus:ring-2 focus:ring-violet-500/20 focus:outline-hidden transition">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    {{-- Search Spinner --}}
                    <div id="notice-search-spinner" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-3.5 w-3.5 text-violet-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>

                    {{-- Clear Search Button --}}
                    <button type="button"
                            id="notice-search-clear"
                            onclick="clearNoticeSearch()"
                            class="{{ empty($search) ? 'hidden' : '' }} absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Bulk Action Bar --}}
        <div id="bulk-action-bar" class="hidden flex items-center justify-between gap-3 p-2.5 rounded-md bg-slate-900 text-white text-xs transition">
            <div class="flex items-center gap-2">
                <span id="bulk-count-badge" class="font-bold bg-white/20 px-2 py-0.5 rounded text-[11px]">0</span>
                <span>selected</span>
            </div>
            <div class="flex items-center gap-2">
                @if ($status === 'trash')
                    <button type="button"
                            onclick="executeBulkAction('restore')"
                            class="inline-flex items-center gap-1.5 rounded bg-emerald-600 hover:bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <span>Restore</span>
                    </button>
                    <button type="button"
                            onclick="executeBulkAction('force_delete')"
                            class="inline-flex items-center gap-1.5 rounded bg-rose-600 hover:bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <span>Delete Permanently</span>
                    </button>
                @else
                    <button type="button"
                            onclick="executeBulkAction('delete')"
                            class="inline-flex items-center gap-1.5 rounded bg-rose-600 hover:bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white transition cursor-pointer">
                        <span>Move to Trash</span>
                    </button>
                @endif
                <button type="button"
                        onclick="clearAllSelections()"
                        class="rounded px-2 py-1 text-xs text-slate-300 hover:text-white transition cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Notices Table --}}
    <div id="notices-table-container" class="rounded-lg border border-slate-200 bg-white shadow-2xs overflow-hidden">
        @include('admin.notices.partials.table')
    </div>

</div>

{{-- Hidden Form for Bulk Action Submission --}}
<form id="bulk-action-form" method="POST" action="{{ route('admin.notices.bulk-action') }}" class="hidden">
    @csrf
    <input type="hidden" name="action" id="bulk-action-input">
    <div id="bulk-ids-container"></div>
</form>

<script>
    let searchDebounceTimer = null;
    let searchAbortController = null;
    const currentStatus = @json($status);

    function initNoticeEventListeners() {
        const selectAll = document.getElementById('select-all-notices');
        const rowCheckboxes = document.querySelectorAll('.notice-row-checkbox');

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

        const paginationLinks = document.querySelectorAll('.notice-pagination-links a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                loadNoticesUrl(this.href);
            });
        });
    }

    function updateBulkActionBar() {
        const selected = document.querySelectorAll('.notice-row-checkbox:checked');
        const bar = document.getElementById('bulk-action-bar');
        const badge = document.getElementById('bulk-count-badge');
        const selectAll = document.getElementById('select-all-notices');
        const totalRows = document.querySelectorAll('.notice-row-checkbox').length;

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
        const selectAll = document.getElementById('select-all-notices');
        if (selectAll) selectAll.checked = false;
        document.querySelectorAll('.notice-row-checkbox').forEach(cb => cb.checked = false);
        updateBulkActionBar();
    }

    window.confirmNoticeAction = function(event, form, type, title) {
        if (event) event.preventDefault();

        let modalTitle = 'Confirm Action';
        let modalText = `Proceed with "${title}"?`;
        let modalIcon = 'warning';
        let confirmBtnText = 'Yes';
        let confirmBtnColor = '#e11d48';

        if (type === 'delete') {
            modalTitle = 'Move to Trash';
            modalText = `Move "${title}" to trash?`;
            modalIcon = 'warning';
            confirmBtnText = 'Move to Trash';
            confirmBtnColor = '#e11d48';
        } else if (type === 'restore') {
            modalTitle = 'Restore Notice';
            modalText = `Restore "${title}" to active list?`;
            modalIcon = 'question';
            confirmBtnText = 'Restore';
            confirmBtnColor = '#059669';
        } else if (type === 'force_delete') {
            modalTitle = 'Delete Permanently';
            modalText = `Permanently delete "${title}"? This cannot be undone.`;
            modalIcon = 'warning';
            confirmBtnText = 'Delete Forever';
            confirmBtnColor = '#be123c';
        }

        if (window.Swal) {
            Swal.fire({
                title: modalTitle,
                text: modalText,
                icon: modalIcon,
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
            if (confirm(modalText)) {
                form.submit();
            }
        }
    };

    function executeBulkAction(action) {
        const selected = document.querySelectorAll('.notice-row-checkbox:checked');
        const ids = Array.from(selected).map(cb => cb.getAttribute('data-id')).filter(Boolean);

        if (ids.length === 0) return;

        let title = 'Bulk Action';
        let text = `Apply to ${ids.length} selected items?`;
        let confirmText = 'Confirm';
        let confirmColor = '#e11d48';
        let icon = 'warning';

        if (action === 'delete') {
            title = 'Move to Trash';
            text = `Move ${ids.length} selected notices to trash?`;
            confirmText = 'Move to Trash';
            confirmColor = '#e11d48';
        } else if (action === 'restore') {
            title = 'Restore Notices';
            text = `Restore ${ids.length} selected notices?`;
            confirmText = 'Restore';
            confirmColor = '#059669';
            icon = 'question';
        } else if (action === 'force_delete') {
            title = 'Delete Permanently';
            text = `Permanently delete ${ids.length} notices? This cannot be undone.`;
            confirmText = 'Delete Forever';
            confirmColor = '#be123c';
            icon = 'warning';
        }

        const proceed = () => {
            const form = document.getElementById('bulk-action-form');
            const actionInput = document.getElementById('bulk-action-input');
            const idsContainer = document.getElementById('bulk-ids-container');

            actionInput.value = action;
            idsContainer.innerHTML = '';

            ids.forEach(id => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = id;
                idsContainer.appendChild(hiddenInput);
            });

            form.submit();
        };

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    proceed();
                }
            });
        } else {
            if (confirm(text)) {
                proceed();
            }
        }
    }

    function triggerSearch(immediate = false) {
        clearTimeout(searchDebounceTimer);
        const delay = immediate ? 0 : 300;

        searchDebounceTimer = setTimeout(() => {
            const search = document.getElementById('notice-search-input').value.trim();
            const type = document.getElementById('notice-type-filter').value;
            const audience = document.getElementById('notice-audience-filter').value;

            const url = new URL(@json(route('admin.notices.index')));
            url.searchParams.set('status', currentStatus);
            if (search) url.searchParams.set('search', search);
            if (type) url.searchParams.set('type', type);
            if (audience) url.searchParams.set('audience', audience);

            loadNoticesUrl(url.toString());
        }, delay);
    }

    function loadNoticesUrl(url) {
        if (searchAbortController) {
            searchAbortController.abort();
        }
        searchAbortController = new AbortController();

        const spinner = document.getElementById('notice-search-spinner');
        if (spinner) spinner.classList.remove('hidden');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: searchAbortController.signal
        })
        .then(response => response.json())
        .then(data => {
            if (spinner) spinner.classList.add('hidden');
            const container = document.getElementById('notices-table-container');
            if (container && data.html) {
                container.innerHTML = data.html;
                initNoticeEventListeners();
                clearAllSelections();
            }
            if (data.metrics) {
                updateMetrics(data.metrics);
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                if (spinner) spinner.classList.add('hidden');
                console.error('Search error:', err);
            }
        });
    }

    function updateMetrics(metrics) {
        const total = document.getElementById('metric-total');
        const pub = document.getElementById('metric-published');
        const pin = document.getElementById('metric-pinned');
        const draft = document.getElementById('metric-draft');

        if (total && metrics.total !== undefined) total.textContent = metrics.total;
        if (pub && metrics.published !== undefined) pub.textContent = metrics.published;
        if (pin && metrics.pinned !== undefined) pin.textContent = metrics.pinned;
        if (draft && metrics.draft !== undefined) draft.textContent = (metrics.draft + (metrics.archived || 0));
    }

    function clearNoticeSearch() {
        const input = document.getElementById('notice-search-input');
        input.value = '';
        document.getElementById('notice-search-clear').classList.add('hidden');
        triggerSearch(true);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initNoticeEventListeners();

        const searchInput = document.getElementById('notice-search-input');
        const clearBtn = document.getElementById('notice-search-clear');
        const typeFilter = document.getElementById('notice-type-filter');
        const audienceFilter = document.getElementById('notice-audience-filter');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                if (this.value.trim().length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
                triggerSearch(false);
            });
        }

        if (typeFilter) {
            typeFilter.addEventListener('change', () => triggerSearch(true));
        }

        if (audienceFilter) {
            audienceFilter.addEventListener('change', () => triggerSearch(true));
        }

        if (window.jQuery) {
            window.jQuery('#notice-type-filter, #notice-audience-filter').on('change select2:select select2:clear', function () {
                triggerSearch(true);
            });
        }
    });
</script>
@endsection
