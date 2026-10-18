<div class="overflow-x-auto">
    <table class="w-full text-left text-xs border-collapse">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-semibold text-slate-600">
                <th class="py-2.5 px-3 w-8 text-center">
                    <input type="checkbox"
                           id="select-all-notices"
                           class="rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer"
                           aria-label="Select all notices">
                </th>
                <th class="py-2.5 px-3 min-w-[280px]">Notice</th>
                <th class="py-2.5 px-3 min-w-[110px]">Category</th>
                <th class="py-2.5 px-3 min-w-[120px]">Audience</th>
                <th class="py-2.5 px-3 min-w-[110px]">Date</th>
                <th class="py-2.5 px-3 min-w-[90px]">File</th>
                <th class="py-2.5 px-3 min-w-[70px] text-center">Views</th>
                <th class="py-2.5 px-3 min-w-[90px]">Status</th>
                <th class="py-2.5 px-3 min-w-[130px] text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($notices as $notice)
                <tr class="hover:bg-slate-50/80 transition-colors {{ $notice->is_pinned ? 'bg-amber-50/40' : '' }}" id="notice-row-{{ $notice->id }}">
                    {{-- Row Checkbox --}}
                    <td class="py-2.5 px-3 text-center">
                        <input type="checkbox"
                               class="notice-row-checkbox rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer"
                               data-id="{{ $notice->id }}"
                               aria-label="Select notice">
                    </td>

                    {{-- Title, Pinned Status, and Meta --}}
                    <td class="py-2.5 px-3">
                        <div class="flex items-start gap-2">
                            @if ($notice->is_pinned)
                                <span class="text-amber-600 mt-0.5 shrink-0" title="Pinned notice">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.59-1.59a1 1 0 011.414 1.414l-1.59 1.59L18 10.777V12a1 1 0 01-1 1h-6v5a1 1 0 11-2 0v-5H3a1 1 0 01-1-1v-1.223l1.632-3.048-1.59-1.59A1 1 0 013.456 4.725l1.59 1.59L9 4.733V3a1 1 0 011-1z" />
                                    </svg>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('admin.notices.show', $notice) }}"
                                   class="font-semibold text-slate-900 hover:text-violet-700 transition block truncate text-xs">
                                    {{ $notice->title }}
                                </a>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                    {{ $notice->author?->name ?? 'Admin' }} &bull; {{ $notice->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="py-2.5 px-3">
                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                            {{ \App\Models\Notice::TYPES[$notice->type] ?? ucfirst($notice->type) }}
                        </span>
                    </td>

                    {{-- Target Audience --}}
                    <td class="py-2.5 px-3">
                        <span class="text-xs text-slate-700 font-medium truncate block">
                            @if ($notice->target_audience === 'class_specific' && $notice->academicClass)
                                Class: {{ $notice->academicClass->name }}
                            @elseif ($notice->target_audience === 'all')
                                Everyone
                            @elseif ($notice->target_audience === 'teachers')
                                Teachers &amp; Staff
                            @elseif ($notice->target_audience === 'students')
                                Students
                            @elseif ($notice->target_audience === 'guardians')
                                Parents
                            @else
                                {{ ucfirst($notice->target_audience) }}
                            @endif
                        </span>
                    </td>

                    {{-- Date --}}
                    <td class="py-2.5 px-3 text-slate-600">
                        <span>{{ $notice->published_at ? $notice->published_at->format('M d, Y') : 'Draft' }}</span>
                        @if ($notice->expires_at)
                            <span class="block text-[10px] text-slate-400">Exp: {{ $notice->expires_at->format('M d') }}</span>
                        @endif
                    </td>

                    {{-- Attachment --}}
                    <td class="py-2.5 px-3">
                        @if ($notice->attachment_path)
                            <a href="{{ asset('storage/' . $notice->attachment_path) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-[11px] font-medium text-violet-700 hover:text-violet-900 hover:underline">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span>PDF/Doc</span>
                            </a>
                        @else
                            <span class="text-slate-300">&mdash;</span>
                        @endif
                    </td>

                    {{-- Views --}}
                    <td class="py-2.5 px-3 text-center text-slate-600 font-medium">
                        {{ number_format($notice->view_count) }}
                    </td>

                    {{-- Status Badge --}}
                    <td class="py-2.5 px-3">
                        @if ($notice->status === 'published')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Published
                            </span>
                        @elseif ($notice->status === 'draft')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                Archived
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="py-2.5 px-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if ($status === 'trash')
                                {{-- Restore --}}
                                <form method="POST" action="{{ route('admin.notices.restore', $notice->id) }}" class="inline">
                                    @csrf
                                    <button type="button"
                                            onclick="confirmNoticeAction(event, this.form, 'restore', '{{ addslashes($notice->title) }}')"
                                            class="inline-flex items-center gap-1 rounded px-2 py-1 text-[11px] font-medium text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition cursor-pointer"
                                            title="Restore">
                                        Restore
                                    </button>
                                </form>

                                {{-- Permanent Delete --}}
                                <form method="POST" action="{{ route('admin.notices.force-delete', $notice->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmNoticeAction(event, this.form, 'force_delete', '{{ addslashes($notice->title) }}')"
                                            class="inline-flex items-center gap-1 rounded px-2 py-1 text-[11px] font-medium text-rose-700 hover:bg-rose-50 border border-rose-200 transition cursor-pointer"
                                            title="Delete permanently">
                                        Delete
                                    </button>
                                </form>
                            @else
                                {{-- View --}}
                                <a href="{{ route('admin.notices.show', $notice) }}"
                                   class="inline-flex h-7 w-7 items-center justify-center rounded text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition"
                                   title="View">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.notices.edit', $notice) }}"
                                   class="inline-flex h-7 w-7 items-center justify-center rounded text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition"
                                   title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Pin Toggle --}}
                                <form method="POST" action="{{ route('admin.notices.toggle-pin', $notice) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex h-7 w-7 items-center justify-center rounded {{ $notice->is_pinned ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-slate-400 hover:bg-slate-100 hover:text-slate-700' }} transition cursor-pointer"
                                            title="{{ $notice->is_pinned ? 'Unpin' : 'Pin to top' }}">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.59-1.59a1 1 0 011.414 1.414l-1.59 1.59L18 10.777V12a1 1 0 01-1 1h-6v5a1 1 0 11-2 0v-5H3a1 1 0 01-1-1v-1.223l1.632-3.048-1.59-1.59A1 1 0 013.456 4.725l1.59 1.59L9 4.733V3a1 1 0 011-1z" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Delete / Trash --}}
                                <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmNoticeAction(event, this.form, 'delete', '{{ addslashes($notice->title) }}')"
                                            class="inline-flex h-7 w-7 items-center justify-center rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Move to trash">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <p class="text-xs font-medium text-slate-600">No notices found.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Try changing your search or filters.</p>
                            @if ($status !== 'trash')
                                <a href="{{ route('admin.notices.create') }}"
                                   class="mt-3 inline-flex items-center gap-1 rounded bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-violet-700 transition">
                                    Create Notice
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($notices->hasPages())
    <div class="notice-pagination-links px-3 py-2.5 border-t border-slate-200 bg-slate-50">
        {{ $notices->links() }}
    </div>
@endif
