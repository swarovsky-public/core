@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m" id="register-view">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Register') }}</h3>

                            {!! Form::open()->route('register')->id('register_form') !!}
                            {!! Form::text('name', 'Username')->attrs(['required' => true])->icon('user') !!}
                            {!! Form::text('email', 'Email')->attrs(['required' => true])->type('email')->icon('mail') !!}

                            {!! Form::text('password', 'Your password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                            {!! Form::text('password_confirmation', 'Confirm password')->attrs(['required' => true])->type('password')->icon('lock') !!}

                            @if(env('GOOGLE_RECAPTCHA_KEY')  && env('APP_ENV') !== 'local')
                                <button
                                    type="button"
                                    data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"
                                    data-callback='registerOnSubmit'
                                    data-action='submit'
                                    class="uk-button uk-button-default uk-button-large uk-width-1-1 g-recaptcha"
                                >
                                    {{ __('Register') }}
                                </button>
                            @else
                                {!! Form::submit('Register')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                            @endif
                            {!! Form::close() !!}
                            <a href="{{url('login')}}" title="Sign in"
                               class="uk-text-muted uk-text-small">Already have an account?</a>
                            <hr class="uk-divider-icon">

                            @include('swarovsky-core::auth.social')


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.5/dist/js/uikit-icons.min.js"></script>
    <script type="text/javascript">
        function registerOnSubmit(token) {
            axios.post('{{route('register')}}', {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                'g-recaptcha-response': token,
                _token: '{{ csrf_token()}}',
            })
                .then(function (response) {
                    console.log('success', response);
                    if (response.status === 201) {
                        window.location.replace("{{route('home')}}");
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    </script>
@endpush
