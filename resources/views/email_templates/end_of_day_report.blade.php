@extends('emails.daily_emails_layout')

@section('title', $subject ? $subject. readableCurrentDate() : 'Daily Status Report'. readableCurrentDate())


@section('content')
    {!! $eodHtmlTemplate ? $eodHtmlTemplate : '' !!}
@stop
