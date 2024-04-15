@extends('voyager::auth.master')

@section('content')
    <div class="login-container">

        <p>{{ __('voyager::login.signin_below') }}</p>

        <form action="{{ route('voyager.login') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group form-group-default" id="emailGroup">
                <label>{{ __('voyager::generic.email') }}</label>
                <div class="controls">
                    <input type="text" name="email" id="email" value="{{ env('DEMO_ADMIN_EMAIL') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group form-group-default" id="passwordGroup">
                <label>{{ __('voyager::generic.password') }}</label>
                <div class="controls">
                    <input type="password" name="password" value="{{ env('DEMO_ADMIN_PASSWORD') }}" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group" id="rememberMeGroup">
                <div class="controls">
                    <input type="checkbox" name="remember" id="remember" value="1"><label for="remember" class="remember-me-text">{{ __('voyager::generic.remember_me') }}</label>
                </div>
            </div>
            <!-- Login as Admin Button -->
            <button type="submit" class="btn btn-block login-button">
                <span class="signin">{{ __('generic.login_as_admin') }}</span>
            </button>

        <!-- Login with Google Button -->
        <a href="{{ route('login.google') }}" class="btn btn-block btn-google">
            <i class="fab fa-google"></i> {{ __('generic.login_with_google') }}
        </a>
        <!-- End Login with Google Button -->
        </form>

        <div style="clear:both"></div>
        <p><a href="javascript:void(0)" onclick="fillAdminCredentials()" id="admin-login" data-login="{{ __('generic.login_as_admin') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_admin') }} </p>
        <p><a href="javascript:void(0)" onclick="fillDeveloperCredentials()" id="developer-login" data-login="{{ __('generic.login_as_developer') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_developer') }} </p>

        @if(!$errors->isEmpty())
            <div class="alert alert-red">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div> <!-- .login-container -->
@endsection

@section('post_js')
    <script>
        function fillAdminCredentials(){
            const email = document.querySelector('[name="email"]');
            const password = document.querySelector('[name="password"]');
            email.value = '{{ env('DEMO_ADMIN_EMAIL') }}';
            password.value = '{{ env('DEMO_ADMIN_PASSWORD') }}';
            const e = document.getElementById('admin-login');
            const btn = document.querySelector('button[type="submit"]');
            btn.textContent = e.dataset.login;
            document.forms[0].submit();
        }

        function fillDeveloperCredentials() {
            const email = document.querySelector('[name="email"]');
            const password = document.querySelector('[name="password"]');
            email.value = '{{ env('DEMO_DEVELOPER_EMAIL') }}';
            password.value = '{{ env('DEMO_DEVELOPER_PASSWORD') }}';
            const e = document.getElementById('developer-login');
            const btn = document.querySelector('button[type="submit"]');
            btn.textContent = e.dataset.login;
            document.forms[0].submit();
        }
    </script>
@endsection
