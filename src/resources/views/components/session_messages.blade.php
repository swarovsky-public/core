@push('styles')
    <link rel="stylesheet" href="{{url('css/notify.min.css')}}"/>
@endpush

<script src="{{ asset('js/notify.min.js') }}"></script>
@if ($errors->any())
    @foreach (['warning'=>'alert-triangle','danger'=>'alert-circle','info'=>'help-circle','success'=>'check'] as $errorType=>$errorIcon)
        @foreach ($errors->get($errorType) as $key=>$error)
            <script>
                let notyf = new Notyf();
                setTimeout(function() {
                    notyf.confirm("{{$error}}");
                }, 500);
            </script>
        @endforeach
    @endforeach
@endif
