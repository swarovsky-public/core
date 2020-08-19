@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Reset password') }}</h3>

                            {!! Form::open()->route('password.update') !!}
                            {!! Form::hidden('token')->attrs(['required' => true])->value($token) !!}
                            {!! Form::text('email', 'Email')->attrs(['required' => true])
                                    ->type('email')
                                    ->readonly()
                                    ->icon('mail')
                                    ->value( $email ?? old('email'))
                                     !!}
                            {!! Form::text('password', 'New password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                            {!! Form::text('password_confirmation', 'Confirm password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                            {!! Form::submit('Reset password')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
