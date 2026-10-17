@extends('layouts.admin')

@section('title', 'Fee Structures & Class Rates | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Fees & Accounts</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Fee Structures</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Fee Structures & Grade Rates</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Configure curriculum rates, recurring tuition, and term fees for classes and academic sessions.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="window.openCreateStructureModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Fee Structure</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.fees.structures.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end">
            <div>
                <label for="filter_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session</label>
                <select name="academic_year_id" id="filter_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Sessions</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_class" class="block text-xs font-semibold text-slate-700 mb-1">Academic Class</label>
                <select name="academic_class_id" id="filter_class" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Classes</option>
                    @foreach($academicClasses as $class)
                        <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_category" class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                <select name="fee_category_id" id="filter_category" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Categories</option>
                    @foreach($feeCategories as $category)
                        <option value="{{ $category->id }}" {{ request('fee_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_frequency" class="block text-xs font-semibold text-slate-700 mb-1">Billing Frequency</label>
                <select name="frequency" id="filter_frequency" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Frequencies</option>
                    <option value="monthly" {{ request('frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="term" {{ request('frequency') === 'term' ? 'selected' : '' }}>Per Term</option>
                    <option value="quarterly" {{ request('frequency') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ request('frequency') === 'yearly' ? 'selected' : '' }}>Yearly / Annual</option>
                    <option value="one_time" {{ request('frequency') === 'one_time' ? 'selected' : '' }}>One-Time</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['academic_year_id', 'academic_class_id', 'fee_category_id', 'frequency']))
                    <a href="{{ route('admin.fees.structures.index') }}"
                       class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Structures Directory Table -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                        <th class="px-4 py-3 w-12 text-center">#</th>
                        <th class="px-4 py-3">Fee Structure Name</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Target Class</th>
                        <th class="px-4 py-3">Session</th>
                        <th class="px-4 py-3">Frequency</th>
                        <th class="px-4 py-3 text-right">Standard Amount</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($structures as $index => $structure)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-center text-slate-400 font-mono">
                                {{ $structures->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                {{ $structure->name }}
                                @if($structure->due_day)
                                    <span class="block text-[11px] font-normal text-slate-400">Due by Day {{ $structure->due_day }} of month</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                                    {{ $structure->feeCategory?->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($structure->academicClass)
                                    <span class="font-medium text-slate-800">{{ $structure->academicClass->name }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        All Classes
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $structure->academicYear?->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst(str_replace('_', ' ', $structure->frequency)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-slate-900 text-sm">
                                {{ number_format($structure->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($structure->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-1 whitespace-nowrap">
                                <button type="button"
                                        onclick="window.openEditStructureModal({{ json_encode($structure) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('admin.fees.structures.destroy', $structure) }}" class="inline" onsubmit="return confirm('Delete this fee structure configuration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">No fee structures configured</p>
                                <p class="text-xs text-slate-400 mt-1">Define class-level tuition or term fee amounts to enable bulk student invoicing.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($structures->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $structures->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Create / Edit Fee Structure Modal -->
<div id="structure-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/75 px-5 py-3.5">
            <h3 id="structure-modal-title" class="text-sm font-bold text-slate-900">Add Fee Structure</h3>
            <button type="button" onclick="window.closeStructureModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="structure-form" method="POST" action="{{ route('admin.fees.structures.store') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="structure-form-method" value="POST">

            <div>
                <label for="structure_name" class="block text-xs font-semibold text-slate-700 mb-1">Structure Title <span class="text-rose-500">*</span></label>
                <input type="text"
                       name="name"
                       id="structure_name"
                       required
                       placeholder="e.g. Grade 10 Monthly Tuition or Annual Term 1 Fee"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="structure_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session <span class="text-rose-500">*</span></label>
                    <select name="academic_year_id" id="structure_year" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="structure_class" class="block text-xs font-semibold text-slate-700 mb-1">Target Class</label>
                    <select name="academic_class_id" id="structure_class" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="">All Classes (Universal)</option>
                        @foreach($academicClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="structure_category" class="block text-xs font-semibold text-slate-700 mb-1">Fee Category <span class="text-rose-500">*</span></label>
                    <select name="fee_category_id" id="structure_category" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="">Select Category</option>
                        @foreach($feeCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="structure_frequency" class="block text-xs font-semibold text-slate-700 mb-1">Billing Frequency <span class="text-rose-500">*</span></label>
                    <select name="frequency" id="structure_frequency" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="monthly">Monthly</option>
                        <option value="term">Per Term</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly / Annual</option>
                        <option value="one_time">One-Time</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="structure_amount" class="block text-xs font-semibold text-slate-700 mb-1">Standard Amount <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               name="amount"
                               id="structure_amount"
                               required
                               placeholder="0.00"
                               class="w-full rounded-lg border border-slate-200 font-mono font-bold px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
                    </div>
                </div>

                <div>
                    <label for="structure_due_day" class="block text-xs font-semibold text-slate-700 mb-1">Default Due Day (1-31) <span class="text-rose-500">*</span></label>
                    <input type="number"
                           min="1"
                           max="31"
                           name="due_day"
                           id="structure_due_day"
                           required
                           value="10"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>
            </div>

            <div>
                <label for="structure_description" class="block text-xs font-semibold text-slate-700 mb-1">Notes / Description</label>
                <textarea name="description"
                          id="structure_description"
                          rows="2"
                          placeholder="Optional breakdown instructions..."
                          class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox"
                       name="is_active"
                       id="structure_active"
                       value="1"
                       checked
                       class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                <label for="structure_active" class="text-xs font-medium text-slate-700 cursor-pointer">Active for invoice generation</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button"
                        onclick="window.closeStructureModal()"
                        class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition cursor-pointer shadow-2xs">
                    Save Structure
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openCreateStructureModal = function() {
        var modal = document.getElementById('structure-modal');
        var form = document.getElementById('structure-form');
        var title = document.getElementById('structure-modal-title');
        var method = document.getElementById('structure-form-method');

        form.reset();
        form.action = "{{ route('admin.fees.structures.store') }}";
        method.value = "POST";
        title.innerText = "Add Fee Structure";
        document.getElementById('structure_active').checked = true;
        document.getElementById('structure_due_day').value = "10";

        modal.classList.remove('hidden');
    };

    window.openEditStructureModal = function(structure) {
        var modal = document.getElementById('structure-modal');
        var form = document.getElementById('structure-form');
        var title = document.getElementById('structure-modal-title');
        var method = document.getElementById('structure-form-method');

        form.action = "{{ url('admin/fees/structures') }}/" + structure.id;
        method.value = "PUT";
        title.innerText = "Edit Fee Structure: " + structure.name;

        document.getElementById('structure_name').value = structure.name || '';
        document.getElementById('structure_year').value = structure.academic_year_id || '';
        document.getElementById('structure_class').value = structure.academic_class_id || '';
        document.getElementById('structure_category').value = structure.fee_category_id || '';
        document.getElementById('structure_frequency').value = structure.frequency || 'monthly';
        document.getElementById('structure_amount').value = structure.amount || '';
        document.getElementById('structure_due_day').value = structure.due_day || '10';
        document.getElementById('structure_description').value = structure.description || '';
        document.getElementById('structure_active').checked = Boolean(structure.is_active);

        modal.classList.remove('hidden');
    };

    window.closeStructureModal = function() {
        document.getElementById('structure-modal').classList.add('hidden');
    };
</script>
@endsection
