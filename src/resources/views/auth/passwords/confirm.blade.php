@extends('layouts.admin')

@section('content')

<div class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1">
    <div class="uk-vertical-align-middle uk-padding-small">

        <div class="uk-card uk-card-default" style="width: 400px;">
            <div class="uk-card-header">
                <h3 class="uk-card-title">{{ __('Confirm Password') }}</h3>
            </div>
            <div class="uk-card-body">
                {{ __('Please confirm your password before continuing.') }}

                {!!Form::open()->route('password.confirm')!!}
                {!! Form::text('password', 'Confirm your password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                {!! Form::submit('Login')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
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
