@extends('emails.daily_emails_layout')

@section('title')
Sign up successful
@stop

@section('heading')
    Sign Up Successful
@stop

@section('body')
<div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-top: 10px; max-width: 520px; margin: 0px auto;">
        <tr><td height="20" style="height: 20px;"></td></tr>
        <tr>
            <td>
                <p style="line-height: 21px !important; margin: 0px; margin-bottom: 20px !important; color: #000000 !important; font-size: 14px !important; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; font-style: normal; text-rendering: geometricPrecision !important;">Hey  {{ $user->name ?? '' }},</p>
                <p style="line-height: 21px !important; margin: 0px; color: #000000 !important; font-size: 14px !important; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; font-style: normal; text-rendering: geometricPrecision !important;">Your account created, use below detail to log in.</p>
                <p style="line-height: 21px !important; margin: 0px; color: #000000 !important; font-size: 14px !important; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; font-style: normal; text-rendering: geometricPrecision !important;">Email:  {{ $user->email }}</p>
                <p style="line-height: 21px !important; margin: 0px; color: #000000 !important; font-size: 14px !important; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; font-style: normal; text-rendering: geometricPrecision !important;">Password: {{$password}}</p>
            </td>
        </tr>
    </table>
    <table class="sm-w-full" cellpadding="0" cellspacing="0" role="presentation">
        <tr><td height="15" style="height:15px;"></td></tr>
        <tr>
            <td align="center" class="hover-bg-brand-600" style="mso-padding-alt: 20px 32px; border-radius: 4px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06); color: #ffffff!important;" bgcolor="#3BBD96">
                <a href="{{ $loginLink ?? '' }}" class="sm-text-14 sm-py-16" style="display: inline-block; padding-left: 10px; padding-right: 10px; text-decoration: none; font-family: 'AvenirNext Demi', roboto, sans-serif !important; font-weight: 700 !important; font-style: normal; font-size: 14px !important; line-height: 41px; text-align: center; color: #ffffff !important; text-rendering: geometricPrecision !important;">Sign in to your account</a>
            </td>
        </tr>
    </table>
    <div class="sm-h-16" style="line-height: 16px;">&nbsp;</div>
    <div class="sm-h-16" style="line-height: 16px;">&nbsp;</div>
</div>
@stop
