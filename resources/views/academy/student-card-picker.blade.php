@extends('voyager::master')

@section('page_title', 'Print Student ID Cards')

@section('page_header')
    <h1 class="page-title"><i class="voyager-credit-card"></i> Print Student ID Cards</h1>
@stop

@section('css')
<style>
    .rb-card{ display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:10px; border:1px solid #e2e8f0; border-radius:12px; padding:14px 16px; margin-bottom:12px; }
    .rb-badge{ display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; background:#f0fdf4; color:#15803d; }
</style>
@stop

@section('content')
<div class="page-content browse container-fluid">

    <p><a href="{{ route('academy.help') }}">❓ Help - how printing works</a></p>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($readyBatches->isNotEmpty())
        <div class="panel panel-bordered">
            <div class="panel-heading"><h3 class="panel-title">Ready-to-Print Batches</h3></div>
            <div class="panel-body">
                <p class="text-muted">Prepared by the Card Manager - open one, print it, then mark it done.</p>
                @foreach($readyBatches as $batch)
                    <div class="rb-card">
                        <div>
                            <strong>{{ $batch->label }}</strong>
                            <span class="rb-badge">{{ $batch->items->count() }} card(s)</span>
                            <div class="text-muted" style="font-size:11.5px;">prepared by {{ $batch->createdBy?->name }}</div>
                        </div>
                        <div>
                            <a href="{{ route('academy.student-cards.print-batch', $batch) }}" target="_blank" class="btn btn-success btn-sm">Print This Batch</a>
                            <form method="POST" action="{{ route('academy.student-cards.mark-batch-printed', $batch) }}" style="display:inline;" onsubmit="return confirm('Mark the whole batch as printed?');">
                                @csrf
                                <button type="submit" class="btn btn-default btn-sm">Mark Batch Printed</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">Print Any Student Directly</h3></div>
        <div class="panel-body">
            <p class="text-muted">To order a batch of cards for print: tick every student who needs one (a whole new cohort, or just a few stragglers), then hit Print - all of them come out together as one print run, each with its own QR code linking to their public verification badge.</p>

            <form method="POST" action="{{ route('academy.student-cards.print') }}" target="_blank">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Track</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td><input type="checkbox" name="user_ids[]" value="{{ $student->id }}" class="student-checkbox"></td>
                                <td><img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('storage/users/default.png') }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover;"></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->academyEnrollments->first()?->track?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">No Academy students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <button type="submit" class="btn btn-success">Print Selected Cards</button>
                <span id="selected-count" class="text-muted" style="margin-left:10px;">0 selected</span>
            </form>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
function updateSelectedCount() {
    var n = document.querySelectorAll('.student-checkbox:checked').length;
    document.getElementById('selected-count').textContent = n + ' selected';
}
document.getElementById('select-all').addEventListener('change', function () {
    document.querySelectorAll('.student-checkbox').forEach(function (cb) { cb.checked = document.getElementById('select-all').checked; });
    updateSelectedCount();
});
document.querySelectorAll('.student-checkbox').forEach(function (cb) {
    cb.addEventListener('change', updateSelectedCount);
});
</script>
@stop
