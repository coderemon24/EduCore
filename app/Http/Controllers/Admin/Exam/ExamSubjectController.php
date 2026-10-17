<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\ExamSubjectComponent;
use App\Models\Subject;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamSubjectController extends Controller
{
    /**
     * Display subject configurations for an exam session.
     */
    public function index(Exam $exam, Request $request): View
    {
        $exam->load('academicYear');

        $selectedClassId = $request->input('academic_class_id');

        $query = ExamSubject::where('exam_id', $exam->id)
            ->with(['academicClass', 'subject', 'components'])
            ->withCount('examMarks');

        if ($selectedClassId) {
            $query->where('academic_class_id', $selectedClassId);
        }

        $examSubjects = $query->orderBy('academic_class_id')->orderBy('order_index')->get();
        $academicClasses = AcademicClass::active()->ordered()->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.exams.subjects', compact(
            'exam',
            'examSubjects',
            'academicClasses',
            'subjects',
            'selectedClassId'
        ));
    }

    /**
     * Store a newly configured subject with dynamic assessment components.
     */
    public function store(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'academic_class_id' => ['required', 'exists:academic_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'full_marks' => ['required', 'numeric', 'min:1', 'max:500'],
            'pass_marks' => ['required', 'numeric', 'min:0', 'lte:full_marks'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'components' => ['nullable', 'array'],
            'components.*.name' => ['required', 'string', 'max:100'],
            'components.*.max_marks' => ['required', 'numeric', 'min:0.5'],
            'components.*.pass_marks' => ['nullable', 'numeric', 'min:0'],
        ]);

        $schoolId = $exam->school_id;

        // Check if subject is already assigned to this class for this exam
        $exists = ExamSubject::where('exam_id', $exam->id)
            ->where('academic_class_id', $validated['academic_class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if ($exists) {
            ToastMagic::error('This subject is already configured for the selected class in this exam.');

            return back()->withInput();
        }

        // Validate component sum if components are defined
        $components = $validated['components'] ?? [];
        if (! empty($components)) {
            $compSum = array_sum(array_column($components, 'max_marks'));
            if (abs($compSum - (float) $validated['full_marks']) > 0.01) {
                ToastMagic::error("The sum of component maximum marks ({$compSum}) must match the subject full marks ({$validated['full_marks']}).");

                return back()->withInput();
            }
        }

        DB::transaction(function () use ($exam, $validated, $schoolId, $components) {
            $order = (ExamSubject::where('exam_id', $exam->id)->where('academic_class_id', $validated['academic_class_id'])->max('order_index') ?? 0) + 1;

            $examSubject = ExamSubject::create([
                'school_id' => $schoolId,
                'exam_id' => $exam->id,
                'academic_class_id' => $validated['academic_class_id'],
                'subject_id' => $validated['subject_id'],
                'full_marks' => $validated['full_marks'],
                'pass_marks' => $validated['pass_marks'],
                'exam_date' => $validated['exam_date'] ?? null,
                'start_time' => $validated['start_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'room_number' => $validated['room_number'] ?? null,
                'order_index' => $order,
            ]);

            if (empty($components)) {
                // Default single component matching full marks
                ExamSubjectComponent::create([
                    'exam_subject_id' => $examSubject->id,
                    'name' => 'Written',
                    'max_marks' => $validated['full_marks'],
                    'pass_marks' => $validated['pass_marks'],
                    'order_index' => 1,
                ]);
            } else {
                foreach ($components as $idx => $comp) {
                    ExamSubjectComponent::create([
                        'exam_subject_id' => $examSubject->id,
                        'name' => trim($comp['name']),
                        'max_marks' => (float) $comp['max_marks'],
                        'pass_marks' => (float) ($comp['pass_marks'] ?? 0.00),
                        'order_index' => $idx + 1,
                    ]);
                }
            }
        });

        ToastMagic::success('Subject and assessment components added successfully.');

        return redirect()->route('admin.exams.subjects.index', [
            'exam' => $exam,
            'academic_class_id' => $validated['academic_class_id'],
        ]);
    }

    /**
     * Remove the specified exam subject configuration.
     */
    public function destroy(Exam $exam, ExamSubject $subject): RedirectResponse
    {
        if ($subject->examMarks()->exists()) {
            ToastMagic::error('Cannot delete subject that already has student mark records.');

            return back();
        }

        $subject->components()->delete();
        $subject->delete();

        ToastMagic::success('Subject configuration removed.');

        return back();
    }
}
