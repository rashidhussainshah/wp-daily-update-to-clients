@extends('layouts.emails')

@section('title', 'Daily Report '. readableCurrentDate())


@section('content')
    {!! $eodHtmlTemplate ? $eodHtmlTemplate : '' !!}
@stop
