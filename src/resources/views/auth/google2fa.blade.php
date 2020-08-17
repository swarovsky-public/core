@extends('layouts.admin')

@section('content')
<div class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1">
    <div class="uk-vertical-align-middle uk-padding-small">

        <div class="uk-card uk-card-default" style="width: 400px;">
            <div class="uk-card-header">
                <h3 class="uk-card-title">{{ __('Two Factor Authentication') }}</h3>
            </div>
            <div class="uk-card-body">
                <strong>Enter the pin from Google Authenticator Enable 2FA</strong><br/><br/>

                {!! Form::open()->route('user.2faVerify')->method('post') !!}
                {!! Form::text('one_time_password', 'Authenticator code')->attrs(['required' => true]) !!}
                {!! Form::submit('Authenticate')->color('primary') !!}
                {!! Form::close() !!}

            </div>
            <div class="uk-card-footer">
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
