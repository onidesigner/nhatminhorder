<!DOCTYPE html>
<html>
<head>
    <title>NhatMinh247 - @yield('page_title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('css_top')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flat-admin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/nprogress.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    @show

    @include('footer_var_view')

    @section('js_top')
    @show
</head>
<body>

@yield('content')

<input type="hidden" class="_autoNumericTemp" value="" />

@section('css_bottom')
@show

@section('js_bottom')
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
<script src="{{ asset('js/autoNumeric.min.js')  }}"></script>
<script src="{{ asset('js/nprogress.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/underscore-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>

@show

</body>
</html>