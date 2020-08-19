@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Confirm Password') }}</h3>
                            <hr class="uk-divider-icon">
                            {!! Form::open()->route('password.confirm') !!}
                            {!! Form::text('password', 'Confirm your password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                            {!! Form::submit('Confirm')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
