<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyCardBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Printable Academy ID cards - pick one or many students, print them
 * together (batch order: select as many as needed, one print run makes
 * every card in that batch). HR, Administrator, Academy Accountant, and
 * Academy Printer can all reach this - accountants already track which
 * students are active/paid and printer is a dedicated capability for staff
 * whose only Academy job is producing physical cards.
 */
class AcademyStudentCardController extends Controller
{
    protected function authorizeStaff(): void
    {
        $user = Auth::user();
        $role = optional($user)->role?->name;

        abort_unless(
            Auth::check() && (
                $role === 'HR'
                || isAdministrator()
                || $user->isAcademyAccountant()
                || $user->isAcademyPrinter()
            ),
            403
        );
    }

    public function index()
    {
        $this->authorizeStaff();

        $students = User::onlyItAcademyStudent()
            ->with('academyEnrollments.track')
            ->orderBy('name')
            ->get();

        $readyBatches = AcademyCardBatch::ready()
            ->with('items.user', 'createdBy')
            ->latest('ready_at')
            ->get();

        return view('academy.student-card-picker', compact('students', 'readyBatches'));
    }

    public function print(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $students = User::whereIn('id', $data['user_ids'])
            ->with('academyEnrollments.track')
            ->get();

        return view('academy.student-card-print', compact('students'));
    }

    public function printBatch(AcademyCardBatch $batch)
    {
        $this->authorizeStaff();

        $students = $batch->students()->with('academyEnrollments.track')->get();

        return view('academy.student-card-print', compact('students', 'batch'));
    }

    public function markBatchPrinted(AcademyCardBatch $batch)
    {
        $this->authorizeStaff();

        $batch->update([
            'status' => AcademyCardBatch::STATUS_PRINTED,
            'printed_by' => Auth::id(),
            'printed_at' => now(),
        ]);
        $batch->items()->update(['printed' => true, 'printed_at' => now()]);

        return redirect()->route('academy.student-cards.index')->with('status', "\"{$batch->label}\" marked as printed.");
    }

    public function markItemPrinted(\App\Models\AcademyCardBatchItem $item)
    {
        $this->authorizeStaff();

        $item->update(['printed' => true, 'printed_at' => now()]);

        // If every card in the batch is now printed, close the batch out too.
        $batch = $item->batch;
        if ($batch->items()->where('printed', false)->doesntExist()) {
            $batch->update(['status' => AcademyCardBatch::STATUS_PRINTED, 'printed_by' => Auth::id(), 'printed_at' => now()]);
        }

        return back()->with('status', 'Card marked printed.');
    }
}
