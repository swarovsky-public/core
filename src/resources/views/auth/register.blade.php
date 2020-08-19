@extends(config('swarovsky-core.layout.app'))

@section('content')
    <div class="uk-section uk-flex uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div style="border-radius: 1px"
                             class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center">{{ __('Register') }}</h3>

                            {!! Form::open()->route('register') !!}
                            {!! Form::text('name', 'Username')->attrs(['required' => true])->icon('user') !!}
                            {!! Form::text('email', 'Email')->attrs(['required' => true])->type('email')->icon('mail') !!}

                            {!! Form::text('password', 'Your password')->attrs(['required' => true])->type('password')->icon('lock') !!}
                            {!! Form::text('password_confirmation', 'Confirm password')->attrs(['required' => true])->type('password')->icon('lock') !!}

                            @if(env('GOOGLE_RECAPTCHA_KEY')  && env('APP_ENV') !== 'local')
                                <button
                                    type="button"
                                    data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"
                                    data-callback='onSubmit'
                                    data-action='submit'
                                    class="uk-button uk-button-default uk-button-large uk-width-1-1 g-recaptcha"
                                >
                                    {{ __('Register') }}
                                </button>
                            @else
                                {!! Form::submit('Register')->icon('check', true)->color('primary')->attrs(['class' => 'uk-margin-top uk-width-1-1']) !!}
                            @endif
                            {!! Form::close() !!}

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
    <script>
        function onSubmit(token) {
            const form = document.getElementById('register-form');

            const XHR = new XMLHttpRequest();
            let urlEncodedData = "",
                urlEncodedDataPairs = [];


            let name = document.getElementById('name').value;
            urlEncodedDataPairs.push(encodeURIComponent('name') + '=' + encodeURIComponent(name));

            let email = document.getElementById('email').value;
            urlEncodedDataPairs.push(encodeURIComponent('email') + '=' + encodeURIComponent(email));

            let password = document.getElementById('password').value;
            urlEncodedDataPairs.push(encodeURIComponent('password') + '=' + encodeURIComponent(password));

            let passwordConfirm = document.getElementById('password-confirm').value;
            urlEncodedDataPairs.push(encodeURIComponent('password_confirmation') + '=' + encodeURIComponent(passwordConfirm));

            urlEncodedDataPairs.push(encodeURIComponent('g-recaptcha-response') + '=' + encodeURIComponent(token));
            urlEncodedDataPairs.push(encodeURIComponent('_token') + '=' + encodeURIComponent('{{ csrf_token()}}'));

            urlEncodedData = urlEncodedDataPairs.join('&').replace(/%20/g, '+');

            XHR.addEventListener('load', function (event) {
                if (event.target.status === 201) {
                    window.location.replace("{{route('home')}}");
                }
            });
            XHR.addEventListener('error', function (event) {

            });
            XHR.open(form.getAttribute("method"), form.getAttribute("action"));
            XHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            XHR.setRequestHeader('Accept', 'application/json');
            XHR.send(urlEncodedData);
        }
    </script>
@endpush
