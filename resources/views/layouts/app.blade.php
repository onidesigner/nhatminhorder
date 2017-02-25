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


<div class="app app-default">
    {{--<div class="row">--}}
        {{--<div class="col-xs-12">--}}
            {{--<div class="___loading" style="height: 100px; width:100%; position: fixed; top: 0; left:0; z-index: 9999999999; display: none'">--}}
                {{--<div class="loader-container text-center">--}}
                    {{--<div class="icon">--}}
                        {{--<div class="sk-wave">--}}
                            {{--<div class="sk-rect sk-rect1"></div>--}}
                            {{--<div class="sk-rect sk-rect2"></div>--}}
                            {{--<div class="sk-rect sk-rect3"></div>--}}
                            {{--<div class="sk-rect sk-rect4"></div>--}}
                            {{--<div class="sk-rect sk-rect5"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="title">Dang tai du lieu, cho xiu nhe!</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

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

        {{--@include('layouts/help-actions')--}}

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

<input type="hidden" class="_autoNumericTemp" value="" />

@section('css_bottom')
@show

@section('js_bottom')
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
<script src="{{ asset('js/autoNumeric.min.js')  }}"></script>
<script src="{{ asset('js/nprogress.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

<script>
    $(document).ready(function() {
        if ($('[data-toggle="tooltip"]').length) {
            $('[data-toggle="tooltip"]').tooltip();
        }

        if ($('[data-toggle="popover"]').length) {
            $('[data-toggle="popover"]').popover();
        }

        $('._autoNumericTemp').autoNumeric({aPad: false, mDec: 3, vMax: 9999999999999.99, aSep: '.', aDec: ','});
        if ($('._autoNumeric').length) {
            $('._autoNumeric').each(function (i) {
                var tagName = $(this).prop("tagName").toLowerCase();
                if (tagName == 'input') {
                    $(this).autoNumeric({aPad: false, mDec: 3, vMax: 9999999999999.99, aSep: '.', aDec: ','});
                } else {
                    var value = $(this).text().trim();
                    $(this).text(formatNumber(value));
                }
            })
        }

        function formatNumber(value) {
            $('._autoNumericTemp').autoNumeric('set', value);
            return $('._autoNumericTemp').val();
        }

        $(document).ajaxStop(function(){
            NProgress.done();
        });

        $.ajaxSetup({
            beforeSend:function(){
                NProgress.start();
            },
            complete:function(){

            }
        });

    });
</script>

@show

</body>
</html>