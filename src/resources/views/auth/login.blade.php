@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m" id="login-view">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Login') }}</h3>

                            {!! Form::open()->route('login') !!}
                            {!! Form::text('email', 'Email')->attrs(['required' => true])->type('email')->icon('mail') !!}

                            {!! Form::text('password', 'Your password')->attrs(['required' => true])->type('password')->icon('lock') !!}

                            {!! Form::checkbox('remember', 'Remember me') !!}
                            {!! Form::submit('Login')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                            {!! Form::close() !!}

                            <a href="{{url('password/reset')}}" title="Ask for a new one"
                               class="uk-text-muted uk-text-small">Forgot password?</a>
                            <br>
                            <a href="{{url('register')}}" title="Register a new one"
                               class="uk-text-muted uk-text-small">Don't have an account?</a>

                            <hr class="uk-divider-icon">

                            @include('swarovsky-core::auth.social')


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
