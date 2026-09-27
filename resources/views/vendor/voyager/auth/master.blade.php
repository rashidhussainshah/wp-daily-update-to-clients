<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>@yield('title', 'Admin - '.Voyager::setting("admin.title"))</title>
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    @if (__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif
    @php
        // Extract a YouTube video ID from whatever URL shape is stored
        // (watch?v=, youtu.be/, embed/ - all supported).
        $loginVideoUrl = Voyager::setting('login.video_url');
        $loginVideoId = null;
        if ($loginVideoUrl && preg_match('/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]{11})/', $loginVideoUrl, $m)) {
            $loginVideoId = $m[1];
        }
    @endphp
    <style>
        body {
            background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');
            background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
        }
        body.login .login-sidebar {
            border-top:5px solid {{ config('voyager.primary_color','#22A7F0') }};
        }
        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top:0px !important;
                border-left:5px solid {{ config('voyager.primary_color','#22A7F0') }};
            }
        }
        body.login .form-group-default.focused{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .login-button, .bar:before, .bar:after{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .remember-me-text{
            padding:0 5px;
        }

        /* Login background video + motivational quote overlay */
        .login-video-panel {
            position: relative;
            overflow: hidden;
            min-height: 100vh; /* fill the whole left side, not just its content height */
        }
        .login-video-panel .login-video-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            min-width: 177.77vh; /* 16:9 cover */
            min-height: 100%;
            transform: translate(-50%, -50%);
            border: 0;
            pointer-events: none;
        }
        .login-video-panel .login-video-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15,23,42,0.15) 0%, rgba(15,23,42,0.65) 100%);
        }
        .login-video-panel .logo-title-container {
            position: relative;
            z-index: 2;
        }
        p.login-quote {
            margin: 6px 0 0;
            color: #fff;
            font-size: 15px;
            font-style: italic;
            line-height: 1.6;
            text-shadow: 0 1px 3px rgba(0,0,0,0.4);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    @yield('pre_css')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body class="login">
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg animated"></div>
        <div class="hidden-xs col-sm-7 col-md-8 {{ $loginVideoId ? 'login-video-panel' : '' }}">
            @if($loginVideoId)
                <iframe class="login-video-bg" src="https://www.youtube.com/embed/{{ $loginVideoId }}?autoplay=1&mute=1&loop=1&playlist={{ $loginVideoId }}&controls=0&showinfo=0&rel=0&modestbranding=1" allow="autoplay; encrypted-media" frameborder="0"></iframe>
                <div class="login-video-scrim"></div>
            @endif
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="copy animated fadeIn">
                            <h1>{{ Voyager::setting('admin.title', 'Voyager') }}</h1>
                            @if($loginVideoId)
                                <p class="login-quote">&ldquo;{{ \App\Helpers\WebpenterQuotes::random() }}&rdquo;</p>
                            @else
                                <p>{{ Voyager::setting('admin.description', __('voyager::login.welcome')) }}</p>
                            @endif
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar">

           @yield('content')

        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
@yield('post_js')
</body>
</html>
