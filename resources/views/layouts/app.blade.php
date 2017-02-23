<!DOCTYPE html>
<html>
<head>
    <title>NhatMinh247 - @yield('page_title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('css_top')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flat-admin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    @show

    @section('js_top')
    @show
</head>
<body>

<div class="app app-default">

    <aside class="app-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a class="sidebar-brand" href="#"><span class="highlight">NhatMinh</span> 247</a>
            <button type="button" class="sidebar-toggle">
                <i class="fa fa-times"></i>
            </button>
        </div>

        @include('layouts/sidebar-menu')

    </aside>

    <script type="text/ng-template" id="sidebar-dropdown.tpl.html">
        <div class="dropdown-background">
            <div class="bg"></div>
        </div>
        <div class="dropdown-container">

        </div>
    </script>
    <div class="app-container">

        <nav class="navbar navbar-default hidden-lg hidden-md hidden-sm" id="navbar">
            <div class="container-fluid">
                <div class="navbar-collapse collapse in">
                    <ul class="nav navbar-nav navbar-mobile">
                        <li>
                            <button type="button" class="sidebar-toggle">
                                <i class="fa fa-bars"></i>
                            </button>
                        </li>
                        <li class="logo">
                            <a class="navbar-brand" href="#"><span class="highlight">NhatMinh</span> 247</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        @include('layouts/help-actions')

        <div class="row">
            <div class="col-xs-12 text-right">
                {{ Auth::user()->name }}
                &nbsp;/&nbsp;
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    Thoat
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>

        @yield('content')

        @include('layouts/footer')

    </div>

</div>

@section('css_bottom')
@show

@section('js_bottom')
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@show

</body>
</html>