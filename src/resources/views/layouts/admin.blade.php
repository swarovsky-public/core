<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.5.3/dist/css/uikit.min.css"/>
    <link rel="stylesheet" href="{{url('css/notify.min.css')}}"/>
    <link rel="stylesheet" href="{{url('css/admin-style.css')}}"/>
    @stack('styles')
</head>

<body>

@component('core::admin.navbar')@endcomponent
@component('core::admin.sidebar')@endcomponent

<div class="content-padder content-background">
    @yield('content')
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.5.3/dist/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.transit/0.9.12/jquery.transit.min.js" integrity="sha256-rqEXy4JTnKZom8mLVQpvni3QHbynfjPmPxQVsPZgmJY=" crossorigin="anonymous"></script>
<script src="{{ asset('js/admin-script.js') }}"></script>
@include('core::components.session_messages')
@stack('scripts')
</body>

</html>
