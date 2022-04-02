@extends('layouts.emails')

@section('title', 'End of Day Report')


@section('content')
    {!! $eodHtmlTemplate ? $eodHtmlTemplate : '' !!}
@stop
