@extends('voyager::master')

@section('page_title', 'Issue a Certificate')

@section('page_header')
    <h1 class="page-title"><i class="voyager-award"></i> Issue a Course Certificate</h1>
@stop

@section('content')
<div class="page-content browse container-fluid">
    <div class="panel panel-bordered">
        <div class="panel-body">
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                    @if(session('download_url'))
                        &middot; <a href="{{ session('download_url') }}" target="_blank">Download PDF</a>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('academy.course-certificates.store') }}" style="max-width:500px;">
                @csrf
                <div class="form-group">
                    <label>Student</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">Select...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Name on certificate</label>
                    <input type="text" name="recipient_name" class="form-control" placeholder="Editable for spelling corrections" required>
                </div>
                <button type="submit" class="btn btn-success">Generate</button>
            </form>
        </div>
    </div>
</div>
@stop
