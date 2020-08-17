@extends('layouts.app')

@section('content')


    <div class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1">
        <div class="uk-vertical-align-middle uk-padding-small">
            <div class="uk-card uk-card-default uk-card-body" style="width: 400px;">
                <div class="uk-card-media-top uk-text-center">
                    <img style="max-width: 225px;"
                         src="{{asset('images/logo-ws4.png')}}"
                         alt="LeadsBridge Logo">

                </div>
                <div class="uk-card-body">
                    <h3 class="uk-card-title">{{__('Login')}}</h3>
                    {!! Form::open()->route('login') !!}
                    {!! Form::text('email', 'Email')->attrs(['required' => true])->type('email')->icon('mail') !!}

                    {!! Form::text('password', 'Your password')->attrs(['required' => true])->type('password')->icon('lock') !!}

                    {!! Form::checkbox('remember', 'Remember me') !!}
                    {!! Form::submit('Login')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                    {!! Form::close() !!}

                </div>
            </div>


        </div>
    </div>



@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.5/dist/js/uikit-icons.min.js"></script>
@endpush
