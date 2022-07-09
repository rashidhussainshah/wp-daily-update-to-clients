@php
    $siteUrl = (isset($siteUrl) && !is_null($siteUrl)) ? $siteUrl : config('app.live_site_url');
    $siteUrl = rtrim($siteUrl, "/");
@endphp

@if (!(isset($preview) && $preview))
    <!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <link href="{{ config('app.aws_s3_assets_url') . 'avenir-next/stylesheet.css' }}" rel="stylesheet">
    <title>
        @yield('title')
    </title>

    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <style>
        table {border-collapse: collapse;}
        td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", Arial, sans-serif; mso-line-height-rule: exactly;}
    </style>
    <![endif]-->
{{--    @include('emails.partials.styles')--}}
    <style>
        .hover-bg-brand-600:hover {
            background-color: #249573 !important;
        }

        .hover-text-brand-700:hover {
            color: #003ca5 !important;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        wbr{
            display: none  !important;
        }
        @media screen {
            img {
                max-width: 100%;
            }

            .all-font-sans {
                font-family: -apple-system, "Segoe UI", sans-serif !important;
            }
        }

        @media (max-width: 640px) {
            u~div .wrapper {
                min-width: 100vw;
            }

            .sm-block {
                display: block !important;
            }

            .sm-h-16 {
                height: 16px !important;
            }

            .sm-mt-16 {
                margin-top: 16px !important;
            }

            .sm-py-16 {
                padding-top: 16px !important;
                padding-bottom: 16px !important;
            }

            .sm-px-16 {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .sm-py-24 {
                padding-top: 24px !important;
                padding-bottom: 24px !important;
            }

            .sm-text-14 {
                font-size: 14px !important;
            }

            .sm-w-full {
                width: 100% !important;
            }
        }
    </style>
</head>

@if ((isset($isPaymentEmail) && $isPaymentEmail == 'true'))
    <body lang="en" style="word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #ffffff; font-family:  'Rubik', sans-serif; font-weight: 400 !important; margin: 0; padding: 20px 0 20px 0 ; width: 100%; height:100%;  font-size: 1rem !important;" class="font-size-16">
    @else
        <body lang="en" style="word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #ffffff; font-family:  'Rubik', sans-serif; font-weight: 400 !important; margin: 0; padding: 20px 0 20px 0 ; width: 100%; height:100%;" class="font-size-16">
        @endif

        @endif

        <table class="wrapper all-font-sans" style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style bgcolor="#ffffff">
                    <table class="sm-w-full" style="width: 520px;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="sm-px-16 sm-py-24" style="padding-top: 20px; padding-bottom: 15px; text-align: left;" bgcolor="#ffffff" align="left">
                                <a href="{{ $siteUrl }}" style="color: #0047c3 !important; text-decoration: none;">
                                    <div style="margin-bottom: 20px; text-align: center; padding-top: 20px; padding-bottom: 19px; background: #3BBD96; line-height: 1;">
                                        <img src="http://dwbiy1oqt2fl0.cloudfront.net/images/logo-x3.png" class="navbar-brand-img mx-auto" alt="" style="max-width: 136px; width: 136px; border: 0; display: block; outline: none; text-decoration: none; height: auto; font-size: 13px;">
                                    </div>
                                </a>

                                <div id="email_heading" style="text-align: center; font-family: 'Rubik', sans-serif; font-size: 18px; line-height: 22px; color: #12263F !important; font-weight:bold;">

                                    @yield('heading')

                                </div>
                                @if (!(isset($isNewsLetter) && $isNewsLetter == 'true'))
                                    <div style="border: 1px dashed #B1C2D9 !important; width: 60px !important; margin: 15px auto 0px !important;"></div>
                                @endif
                                <div id="email_body">

                                    @yield('body')

                                    @yield('password-reset-text')

                                </div>


                                <div id="mid_thanks">

                                    @yield('mid_thanks')

                                </div>

                                <div id="user_info">

                                    @yield('user_info')

                                </div>

                                <div id="email_additional">

                                    @yield('additional')

                                </div>

                                <div style="text-align: left; width: 520px !important; margin: 0 auto !important;">
                                    <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                        <tr>
                                            <td style="text-align: center !important;">
                                                <p style="font-size: 14px !important; margin: 0px; color: #12263F !important; letter-spacing: 0.01em; line-height: 17px; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; text-align:center; text-rendering: geometricPrecision !important;">Thank You!</p>
                                            </td>
                                        </tr>
                                        <tr><td height="20" style="height: 20px;"></td></tr>
                                        <tr>
                                            <td style="border: 1px solid #e1e1ea !important;">
                                                <!-- <td style="padding-top: 25px; padding-bottom: 25px;"> -->
                                                <!-- <div style="background-color: #e1e1ea !important; height: 1px; line-height: 1px;">&nbsp;</div> -->
                                            </td>
                                        </tr>
                                        <tr><td height="20" style="height: 20px;"></td></tr>
                                    </table>

                                    <p style="color: #6E84A3 !important; font-size: 13px; line-height: 19px; max-width: 432px; margin: 0 auto 7px; text-align: center; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; margin-bottom: 0; text-rendering: geometricPrecision !important;">
                                        @if(isset($no_reply))
                                            DO NOT REPLY. This email is not monitored.<br>
                                        @endif
                                        For further assistance,  check out our <a href="{{ (isset($faqsUrl) && !is_null($faqsUrl)) ? $faqsUrl : config('app.live_site_url') . '/faqs' }}" style="color: #2C7BE5 !important; display: inline-block; text-decoration: none; font-size: 13px; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; text-rendering: geometricPrecision !important;">FAQs</a> or
                                        <br>
                                        email us at <a href="mailto:{{config('app.support_email')}}" style="color: #2C7BE5 !important; display: inline-block; text-decoration: none; font-size: 13px; line-height: 148%; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; text-rendering: geometricPrecision !important;">{{config('app.support_email')}}</a>
                                    </p>
                                    <p style="line-height: 15px; margin-top: 0; margin-bottom: 25px; color: #95AAC9 !important; font-size: 11px; text-align:center; padding-top: 0px;">
                                        <a href="{{ $siteUrl }}" style="color: #3BBD96 !important; display: inline-block; text-decoration: none; font-size: 13px; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important; text-rendering: geometricPrecision !important;">www.webpenter.com</a>
                                    </p>

                                    <!-- <p style="line-height: 16px; margin-top: 0; margin-bottom: 16px; color: #95AAC9 !important; font-size: 11px;">
                                    This email was sent to you as a registered member of <a href="{{ $siteUrl }}" class="hover-text-brand-700" style="color: #0047c3 !important; display: inline-block; text-decoration: none;">webpenter.com</a>.
                                    <span class="sm-block sm-mt-16">Use of the service and website is subject to our <a href="{{ $siteUrl . '/terms' }}" class="hover-text-brand-700" style="color: #0047c3 !important; text-decoration: none; display: inline-block;">Terms of use</a> and <a href="{{ $siteUrl . '/privacy' }}" class="hover-text-brand-700" style="color: #0047c3 !important; text-decoration: none; display: inline-block; text-decoration: none;">Privacy policy</a>.</span>
                                </p> -->

                                    @if (isset($allowUnsubscribe) && $allowUnsubscribe)
                                        <p style="color: #6E84A3 !important; font-size: 10px; line-height: 148%; max-width: 422px; margin: 0 auto 5px; text-align: center; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important;">
                                            <a href="{{ $siteUrl }}/unsubscribe?email=%recipient.emailid_hash%" target="_blank" style="color: #6E84A3 !important; display: inline-block; text-decoration: none; font-size: 10px; line-height: 148%; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important;">Click here to unsubscribe</a>
                                        </p>
                                    @endif

                                    <p style="line-height: 16px; margin: 0; color: #6E84A3 !important; font-size: 12px; line-height: 148%; text-align:center; font-family: 'AvenirNext Regular', roboto, sans-serif !important; font-weight: 400 !important;">&copy; {{ date('Y') }}. WebPenter</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if (!(isset($preview) && $preview))
        </body>

</html>
@endif
