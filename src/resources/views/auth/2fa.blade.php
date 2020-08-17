@extends('layouts.admin')

@section('content')
<div class="uk-section-small">
    <div class="uk-container uk-container-large">

        <div class="uk-card uk-card-default">

            <div class="uk-card-header">
                <h3 class="uk-card-title">Two Factor Authentication</h3>
            </div>

            <div class="uk-card-body">
                <p>Two factor authentication (2FA) strengthens access security by requiring two methods (also
                    referred to as factors) to verify your identity. Two factor authentication protects against
                    phishing, social engineering and password brute force attacks and secures your logins from
                    attackers exploiting weak or stolen credentials.</p>
                <p>To Enable Two Factor Authentication on your Account, you need to do following steps</p>
                <ol>
                    <li>Click on Generate Secret Button , To Generate a Unique secret QR code for your
                        profile</li>
                    <li>Verify the OTP from Google Authenticator Mobile App</li>
                </ol>

                @if($data['user']->passwordSecurity === null)

                    {!! Form::open()->route('user.generate2faSecret')->method('post') !!}
                    {!! Form::submit('Generate Secret Key to Enable 2FA')->color('primary') !!}
                    {!! Form::close() !!}

                @elseif(!$data['user']->passwordSecurity->google2fa_enable)

                    <strong>1. Scan this barcode with your Google Authenticator App:</strong><br/>
                    <img src="{{$data['google2fa_url'] }}" alt="">
                    <br/><br/>
                    <strong>2.Enter the pin the code to Enable 2FA</strong>

                    {!! Form::open()->route('user.enable2fa')->method('post') !!}
                    {!! Form::text('verify-code', 'Authenticator code')->attrs(['required' => true]) !!}
                    {!! Form::submit('Enable 2FA')->color('primary') !!}
                    {!! Form::close() !!}

                @elseif($data['user']->passwordSecurity->google2fa_enable)

                    <div class="alert alert-success">
                        2FA is Currently <strong>Enabled</strong> for your account.
                    </div>
                    <p>If you are looking to disable Two Factor Authentication. Please confirm your password and
                        Click Disable 2FA Button.</p>

                    {!! Form::open()->route('user.disable2fa')->method('post') !!}
                    {!! Form::text('current-password', 'Current Password')->attrs(['required' => true])->type('password') !!}
                    {!! Form::submit('Disable 2FA')->color('secondary') !!}
                    {!! Form::close() !!}

                @endif

            </div>

        </div>

    </div>
</div>
@endsection
