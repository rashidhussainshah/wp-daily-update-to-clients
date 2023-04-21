@extends('layouts.emails')

@section('title', $subject ? $subject. readableCurrentDate() : 'Daily Status Report'. readableCurrentDate())


@section('content')
    {!! $eodHtmlTemplate ? $eodHtmlTemplate : '' !!}
@stop
