@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Verify Your Email Address') }}</h3>
                            <hr class="uk-divider-icon">
                    <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    <p>{{ __('If you did not receive the email') }}</p>
                    {!! Form::open()->route('verification.resend') !!}
                    {!! Form::submit('Send')->icon('mail', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                    {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
