<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
@if ($errors->any())
    <script>
        let notyf = new Notyf({
            duration: 4000,
            dismissible: true,
            types: [
                {
                    type: 'warning',
                    background: 'orange',
                    icon: {
                        className: 'material-icons',
                        tagName: 'i',
                        text: 'warning'
                    }
                },
            ]
        });
        @foreach ($errors->get('success') as $error)
            setTimeout(function () {
                notyf.success("{{$error}}");
            }, 500);
        @endforeach
        @foreach ($errors->get('warning') as $error)
            setTimeout(function () {
                notyf.warning("{{$error}}");
            }, 500);
        @endforeach
        @foreach ($errors->get('danger') as $error)
            setTimeout(function () {
                notyf.error("{{$error}}");
            }, 500);
        @endforeach
    </script>
@endif
