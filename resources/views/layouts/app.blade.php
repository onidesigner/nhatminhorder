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

{{--@include('partials/__comment', [--}}
    {{--'object_id' => 100,--}}
    {{--'object_type' => App\Comment::TYPE_OBJECT_ORDER,--}}
    {{--'scope' => App\Comment::TYPE_EXTERNAL--}}
{{--])--}}

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

        <nav class="navbar navbar-default" id="navbar">
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
                        <li>
                            <button type="button" class="navbar-toggle">
                                <img class="profile-img" src="{{ asset('images/profile.png')  }}">
                            </button>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="navbar-title">
                            Tỉ giá: {{ number_format(App\Exchange::getExchange(), 0, ",", ".")  }} <sup>d</sup>&nbsp;&nbsp;&nbsp;Số dư: <span class="text-danger"><?php echo number_format(Auth::user()->account_balance, 2, ",", ".") ?> <sup>d</sup></span>

                        </li>
                        {{--<li class="navbar-search hidden-sm">--}}
                            {{--<input id="search" type="text" placeholder="Search.." autocomplete="off">--}}
                            {{--<button class="btn-search"><i class="fa fa-search"></i></button>--}}
                        {{--</li>--}}
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown notification">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <div class="icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i></div>
                                <div class="title">New Orders</div>
                                <div class="count">0</div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li class="dropdown-header">Ordering</li>
                                    <li class="dropdown-empty">No New Ordered</li>
                                    <li class="dropdown-footer">
                                        <a href="#">View All <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="dropdown notification danger">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                                <div class="title">System Notifications</div>
                                <div class="count">10</div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li class="dropdown-header">Notification</li>
                                    <li>
                                        <a href="#">
                                            <span class="badge badge-danger pull-right">8</span>
                                            <div class="message">
                                                <div class="content">
                                                    <div class="title">New Order</div>
                                                    <div class="description">$400 total</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="badge badge-danger pull-right">14</span>
                                            Inbox
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="badge badge-danger pull-right">5</span>
                                            Issues Report
                                        </a>
                                    </li>
                                    <li class="dropdown-footer">
                                        <a href="#">View All <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="dropdown profile">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <img class="profile-img" src="{{ asset('images/profile.png')  }}">
                                <div class="title">Profile</div>
                            </a>
                            <div class="dropdown-menu">
                                <div class="profile-info">
                                    <h4 class="username">
                                        @if(Auth::user()->section == App\User::SECTION_CRANE)
                                            [Quản trị viên]
                                        @else
                                            [Khách hàng]
                                        @endif

                                        {{ Auth::user()->name }}
                                    </h4>
                                </div>
                                <ul class="action">
                                    <li>
                                        <a href="{{ url('user/detail', Auth::user()->id)  }}">
                                            Cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                            Thoát
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        {{--@include('layouts/help-actions')--}}

        @yield('content')

        @include('layouts/footer')

    </div>

</div>

<input type="hidden" class="_autoNumericTemp" value="" />

@section('css_bottom')
@show

@section('js_bottom')
{{--<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
<script src="{{ asset('js/autoNumeric.min.js')  }}"></script>
<script src="{{ asset('js/nprogress.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/underscore-min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
@show

</body>
</html>