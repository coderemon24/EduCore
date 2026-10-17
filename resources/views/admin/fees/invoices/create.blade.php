@extends('layouts.admin')

@section('title', 'Generate Student Invoices | Administration')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Invoices</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Generate</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Issue Student Invoices</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Generate tuition and fee invoices for an entire class or an individual student.
            </p>
        </div>

        <a href="{{ route('admin.fees.invoices.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            &larr; Back to Invoices
        </a>
    </div>

    <!-- Mode Switcher -->
    <div class="flex rounded-xl border border-slate-200 bg-white p-1 shadow-2xs max-w-md">
        <button type="button"
                id="btn-mode-bulk"
                onclick="window.switchInvoiceMode('bulk')"
                class="flex-1 rounded-lg py-2 text-xs font-semibold text-white bg-violet-600 shadow-2xs transition cursor-pointer">
            Bulk Class Invoicing
        </button>
        <button type="button"
                id="btn-mode-single"
                onclick="window.switchInvoiceMode('single')"
                class="flex-1 rounded-lg py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition cursor-pointer">
            Single Student Invoice
        </button>
    </div>

    <form id="invoice-creation-form" method="POST" action="{{ route('admin.fees.invoices.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="generation_type" id="input_generation_type" value="bulk">

        <!-- Common Invoice Metadata Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">1. Invoice Schedule & Title</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label for="input_title" class="block text-xs font-semibold text-slate-700 mb-1">Invoice Title <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="title"
                           id="input_title"
                           required
                           value="{{ old('title', 'Monthly Tuition Fee - ' . date('F Y')) }}"
                           placeholder="e.g. Monthly Tuition Fee - October 2026"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="input_issue_date" class="block text-xs font-semibold text-slate-700 mb-1">Issue Date <span class="text-rose-500">*</span></label>
                    <input type="date"
                           name="issue_date"
                           id="input_issue_date"
                           required
                           value="{{ old('issue_date', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="input_due_date" class="block text-xs font-semibold text-slate-700 mb-1">Due Date <span class="text-rose-500">*</span></label>
                    <input type="date"
                           name="due_date"
                           id="input_due_date"
                           required
                           value="{{ old('due_date', date('Y-m-10', strtotime('+1 month'))) }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="input_remarks" class="block text-xs font-semibold text-slate-700 mb-1">Internal Remarks</label>
                    <input type="text"
                           name="remarks"
                           id="input_remarks"
                           placeholder="Optional cashier note..."
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
                </div>
            </div>
        </div>

        <!-- Section A: Bulk Class Target & Fee Structures Selection -->
        <div id="section-bulk-mode" class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">2. Target Class & Curriculum Fee Items</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="bulk_academic_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session <span class="text-rose-500">*</span></label>
                    <select name="academic_year_id" id="bulk_academic_year" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bulk_academic_class" class="block text-xs font-semibold text-slate-700 mb-1">Academic Class <span class="text-rose-500">*</span></label>
                    <select name="academic_class_id" id="bulk_academic_class" onchange="window.filterFeeStructuresByClass()" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="">Select Target Class</option>
                        @foreach($academicClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bulk_section" class="block text-xs font-semibold text-slate-700 mb-1">Section (Optional)</label>
                    <select name="section_id" id="bulk_section" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Fee Structures Checklist -->
            <div class="pt-2">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Select Fee Items to Apply to Each Student <span class="text-rose-500">*</span></label>
                <div class="space-y-2 border border-slate-200 rounded-lg p-3 max-h-60 overflow-y-auto bg-slate-50/50" id="structures-checklist-container">
                    @forelse($feeStructures as $structure)
                        <label class="flex items-center justify-between p-2 rounded-md hover:bg-white border border-transparent hover:border-slate-200 transition cursor-pointer fee-structure-row"
                               data-class-id="{{ $structure->academic_class_id ?? '' }}">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox"
                                       name="fee_structure_ids[]"
                                       value="{{ $structure->id }}"
                                       data-amount="{{ $structure->amount }}"
                                       onchange="window.calculateBulkPreview()"
                                       class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer fee-struct-checkbox">
                                <div>
                                    <span class="text-xs font-semibold text-slate-800">{{ $structure->name }}</span>
                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-700">
                                        {{ $structure->feeCategory?->name }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-900">
                                {{ number_format($structure->amount, 2) }}
                            </span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 p-2 italic">No fee structures configured. <a href="{{ route('admin.fees.structures.index') }}" class="text-violet-600 underline">Add fee structures</a> first.</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-between pt-3 text-xs">
                    <span class="text-slate-500 font-medium">Standard Total per Student:</span>
                    <span class="font-mono font-bold text-slate-900 text-sm" id="bulk-preview-subtotal">0.00</span>
                </div>
            </div>
        </div>

        <!-- Section B: Single Student Target & Custom Item Rows -->
        <div id="section-single-mode" class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4 hidden">
            <h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">2. Select Student & Itemized Charges</h2>

            <div>
                <label for="single_student_id" class="block text-xs font-semibold text-slate-700 mb-1">Select Student <span class="text-rose-500">*</span></label>
                <select name="student_id" id="single_student_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                    <option value="">Select Student (by name or admission number)</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">
                            {{ $st->first_name }} {{ $st->last_name }} (Adm: {{ $st->admission_number }}) - {{ $st->currentEnrollment?->academicClass?->name ?? 'Class N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Custom Items Table -->
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-semibold text-slate-700">Line Items Breakdown</label>
                    <button type="button" onclick="window.addCustomItemRow()" class="text-xs font-semibold text-violet-600 hover:text-violet-700 cursor-pointer">
                        + Add Line Item
                    </button>
                </div>

                <div class="space-y-2" id="custom-items-container">
                    <div class="grid grid-cols-12 gap-2 items-center item-row bg-slate-50/75 p-2 rounded-lg border border-slate-200">
                        <div class="col-span-4">
                            <select name="items[0][fee_category_id]" class="w-full rounded-md border border-slate-200 px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                                @foreach($feeCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-5">
                            <input type="text" name="items[0][name]" value="Tuition Fee" placeholder="Description..." class="w-full rounded-md border border-slate-200 px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                        </div>
                        <div class="col-span-2">
                            <input type="number" step="0.01" min="0.01" name="items[0][amount]" value="100.00" oninput="window.calculateSinglePreview()" placeholder="0.00" class="w-full rounded-md border border-slate-200 font-mono font-bold px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                        </div>
                        <div class="col-span-1 text-center">
                            <button type="button" onclick="this.closest('.item-row').remove(); window.calculateSinglePreview();" class="text-slate-400 hover:text-rose-600 cursor-pointer text-xs">
                                &times;
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 text-xs border-t border-slate-100">
                    <span class="text-slate-500 font-medium">Invoice Total:</span>
                    <span class="font-mono font-bold text-slate-900 text-sm" id="single-preview-subtotal">100.00</span>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <a href="{{ route('admin.fees.invoices.index') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition shadow-2xs cursor-pointer">
                Confirm & Generate Invoices
            </button>
        </div>
    </form>

</div>

<script>
    var itemIndex = 1;

    window.switchInvoiceMode = function(mode) {
        var inputType = document.getElementById('input_generation_type');
        var btnBulk = document.getElementById('btn-mode-bulk');
        var btnSingle = document.getElementById('btn-mode-single');
        var secBulk = document.getElementById('section-bulk-mode');
        var secSingle = document.getElementById('section-single-mode');

        if (mode === 'single') {
            inputType.value = 'single';
            btnSingle.classList.add('bg-violet-600', 'text-white', 'shadow-2xs');
            btnSingle.classList.remove('text-slate-600');
            btnBulk.classList.remove('bg-violet-600', 'text-white', 'shadow-2xs');
            btnBulk.classList.add('text-slate-600');

            secBulk.classList.add('hidden');
            secSingle.classList.remove('hidden');

            document.getElementById('bulk_academic_class').required = false;
            document.getElementById('single_student_id').required = true;
            window.calculateSinglePreview();
        } else {
            inputType.value = 'bulk';
            btnBulk.classList.add('bg-violet-600', 'text-white', 'shadow-2xs');
            btnBulk.classList.remove('text-slate-600');
            btnSingle.classList.remove('bg-violet-600', 'text-white', 'shadow-2xs');
            btnSingle.classList.add('text-slate-600');

            secSingle.classList.add('hidden');
            secBulk.classList.remove('hidden');

            document.getElementById('bulk_academic_class').required = true;
            document.getElementById('single_student_id').required = false;
            window.calculateBulkPreview();
        }
    };

    window.calculateBulkPreview = function() {
        var checkboxes = document.querySelectorAll('.fee-struct-checkbox:checked');
        var total = 0.00;
        checkboxes.forEach(function(cb) {
            total += parseFloat(cb.dataset.amount || 0);
        });
        document.getElementById('bulk-preview-subtotal').innerText = total.toFixed(2);
    };

    window.calculateSinglePreview = function() {
        var inputs = document.querySelectorAll('#custom-items-container input[name*="[amount]"]');
        var total = 0.00;
        inputs.forEach(function(inp) {
            total += parseFloat(inp.value || 0);
        });
        document.getElementById('single-preview-subtotal').innerText = total.toFixed(2);
    };

    window.addCustomItemRow = function() {
        var container = document.getElementById('custom-items-container');
        var row = document.createElement('div');
        row.className = "grid grid-cols-12 gap-2 items-center item-row bg-slate-50/75 p-2 rounded-lg border border-slate-200";

        var categoryOptions = `@foreach($feeCategories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach`;

        row.innerHTML = `
            <div class="col-span-4">
                <select name="items[${itemIndex}][fee_category_id]" class="w-full rounded-md border border-slate-200 px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                    ${categoryOptions}
                </select>
            </div>
            <div class="col-span-5">
                <input type="text" name="items[${itemIndex}][name]" value="Fee Item" placeholder="Description..." class="w-full rounded-md border border-slate-200 px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
            </div>
            <div class="col-span-2">
                <input type="number" step="0.01" min="0.01" name="items[${itemIndex}][amount]" value="50.00" oninput="window.calculateSinglePreview()" placeholder="0.00" class="w-full rounded-md border border-slate-200 font-mono font-bold px-2 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
            </div>
            <div class="col-span-1 text-center">
                <button type="button" onclick="this.closest('.item-row').remove(); window.calculateSinglePreview();" class="text-slate-400 hover:text-rose-600 cursor-pointer text-xs">
                    &times;
                </button>
            </div>
        `;
        container.appendChild(row);
        itemIndex++;
        window.calculateSinglePreview();
    };

    window.filterFeeStructuresByClass = function() {
        var selectedClassId = document.getElementById('bulk_academic_class').value;
        var rows = document.querySelectorAll('.fee-structure-row');

        rows.forEach(function(row) {
            var rowClassId = row.dataset.classId;
            if (!rowClassId || rowClassId === selectedClassId) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
                var cb = row.querySelector('.fee-struct-checkbox');
                if (cb) cb.checked = false;
            }
        });
        window.calculateBulkPreview();
    };
</script>
@endsection
