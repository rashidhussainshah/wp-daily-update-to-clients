<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyCardBatch;
use App\Models\AcademyCardBatchItem;
use App\Models\AcademyTrack;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The Card Manager's job: assemble "print batches" (a named, filtered group
 * of students) for the Printer to actually run - track/instructor filters
 * plus a manual name/email search to catch anyone the filters miss, add to
 * a brand-new batch or an existing draft one ("page"), then mark the batch
 * Ready when it's complete. The Printer picks up ready batches from
 * AcademyStudentCardController and marks them (or individual cards inside
 * them) printed once done.
 */
class AcademyCardBatchController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyCardManager() || isAdministrator()), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $trackId = $request->query('track_id');
        $instructorId = $request->query('instructor_id');
        $search = trim((string) $request->query('search', ''));
        $onlyUnbatched = $request->boolean('only_unbatched');

        $students = User::onlyItAcademyStudent()
            ->with('academyEnrollments.track', 'academyEnrollments.instructor')
            ->when($trackId, fn ($q) => $q->whereHas('academyEnrollments', fn ($e) => $e->where('track_id', $trackId)))
            ->when($instructorId, fn ($q) => $q->whereHas('academyEnrollments', fn ($e) => $e->where('instructor_id', $instructorId)))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->when($onlyUnbatched, fn ($q) => $q->whereDoesntHave('cardBatchItems'))
            ->orderBy('name')
            ->get();

        $tracks = AcademyTrack::orderBy('name')->get(['id', 'name']);
        $instructors = User::academyInstructors()->orderBy('name')->get(['id', 'name']);

        $batches = AcademyCardBatch::with('items.user', 'createdBy', 'printedBy')
            ->latest()
            ->get();

        return view('academy.card-batches', compact('students', 'tracks', 'instructors', 'batches', 'trackId', 'instructorId', 'search', 'onlyUnbatched'));
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate(['label' => 'nullable|string|max:120']);

        $batch = AcademyCardBatch::create([
            'label' => $data['label'] ?: 'Batch - ' . now()->format('d M Y, g:ia'),
            'status' => AcademyCardBatch::STATUS_DRAFT,
            'created_by' => Auth::id(),
        ]);

        return back()->with('status', "Created \"{$batch->label}\" - now add students to it.");
    }

    public function addStudents(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'batch_id' => 'nullable|exists:academy_card_batches,id',
            'new_batch_label' => 'nullable|string|max:120',
        ]);

        if (!empty($data['batch_id'])) {
            $batch = AcademyCardBatch::findOrFail($data['batch_id']);
        } else {
            $batch = AcademyCardBatch::create([
                'label' => $data['new_batch_label'] ?: 'Batch - ' . now()->format('d M Y, g:ia'),
                'status' => AcademyCardBatch::STATUS_DRAFT,
                'created_by' => Auth::id(),
            ]);
        }

        abort_if($batch->status === AcademyCardBatch::STATUS_PRINTED, 422, 'This batch has already been printed - start a new one.');

        foreach ($data['user_ids'] as $userId) {
            AcademyCardBatchItem::firstOrCreate(['batch_id' => $batch->id, 'user_id' => $userId]);
        }

        return back()->with('status', count($data['user_ids']) . " student(s) added to \"{$batch->label}\".");
    }

    public function removeItem(AcademyCardBatchItem $item)
    {
        $this->authorizeStaff();

        abort_if($item->batch->status === AcademyCardBatch::STATUS_PRINTED, 422, 'This batch has already been printed.');

        $item->delete();

        return back()->with('status', 'Removed from batch.');
    }

    public function markReady(AcademyCardBatch $batch)
    {
        $this->authorizeStaff();

        abort_if($batch->items()->count() === 0, 422, 'Add at least one student before marking this batch ready.');

        $batch->update(['status' => AcademyCardBatch::STATUS_READY, 'ready_at' => now()]);

        return back()->with('status', "\"{$batch->label}\" is ready to print - the Printer can now pick it up.");
    }

    public function destroy(AcademyCardBatch $batch)
    {
        $this->authorizeStaff();

        abort_if($batch->status === AcademyCardBatch::STATUS_PRINTED, 422, 'Printed batches are kept as a record and cannot be deleted.');

        $batch->delete();

        return back()->with('status', 'Batch deleted.');
    }
}
