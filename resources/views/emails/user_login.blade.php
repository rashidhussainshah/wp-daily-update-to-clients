@extends('emails.daily_emails_layout')

@section('email_title')
    {{ __('email.email_title') }}
@stop

@section('logo')
    <img src="{{ Voyager::image(setting('user-confirmation-email.user_registration_confirmation_email_logo')) }}" alt="Logo" width="150">
@stop

@section('content')
    <h2>{{ __('email.welcome_message') }}</h2>
    <p>{{ __('email.dear_user', ['name' => $name]) }}</p>
    <p>{{ __('email.account_created') }}</p>
    <p><strong>{{ __('email.email_label') }}</strong> {{ $email }}</p>
    <p><strong>{{ __('email.password_label') }}</strong> {{$password}}</p>
    <p>{{ __('email.login_instruction') }} <a href="{{ url('admin/login')}}">{{ __('email.here') }}</a>.</p>
    <p>{{ __('email.questions_message') }}</p>
    <p>{{ __('email.thank_you_message') }}</p>
@stop
