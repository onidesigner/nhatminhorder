<?php
//die(phpinfo());

?>

<!DOCTYPE html>
<html>
<head>
    <title>@yield('page_title') - NhatMinh247</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('partials/__facebook_pixel')

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

    <aside class="app-sidebar" id="sidebar">


        <div class="sidebar-header">
            <a class="sidebar-brand" href="{{ url('')  }}"><span class="highlight">NhậtMinh</span> 247</a>
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
                                <img class="profile-img" src="{{ asset('images/home/_logo.png')  }}">
                            </button>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="navbar-title">
                            Tỉ giá: {{ number_format(App\Exchange::getExchange(), 0, ",", ".")  }} <sup>đ</sup>&nbsp;&nbsp;&nbsp;Số dư: <span class="text-danger"><?php echo App\Util::formatNumber(Auth::user()->account_balance) ?> <sup>đ</sup></span>

                        </li>
                        {{--<li class="navbar-search hidden-sm">--}}
                            {{--<input id="search" type="text" placeholder="Search.." autocomplete="off">--}}
                            {{--<button class="btn-search"><i class="fa fa-search"></i></button>--}}
                        {{--</li>--}}

                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="arrow-none dropdown notification">
                            <a href="{{ url('gio-hang') }}" class="dropdown-toggle" data-toggle="dropdown1111">
                                <div class="icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i></div>
                                <div class="title">New Orders</div>
                                <div class="count width-auto">
                                    {{ App\Cart::getCartTotalQuantityItem(Auth::user()->id)  }}

                                </div>
                            </a>
                        </li>

                        <li class="dropdown notification danger">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                                <div class="title">System Notifications</div>
                                <div class="count width-auto _count_notification"></div>
                            </a>
                            <div class="dropdown-menu" style="max-height: 500px; overflow-y: auto;">
                                <ul class="_display_notify">
                                    <li class="dropdown-header _system_notify" style="height: 40px">
                                        <span class="pull-left" >Thông báo</span>
                                        <span class="pull-right _mark_read_all " style="cursor: pointer!important;color: #365899;">Đánh dấu tất cả đã đọc</span>
                                    </li>

                                    {{--<li class="dropdown-footer">--}}
                                        {{--<a href="{{ url('/tat-ca-thong-bao') }}" target="_blank">Xem tất cả <i class="fa fa-angle-right" aria-hidden="true"></i></a>--}}
                                    {{--</li>--}}
                                </ul>
                            </div>
                        </li>

                        <li class="dropdown profile">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <img class="profile-img" src="{{ asset('images/home/_logo.png')  }}">
                                <div class="title">Profile</div>
                            </a>
                            <div class="dropdown-menu">
                                <div class="profile-info">
                                    <h4 class="username">
                                        <?php
                                            $current_user = App\User::find(Auth::user()->id);
                                        ?>

                                        <span style="@if(Auth::user()->section == App\User::SECTION_CRANE)color: orangered;@endif">{{ Auth::user()->name }}</span>
                                        ({{ $current_user->code }})
                                    </h4>
                                </div>
                                <ul class="action">
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
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        {{--@include('layouts/help-actions')--}}

        <div class="row">
            <div class="col-xs-12" id="_content">
                @yield('content')
            </div>
        </div>

        @include('layouts/footer')

    </div>

</div>

@yield('widget')

@section('css_bottom')
    <link rel="stylesheet" href=" {{asset('css/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href=" {{asset('css/font-awesome.min.css') }}">




@show

@section('js_bottom')
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/autoNumeric.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/nprogress.js')  }}"></script>
<script type="text/javascript" src="{{ asset('js/underscore-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.scrollbar.min.js') }}"></script>

    <script>
        $( document ).ready(function() {
            var xhr;
           function content_notification(view){
               $.ajax({
                   url: "{{ url('/load-content-notify') }}",
                   type: 'GET',
                   data: {},
                   dataType: 'json',
                   success: function (response) {

                       if (response.type == 'success') {
                           // count notifycation
                           if(response.count_notify != 0){
                               $("._count_notification").html(response.count_notify);
                           }else{
                               $("._count_notification").hide();
                           }

                           if(view == true){
                               $("._system_notify").after(response.notification);
                           }

                       }
                   }
               });
           }
            content_notification(true);

            /*setInterval(function(){ content_notification(false); }, 30000);*/

            /**
             * đổi trạng thái đơn sang đã đọc
             */


            $(document).on("click","._change_status",function() {
                var follower_id = $(this).data('follower-id');
                $.ajax({
                    url: "{{ url('/change-status-follower') }}",
                    type: 'GET',
                    data: {
                        follower_id : follower_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.type == 'success') {
                                // do nothing
                        }
                    }
                });
            });


            var page = 1;

            $('.dropdown-menu').scroll(function() {
                if($('.dropdown-menu').scrollTop() + $('.dropdown-menu').height() >= $('.dropdown-menu').height()) {

                    page++;
                    //console.info(page);
                  ///  loadMoreData(page);
                }
            });

            function loadMoreData(page){
                if(xhr && xhr.readyState != 4){
                    xhr.abort();
                }
              xhr =   $.ajax(
                        {
                            url: "{{ url('/load-content-notify') }}",
                            type: "get",
                            data :{
                                currentPage : page,
                                pageSize : 10
                            },
                            beforeSend: function()
                            {
                                $('.ajax-load').show();
                            }
                        })
                        .done(function(data)
                        {


                            if(data.html == " "){
                             //   $('.ajax-load').html("No more records found");
                                return;
                            }
                            $('.ajax-load').hide();
                            $("._display_notify").append(data.notification);


                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError)
                        {
                            console.info('server not responding...');
                        });
            }

        });

    </script>
@show

</body>
</html>