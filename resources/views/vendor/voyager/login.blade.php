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

            <button type="submit" class="btn btn-block login-button">
                <span class="signin">{{ __('generic.login_as_admin') }}</span>
            </button>
        </form>

        <div style="clear:both"></div>
        <p><a href="javascript:void(0)" onclick="fillAdminCredentials()" id="admin-login" data-login="{{ __('generic.login_as_admin') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_admin') }} </p>
        <p><a href="javascript:void(0)" onclick="fillDeveloperCredentials()" id="developer-login" data-login="{{ __('generic.login_as_developer') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_developer') }} </p>
        <p><a href="javascript:void(0)" onclick="fillClientCredentials()" id="client-login" data-login="{{ __('generic.login_as_client') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_client') }} </p>
        <p><a href="javascript:void(0)" onclick="fillStudentManagerCredentials()" id="student-manager-login" data-login="{{ __('generic.login_as_student_manager') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_student_manager') }} </p>
        <p><a href="javascript:void(0)" onclick="fillHRCredentials()" id="hr-manager-login" data-login="{{ __('generic.login_as_hr_manager') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_hr_manager') }} </p>
        <p><a href="javascript:void(0)" onclick="fillBdCredentials()" id="bd-manager-login" data-login="{{ __('generic.login_as_bd') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_bd') }} </p>
        <p><a href="javascript:void(0)" onclick="fillAccountantCredentials()" id="accountant-manager-login" data-login="{{ __('generic.login_as_accountant') }}">{{ __('generic.click_to') }}</a> {{ __('generic.login_as_accountant') }} </p>

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
        var btn = document.querySelector('button[type="submit"]');
        var form = document.forms[0];
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signingin').className = 'signingin';
                btn.querySelector('.signin').className = 'signin hidden';
            } else {
                ev.preventDefault();
            }
        });
        email.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        email.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        email.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });

        password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });

        function fillAdminCredentials(){
            email.value = '{{ env('DEMO_ADMIN_EMAIL') }}';
            password.value = '{{ env('DEMO_ADMIN_PASSWORD') }}';
            const e = document.getElementById('admin-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }

        function fillDeveloperCredentials() {
            email.value = '{{ env('DEMO_DEVELOPER_EMAIL') }}';
            password.value = '{{ env('DEMO_DEVELOPER_PASSWORD') }}';
            const e = document.getElementById('developer-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }
        function fillClientCredentials() {
            email.value = '{{ env('DEMO_CLIENT_EMAIL') }}';
            password.value = '{{ env('DEMO_CLIENT_PASSWORD') }}';
            const e = document.getElementById('client-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }
        function fillStudentManagerCredentials() {
            email.value = '{{ env('DEMO_STUDENT_MANAGER_EMAIL') }}';
            password.value = '{{ env('DEMO_STUDENT_MANAGER_PASSWORD') }}';
            const e = document.getElementById('student-manager-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }
        function fillHRCredentials() {
            email.value = '{{ env('DEMO_HR_EMAIL') }}';
            password.value = '{{ env('DEMO_HR_PASSWORD') }}';
            const e = document.getElementById('hr-manager-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }
        function fillBdCredentials() {
            email.value = '{{ env('DEMO_BD_EMAIL') }}';
            password.value = '{{ env('DEMO_BD_PASSWORD') }}';
            const e = document.getElementById('bd-manager-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }
        function fillAccountantCredentials() {
            email.value = '{{ env('DEMO_ACCOUNTANT_EMAIL') }}';
            password.value = '{{ env('DEMO_ACCOUNTANT_PASSWORD') }}';
            const e = document.getElementById('accountant-manager-login');
            btn.textContent = e.dataset.login;
            form.submit();
        }

    </script>
@endsection
