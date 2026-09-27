@extends('voyager::master')

@section('page_title', 'Prepare Print Batches')

@section('page_header')
    <h1 class="page-title"><i class="voyager-credit-card"></i> Prepare Print Batches</h1>
@stop

@section('css')
<style>
    .cb-badge{ display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .cb-draft{ background:#f1f5f9; color:#475569; }
    .cb-ready{ background:#f0fdf4; color:#15803d; }
    .cb-printed{ background:#dcfce7; color:#166534; border:1px solid #16a34a; }
    .cb-batch-card{ border:1px solid #e2e8f0; border-radius:12px; padding:16px 18px; margin-bottom:14px; }
    .cb-batch-head{ display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:10px; margin-bottom:10px; }
    .cb-chip-list{ display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .cb-chip{ display:flex; align-items:center; gap:6px; background:#f8faf9; border:1px solid #e2e8f0; border-radius:999px; padding:4px 6px 4px 10px; font-size:12px; }
    .cb-chip form{ display:inline; }
    .cb-chip button{ background:none; border:0; color:#ef4444; font-weight:700; cursor:pointer; padding:0 4px; }
    .cb-filters{ display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; margin-bottom:16px; }
    .cb-filters .form-group{ margin-bottom:0; min-width:160px; }
    @media (max-width:640px){ .cb-filters{ flex-direction:column; align-items:stretch; } .cb-filters .form-group{ min-width:0; } }
</style>
@stop

@section('content')
<div class="page-content browse container-fluid">

    <p><a href="{{ route('academy.help') }}">❓ Help - how batch printing works</a></p>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">1. Filter students</h3></div>
        <div class="panel-body">
            <form method="GET" action="{{ route('academy.card-batches.index') }}" class="cb-filters">
                <div class="form-group">
                    <label>Track</label>
                    <select name="track_id" class="form-control">
                        <option value="">All tracks</option>
                        @foreach($tracks as $track)
                            <option value="{{ $track->id }}" @selected($trackId == $track->id)>{{ $track->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Instructor</label>
                    <select name="instructor_id" class="form-control">
                        <option value="">All instructors</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @selected($instructorId == $instructor->id)>{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Search by name or email</label>
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="e.g. Ayesha">
                </div>
                <div class="form-group">
                    <label style="display:block;">&nbsp;</label>
                    <label style="font-weight:normal;"><input type="checkbox" name="only_unbatched" value="1" @checked($onlyUnbatched)> Only students not in any batch yet</label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    @if($trackId || $instructorId || $search || $onlyUnbatched)
                        <a href="{{ route('academy.card-batches.index') }}" class="btn btn-default">Reset</a>
                    @endif
                </div>
            </form>

            <form method="POST" action="{{ route('academy.card-batches.add-students') }}">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Track</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php $enrollment = $student->academyEnrollments->first(); @endphp
                            <tr>
                                <td><input type="checkbox" name="user_ids[]" value="{{ $student->id }}" class="student-checkbox"></td>
                                <td><img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('storage/users/default.png') }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;"></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $enrollment?->track?->name ?? '-' }}</td>
                                <td>{{ $enrollment?->instructor?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">No students match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if($students->isNotEmpty())
                    <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                        <span id="selected-count" class="text-muted">0 selected</span>
                        <select name="batch_id" id="batch-select" class="form-control" style="width:auto;">
                            <option value="">+ Create a new batch</option>
                            @foreach($batches->where('status', 'draft') as $batch)
                                <option value="{{ $batch->id }}">Add to: {{ $batch->label }} ({{ $batch->items->count() }} cards)</option>
                            @endforeach
                        </select>
                        <input type="text" name="new_batch_label" id="new-batch-label" class="form-control" style="width:auto;" placeholder="New batch name (optional)">
                        <button type="submit" class="btn btn-success">Add Selected to Batch</button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="panel panel-bordered">
        <div class="panel-heading"><h3 class="panel-title">2. Batches</h3></div>
        <div class="panel-body">
            @forelse($batches as $batch)
                <div class="cb-batch-card">
                    <div class="cb-batch-head">
                        <div>
                            <strong>{{ $batch->label }}</strong>
                            <span class="cb-badge cb-{{ $batch->status }}">{{ ucfirst($batch->status) }}</span>
                            <div class="text-muted" style="font-size:11.5px; margin-top:2px;">
                                {{ $batch->items->count() }} card(s) &middot; created by {{ $batch->createdBy?->name }}
                                @if($batch->status === 'printed')
                                    &middot; printed by {{ $batch->printedBy?->name }} on {{ $batch->printed_at?->format('d M Y, g:ia') }}
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($batch->status === 'draft')
                                <form method="POST" action="{{ route('academy.card-batches.mark-ready', $batch) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Mark Ready to Print</button>
                                </form>
                            @endif
                            @if($batch->status !== 'printed')
                                <form method="POST" action="{{ route('academy.card-batches.destroy', $batch) }}" style="display:inline;" onsubmit="return confirm('Delete this batch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="cb-chip-list">
                        @foreach($batch->items as $item)
                            <div class="cb-chip">
                                {{ $item->printed ? '✅' : '' }} {{ $item->user->name }}
                                @if($batch->status !== 'printed')
                                    <form method="POST" action="{{ route('academy.card-batches.remove-item', $item) }}" onsubmit="return confirm('Remove this student from the batch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove">&times;</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-muted">No batches yet - filter students above and add them to a new batch.</p>
            @endforelse
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
function updateSelectedCount() {
    var n = document.querySelectorAll('.student-checkbox:checked').length;
    var el = document.getElementById('selected-count');
    if (el) el.textContent = n + ' selected';
}
var selectAll = document.getElementById('select-all');
if (selectAll) {
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.student-checkbox').forEach(function (cb) { cb.checked = selectAll.checked; });
        updateSelectedCount();
    });
}
document.querySelectorAll('.student-checkbox').forEach(function (cb) {
    cb.addEventListener('change', updateSelectedCount);
});
var batchSelect = document.getElementById('batch-select');
var newBatchLabel = document.getElementById('new-batch-label');
function syncNewBatchField() {
    newBatchLabel.style.display = batchSelect.value ? 'none' : 'inline-block';
}
if (batchSelect && newBatchLabel) {
    batchSelect.addEventListener('change', syncNewBatchField);
    syncNewBatchField();
}
</script>
@stop
