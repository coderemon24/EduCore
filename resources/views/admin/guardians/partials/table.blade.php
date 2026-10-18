<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 text-left text-xs text-slate-600">
        <thead class="bg-slate-50 text-[11px] font-semibold uppercase tracking-wider text-slate-700">
            <tr>
                <th scope="col" class="w-10 px-3 py-3 text-center">
                    <input type="checkbox"
                           id="select-all-guardians"
                           aria-label="Select all parents"
                           class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                </th>
                <th scope="col" class="px-4 py-3">Name</th>
                <th scope="col" class="px-4 py-3">Phone &amp; Email</th>
                <th scope="col" class="px-4 py-3">Children</th>
                <th scope="col" class="px-4 py-3">Job / ID</th>
                <th scope="col" class="px-4 py-3 text-center">Status</th>
                <th scope="col" class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($guardians as $guardian)
                <tr class="hover:bg-slate-50/70 transition guardian-table-row {{ $guardian->trashed() ? 'bg-rose-50/20' : '' }}" data-id="{{ $guardian->id }}">
                    {{-- Row Checkbox --}}
                    <td class="w-10 px-3 py-3 text-center">
                        <input type="checkbox"
                               name="ids[]"
                               value="{{ $guardian->id }}"
                               aria-label="Select {{ $guardian->name }}"
                               class="guardian-row-checkbox h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                    </td>

                    {{-- Name --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 shrink-0 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs border border-slate-200">
                                {{ strtoupper(substr($guardian->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.guardians.show', $guardian) }}" class="font-semibold text-slate-900 hover:text-violet-600 transition">
                                    {{ $guardian->name }}
                                </a>
                                @if($guardian->address)
                                    <p class="text-[11px] text-slate-400 truncate max-w-xs">{{ $guardian->address }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Phone & Email --}}
                    <td class="px-4 py-3 text-[11px]">
                        <div class="space-y-1">
                            <div class="flex items-center gap-1.5 font-mono">
                                <a href="tel:{{ $guardian->phone }}" class="text-slate-800 hover:text-violet-600 font-medium transition" title="Call {{ $guardian->phone }}">
                                    {{ $guardian->phone }}
                                </a>
                            </div>
                            @if($guardian->email)
                                <div class="flex items-center gap-1.5">
                                    <a href="mailto:{{ $guardian->email }}" class="text-slate-500 hover:text-violet-600 truncate max-w-xs transition" title="Email {{ $guardian->email }}">
                                        {{ $guardian->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- Children --}}
                    <td class="px-4 py-3">
                        @if($guardian->students->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 max-w-md">
                                @foreach($guardian->students->take(3) as $student)
                                    @php
                                        $rel = $student->pivot->relationship ?? 'Parent';
                                        $isEmergency = $student->pivot->is_emergency_contact ?? false;
                                        $currentClass = $student->currentEnrollment?->academicClass?->name;
                                        $currentSection = $student->currentEnrollment?->section?->name;
                                    @endphp
                                    <div class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] text-slate-700">
                                        <a href="{{ route('admin.students.show', $student) }}" class="font-medium hover:text-violet-600 transition">
                                            {{ $student->full_name }}
                                        </a>
                                        @if($currentClass)
                                            <span class="rounded bg-slate-200/70 text-slate-700 px-1 py-0.2 text-[9px] font-bold">
                                                {{ $currentClass }}{{ $currentSection ? ' '.$currentSection : '' }}
                                            </span>
                                        @endif
                                        <span class="text-slate-400">·</span>
                                        <span class="capitalize text-slate-500">{{ $rel }}</span>
                                        @if($isEmergency)
                                            <span class="text-rose-600 font-bold" title="Emergency Contact">!</span>
                                        @endif
                                    </div>
                                @endforeach

                                @if($guardian->students->count() > 3)
                                    <a href="{{ route('admin.guardians.show', $guardian) }}"
                                       class="inline-flex items-center rounded-md border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600 hover:text-violet-600 transition">
                                        +{{ $guardian->students->count() - 3 }} more
                                    </a>
                                @endif
                            </div>
                        @else
                            <span class="text-slate-400 text-[11px]">No children linked</span>
                        @endif
                    </td>

                    {{-- Job / National ID --}}
                    <td class="px-4 py-3 text-[11px]">
                        <div class="space-y-0.5">
                            <div class="text-slate-700">{{ $guardian->occupation ?: '—' }}</div>
                            @if($guardian->national_id)
                                <div class="font-mono text-[10px] text-slate-400">ID: {{ $guardian->national_id }}</div>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3 text-center">
                        @if($guardian->trashed())
                            <span class="inline-flex items-center gap-1 rounded border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-semibold text-rose-700">
                                Trash
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                Active
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.guardians.show', $guardian) }}"
                               title="View Details"
                               class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            @unless($guardian->trashed())
                                <a href="{{ route('admin.guardians.edit', $guardian) }}"
                                   title="Edit"
                                   class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-violet-600 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.guardians.destroy', $guardian) }}"
                                      onsubmit="confirmGuardianAction(event, this, 'delete', '{{ addslashes($guardian->name) }}');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Move to Trash"
                                            class="rounded p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.guardians.restore', $guardian->id) }}"
                                      onsubmit="confirmGuardianAction(event, this, 'restore', '{{ addslashes($guardian->name) }}');"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="Restore"
                                            class="rounded p-1 text-emerald-600 hover:bg-emerald-50 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.guardians.force-delete', $guardian->id) }}"
                                      onsubmit="confirmGuardianAction(event, this, 'force_delete', '{{ addslashes($guardian->name) }}');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Delete Forever"
                                            class="rounded p-1 text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="max-w-sm mx-auto space-y-2">
                            <div class="h-10 w-10 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-800">No parents found</h3>
                            <p class="text-xs text-slate-500">
                                @if(!empty($search))
                                    No records match "{{ $search }}". Try a different name or phone number.
                                @else
                                    There are no {{ $status === 'archived' ? 'trash' : $status }} records.
                                @endif
                            </p>
                            @if(!empty($search))
                                <button type="button"
                                        onclick="clearGuardianSearch()"
                                        class="inline-flex items-center text-xs font-semibold text-violet-600 hover:text-violet-700 cursor-pointer">
                                    Clear search
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination & Summary --}}
@if($guardians->hasPages() || $guardians->total() > 0)
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-t border-slate-200 px-4 py-3 bg-slate-50/50">
        <div class="text-xs text-slate-500">
            Showing <span class="font-semibold text-slate-700">{{ $guardians->firstItem() ?? 0 }}</span>
            to <span class="font-semibold text-slate-700">{{ $guardians->lastItem() ?? 0 }}</span>
            of <span class="font-semibold text-slate-700">{{ $guardians->total() }}</span> parents
        </div>
        <div class="guardian-pagination-links">
            {{ $guardians->links() }}
        </div>
    </div>
@endif
