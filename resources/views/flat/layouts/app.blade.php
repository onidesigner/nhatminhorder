<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <title>@yield('page_title') - NhatMinh247</title>

    @include('partials/__facebook_pixel')


    @section('css_top')

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('flat/css/bootstrap.min.css') }}">
    <!-- Bootstrap responsive -->
    <link rel="stylesheet" href="{{ asset('flat/css/bootstrap-responsive.min.css') }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('flat/css/style.css') }}">
    <!-- Color CSS -->
    <link rel="stylesheet" href="{{ asset('flat/css/themes.css') }}">

    <!-- Nprogress CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('flat/css/plugins/nprogress/nprogress.css') }}">

    <link rel="stylesheet" href="{{ asset('flat/css/custom.css') }}">
    @show

    @include('footer_var_view')

    @section('js_top')
    <!-- jQuery -->
    <script src="{{ asset('flat/js/jquery.min.js')  }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('flat/js/bootstrap.min.js')  }}"></script>

    <!-- Nprogress JS -->
    <script type="text/javascript" src="{{ asset('flat/js/plugins/nprogress/nprogress.js')  }}"></script>

    <!-- Bootbox JS -->
    <script type="text/javascript" src="{{ asset('flat/js/plugins/bootbox/jquery.bootbox.js') }}"></script>

    @show

    <!-- Favicon -->
    <link rel="shortcut icon" href="flat/img/favicon.ico" />
    <!-- Apple devices Homescreen icon -->
    <link rel="apple-touch-icon-precomposed" href="flat/img/apple-touch-icon-precomposed.png" />

</head>

<body>

<div id="navigation">
    <div class="container-fluid">
        <a href="{{ url('')  }}" id="brand">NhatMinh247</a>
        {{--<a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>--}}

        @include('flat/partials/sidebar-menu', ['device' => 'web']);

        <div class="user">
            <ul class="icon-nav">
                <li class="dropdown">
                    <a href="{{ url('gio-hang') }}">
                        <i class="icon-shopping-cart"></i>
                        <span class="label label-lightred">
                            {{ App\Cart::getCartTotalQuantityItem(Auth::user()->id)  }}
                        </span>
                    </a>
                </li>

                <li class='dropdown'>
                    <a href="
                            <?php if(Auth::user()->section == App\User::SECTION_CUSTOMER){ ?>

                    {{ url('thong-bao') }}

                    <?php }else{ ?>

                    {{ url('notification') }}

                    <?php }?>
                            " class="dropdown-toggle
                            <?php if(Auth::user()->section == App\User::SECTION_CUSTOMER){

                        echo "_read_notification_customer";

                    }else{
                        echo "_read_notification_crane";
                    }?>


                            ">


                        <i class="icon-bell"></i><span class="label label-lightred">

                            <?php
                            if(Auth::user()->section == App\User::SECTION_CUSTOMER){
                                // đây là giao diện dành cho khách hàng
                                $user_id = \Illuminate\Support\Facades\Auth::user()->id;
                                $count_notification = \App\CustomerNotification::where([
                                    'section' => App\User::SECTION_CUSTOMER,
                                    'is_view' => App\CustomerNotification::CUSTOMER_NOTIFICATION_VIEW,
                                    'user_id' => $user_id
                                ])->count();
                                ;
                                echo $count_notification;
                            }else{
                                // giao dien dành cho quản trị
                                $user_id = \Illuminate\Support\Facades\Auth::user()->id;
                                $count_notification = \App\CustomerNotification::
                                where([
                                    'section' => App\User::SECTION_CRANE,
                                    'is_view' => App\CustomerNotification::CUSTOMER_NOTIFICATION_VIEW,
                                    'user_id' => $user_id
                                ])->count();
                                ;
                                echo $count_notification;
                            }
                            ?>

                        </span></a>



                    {{--<ul class="dropdown-menu pull-right message-ul">--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<img src="flat/img/demo/user-1.jpg" alt="">--}}
                                {{--<div class="details">--}}
                                    {{--<div class="name">Jane Doe</div>--}}
                                    {{--<div class="message">--}}
                                        {{--Lorem ipsum Commodo quis nisi ...--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<img src="flat/img/demo/user-2.jpg" alt="">--}}
                                {{--<div class="details">--}}
                                    {{--<div class="name">John Doedoe</div>--}}
                                    {{--<div class="message">--}}
                                        {{--Ut ad laboris est anim ut ...--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="count">--}}
                                    {{--<i class="icon-comment"></i>--}}
                                    {{--<span>3</span>--}}
                                {{--</div>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<img src="flat/img/demo/user-3.jpg" alt="">--}}
                                {{--<div class="details">--}}
                                    {{--<div class="name">Bob Doe</div>--}}
                                    {{--<div class="message">--}}
                                        {{--Excepteur Duis magna dolor!--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="components-messages.html" class='more-messages'>Go to Message center <i class="icon-arrow-right"></i></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </li>


                {{--<li class='dropdown language-select'>--}}
                    {{--<a href="#" class='dropdown-toggle' data-toggle="dropdown"><img src="flat/img/demo/flags/us.gif" alt=""><span>US</span></a>--}}
                    {{--<ul class="dropdown-menu pull-right">--}}
                        {{--<li>--}}
                            {{--<a href="#"><img src="flat/img/demo/flags/br.gif" alt=""><span>Brasil</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#"><img src="flat/img/demo/flags/de.gif" alt=""><span>Deutschland</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#"><img src="flat/img/demo/flags/es.gif" alt=""><span>España</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#"><img src="flat/img/demo/flags/fr.gif" alt=""><span>France</span></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}


            </ul>
            <div class="dropdown">
                <a href="#" class='dropdown-toggle' data-toggle="dropdown">
                    {{--{{ Auth::user()->name }}--}}

                    Tỉ giá: {{ number_format(App\Exchange::getExchange(), 0, ",", ".")  }}đ

                    |

                    <span><?php echo App\Util::formatNumber(Auth::user()->account_balance) ?>đ</span>

                    <img src="{{ asset('images/home/_logo.png')  }}" width="27px" alt="">
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{ url('san-pham-da-luu')  }}">Sản phẩm đã lưu</a>
                    </li>
                    <li>


                        @if(Auth::user()->section == App\User::SECTION_CRANE)
                            <a href="{{ url('user/detail', Auth::user()->id)  }}">
                                @else
                                    <a href="{{ url('nhan-vien', Auth::user()->id)  }}">
                                        @endif
                                        Thông tin cá nhân
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
        </div>
    </div>
</div>
<div class="container-fluid" id="content">

    <div id="main">
        <div class="container-fluid">
            @section('page-header')
            @show

            @section('breadcrumbs')
            @show

            <div id="_content">
                @yield('content')
            </div>
        </div>
    </div>
</div>

@yield('widget')

@section('css_bottom')
@show

@section('js_bottom')
    <script type="text/javascript" src="{{ asset('flat/js/common.js') }}"></script>

    <script>
        $( document ).ready(function() {
            $( "._read_notification_customer" ).click(function() {
                $.ajax({
                    url : '/view-notification',
                    data : {

                    },
                    type : 'GET'
                }).done(function (response) {
                    console.info(response);
                });
            });
            $( "._read_notification_crane" ).click(function() {
                $.ajax({
                    url : '/view-notification',
                    data : {

                    },
                    type : 'GET'
                }).done(function (response) {
                    console.info(response);
                });
            })
        });
    </script>
@show

</body>

</html>

