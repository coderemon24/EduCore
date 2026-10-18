<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notice\StoreNoticeRequest;
use App\Http\Requests\Admin\Notice\UpdateNoticeRequest;
use App\Models\AcademicClass;
use App\Models\Notice;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NoticeController extends Controller
{
    /**
     * Display a listing of institutional notices.
     */
    public function index(Request $request): View|JsonResponse
    {
        $status = $request->input('status', 'all');
        $type = $request->input('type');
        $audience = $request->input('audience');
        $search = $request->input('search');

        // Base query
        $query = Notice::with(['author', 'academicClass'])->latest('is_pinned')->latest('published_at')->latest('id');

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            if ($status === 'published') {
                $query->where('status', Notice::STATUS_PUBLISHED);
            } elseif ($status === 'draft') {
                $query->where('status', Notice::STATUS_DRAFT);
            } elseif ($status === 'archived') {
                $query->where('status', Notice::STATUS_ARCHIVED);
            }
        }

        if (! empty($type)) {
            $query->where('type', $type);
        }

        if (! empty($audience)) {
            $query->where('target_audience', $audience);
        }

        if (! empty($search)) {
            $query->search($search);
        }

        $notices = $query->paginate(15)->withQueryString();

        // Calculate metric counts for tabs and status counters
        $metrics = [
            'total' => Notice::count(),
            'published' => Notice::where('status', Notice::STATUS_PUBLISHED)->count(),
            'draft' => Notice::where('status', Notice::STATUS_DRAFT)->count(),
            'archived' => Notice::where('status', Notice::STATUS_ARCHIVED)->count(),
            'trash' => Notice::onlyTrashed()->count(),
            'pinned' => Notice::where('is_pinned', true)->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.notices.partials.table', compact('notices', 'status'))->render(),
                'metrics' => $metrics,
            ]);
        }

        $classes = AcademicClass::orderBy('order_index')->get();
        $types = Notice::TYPES;
        $audiences = Notice::AUDIENCES;

        return view('admin.notices.index', compact(
            'notices',
            'metrics',
            'status',
            'type',
            'audience',
            'search',
            'classes',
            'types',
            'audiences'
        ));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create(): View
    {
        $classes = AcademicClass::orderBy('order_index')->get();
        $types = Notice::TYPES;
        $audiences = Notice::AUDIENCES;
        $statuses = Notice::STATUSES;

        return view('admin.notices.create', compact('classes', 'types', 'audiences', 'statuses'));
    }

    /**
     * Store a newly created notice in storage.
     */
    public function store(StoreNoticeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        // Handle attachment file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $schoolId = Auth::guard('web')->user()?->school_id ?? 1;
            $path = $file->store("uploads/notices/{$schoolId}", 'public');

            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $originalName;
        }

        Notice::create($validated);

        ToastMagic::success('Notice published successfully.');

        return redirect()->route('admin.notices.index');
    }

    /**
     * Display the specified notice bulletin.
     */
    public function show(Notice $notice): View
    {
        $notice->increment('view_count');
        $notice->load(['author', 'academicClass']);

        return view('admin.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice): View
    {
        $classes = AcademicClass::orderBy('order_index')->get();
        $types = Notice::TYPES;
        $audiences = Notice::AUDIENCES;
        $statuses = Notice::STATUSES;

        return view('admin.notices.edit', compact('notice', 'classes', 'types', 'audiences', 'statuses'));
    }

    /**
     * Update the specified notice in storage.
     */
    public function update(UpdateNoticeRequest $request, Notice $notice): RedirectResponse
    {
        $validated = $request->validated();

        // Handle attachment removal request
        if (! empty($validated['remove_attachment'])) {
            if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
                Storage::disk('public')->delete($notice->attachment_path);
            }
            $validated['attachment_path'] = null;
            $validated['attachment_name'] = null;
        }

        // Handle new attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old file if present
            if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
                Storage::disk('public')->delete($notice->attachment_path);
            }

            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $schoolId = Auth::guard('web')->user()?->school_id ?? 1;
            $path = $file->store("uploads/notices/{$schoolId}", 'public');

            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $originalName;
        }

        $notice->update($validated);

        ToastMagic::success('Notice updated successfully.');

        return redirect()->route('admin.notices.index');
    }

    /**
     * Remove the specified notice from active listings (soft delete).
     */
    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        ToastMagic::success('Notice moved to trash.');

        return back();
    }

    /**
     * Restore a soft-deleted notice.
     */
    public function restore(int $id): RedirectResponse
    {
        $notice = Notice::onlyTrashed()->findOrFail($id);
        $notice->restore();

        ToastMagic::success('Notice restored successfully.');

        return back();
    }

    /**
     * Permanently delete a notice and its file attachment.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $notice = Notice::withTrashed()->findOrFail($id);

        if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
            Storage::disk('public')->delete($notice->attachment_path);
        }

        $notice->forceDelete();

        ToastMagic::success('Notice permanently deleted.');

        return back();
    }

    /**
     * Toggle the pinned status of a notice.
     */
    public function togglePin(Notice $notice): RedirectResponse|JsonResponse
    {
        $notice->is_pinned = ! $notice->is_pinned;
        $notice->save();

        $message = $notice->is_pinned ? 'Notice pinned to top.' : 'Notice unpinned.';
        ToastMagic::success($message);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_pinned' => $notice->is_pinned,
                'message' => $message,
            ]);
        }

        return back();
    }

    /**
     * Perform bulk actions (trash, restore, delete forever) on selected notices.
     */
    public function bulkAction(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:delete,restore,force_delete'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];
        $count = count($ids);

        if ($action === 'delete') {
            Notice::whereIn('id', $ids)->delete();
            $message = "{$count} ".Str::plural('notice', $count).' moved to trash.';
        } elseif ($action === 'restore') {
            Notice::onlyTrashed()->whereIn('id', $ids)->restore();
            $message = "{$count} ".Str::plural('notice', $count).' restored successfully.';
        } elseif ($action === 'force_delete') {
            $notices = Notice::withTrashed()->whereIn('id', $ids)->get();
            foreach ($notices as $notice) {
                if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
                    Storage::disk('public')->delete($notice->attachment_path);
                }
                $notice->forceDelete();
            }
            $message = "{$count} ".Str::plural('notice', $count).' permanently deleted.';
        }

        ToastMagic::success($message);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back();
    }
}
