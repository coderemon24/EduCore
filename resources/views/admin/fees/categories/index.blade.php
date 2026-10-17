@extends('layouts.admin')

@section('title', 'Fee Categories Catalog | Administration')

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
                <span class="font-semibold text-slate-700">Fee Categories</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Institutional Fee Categories</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Define the institutional fee catalog (e.g. Tuition, Admission, Examination, Library, Laboratory, Sports).
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="window.openCreateCategoryModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Fee Category</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.fees.categories.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 items-end">
            <div>
                <label for="filter_search" class="block text-xs font-semibold text-slate-700 mb-1">Search Category</label>
                <div class="relative">
                    <input type="text"
                           name="search"
                           id="filter_search"
                           value="{{ request('search') }}"
                           placeholder="Category name or code..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-8 pr-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div>
                <label for="filter_status" class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" id="filter_status" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition cursor-pointer">
                    Apply Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.fees.categories.index') }}"
                       class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Categories Directory Table -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                        <th class="px-4 py-3 w-12 text-center">#</th>
                        <th class="px-4 py-3">Category Name</th>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 text-center">Fee Structures</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-center text-slate-400 font-mono">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                {{ $category->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $category->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 max-w-xs truncate">
                                {{ $category->description ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                                    {{ $category->fee_structures_count }} {{ Str::plural('rate', $category->fee_structures_count) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($category->is_active)
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
                                        onclick="window.openEditCategoryModal({{ json_encode($category) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </button>

                                @if($category->invoice_items_count == 0)
                                    <form method="POST" action="{{ route('admin.fees.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this fee category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">No fee categories registered</p>
                                <p class="text-xs text-slate-400 mt-1">Get started by defining tuition, admission, or examination fee categories.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Create / Edit Fee Category Modal -->
<div id="category-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/75 px-5 py-3.5">
            <h3 id="category-modal-title" class="text-sm font-bold text-slate-900">Add Fee Category</h3>
            <button type="button" onclick="window.closeCategoryModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="category-form" method="POST" action="{{ route('admin.fees.categories.store') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="category-form-method" value="POST">

            <div>
                <label for="category_name" class="block text-xs font-semibold text-slate-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                <input type="text"
                       name="name"
                       id="category_name"
                       required
                       placeholder="e.g. Monthly Tuition Fee"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
            </div>

            <div>
                <label for="category_code" class="block text-xs font-semibold text-slate-700 mb-1">Unique Code <span class="text-rose-500">*</span></label>
                <input type="text"
                       name="code"
                       id="category_code"
                       required
                       placeholder="e.g. TUITION, ADMISSION, EXAM"
                       class="w-full uppercase font-mono rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition">
                <p class="text-[10px] text-slate-400 mt-1">Short identifier used in receipts and ledger summaries.</p>
            </div>

            <div>
                <label for="category_description" class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description"
                          id="category_description"
                          rows="2"
                          placeholder="Institutional purpose of this fee..."
                          class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:outline-hidden transition"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox"
                       name="is_active"
                       id="category_active"
                       value="1"
                       checked
                       class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                <label for="category_active" class="text-xs font-medium text-slate-700 cursor-pointer">Active for invoicing</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button"
                        onclick="window.closeCategoryModal()"
                        class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition cursor-pointer shadow-2xs">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openCreateCategoryModal = function() {
        var modal = document.getElementById('category-modal');
        var form = document.getElementById('category-form');
        var title = document.getElementById('category-modal-title');
        var method = document.getElementById('category-form-method');

        form.reset();
        form.action = "{{ route('admin.fees.categories.store') }}";
        method.value = "POST";
        title.innerText = "Add Fee Category";
        document.getElementById('category_active').checked = true;

        modal.classList.remove('hidden');
    };

    window.openEditCategoryModal = function(category) {
        var modal = document.getElementById('category-modal');
        var form = document.getElementById('category-form');
        var title = document.getElementById('category-modal-title');
        var method = document.getElementById('category-form-method');

        form.action = "{{ url('admin/fees/categories') }}/" + category.id;
        method.value = "PUT";
        title.innerText = "Edit Fee Category: " + category.name;

        document.getElementById('category_name').value = category.name || '';
        document.getElementById('category_code').value = category.code || '';
        document.getElementById('category_description').value = category.description || '';
        document.getElementById('category_active').checked = Boolean(category.is_active);

        modal.classList.remove('hidden');
    };

    window.closeCategoryModal = function() {
        document.getElementById('category-modal').classList.add('hidden');
    };
</script>
@endsection
