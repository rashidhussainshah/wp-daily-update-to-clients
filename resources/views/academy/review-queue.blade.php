@extends('voyager::master')

@section('page_title', 'Academy Review Queue')

@section('page_header')
    <h1 class="page-title"><i class="voyager-check"></i> Academy Review Queue</h1>
@stop

@section('content')
<div class="page-content browse container-fluid">
    <div class="panel panel-bordered">
        <div class="panel-body">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @forelse($reviews as $review)
                <div style="border:1px solid #e2e8f0; border-radius:14px; padding:18px 20px; margin-bottom:14px;">
                    <div style="display:flex; justify-content:space-between;">
                        <div>
                            <strong>{{ $review->enrollment->user->name }}</strong> &middot; {{ $review->project_title }}
                            <div style="font-size:12px; color:#64748b;">{{ $review->enrollment->track->name }} &middot; Stage {{ $review->stage_index + 1 }} &middot; <a href="{{ $review->submission_link }}" target="_blank">{{ $review->submission_link }}</a></div>
                        </div>
                    </div>

                    @if($review->ai_verdict)
                        <div style="margin-top:12px; padding:12px 14px; border-radius:10px; background:#f0fdf4; border:1px solid #dcfce7;">
                            <strong style="color:#15803d;">AI Suggestion: {{ ucfirst(str_replace('_', ' ', $review->ai_verdict)) }}</strong>
                            @if($review->ai_score) <span style="color:#15803d;">&middot; {{ $review->ai_score }}/10</span> @endif
                            <div style="font-size:13px; color:#334155; margin-top:4px;">{{ $review->ai_feedback }}</div>
                        </div>
                    @elseif($review->ai_feedback)
                        <div style="margin-top:12px; padding:12px 14px; border-radius:10px; background:#fffbeb; border:1px solid #fef3c7; font-size:13px; color:#b45309;">{{ $review->ai_feedback }}</div>
                    @endif

                    <div style="margin-top:14px; display:flex; gap:10px;">
                        <form method="POST" action="{{ route('academy.review.approve', $review) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve &amp; Award</button>
                        </form>
                        <form method="POST" action="{{ route('academy.review.send-back', $review) }}">
                            @csrf
                            <button type="submit" class="btn btn-default">Send Back</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color:#64748b;">No pending submissions.</p>
            @endforelse
        </div>
    </div>
</div>
@stop
