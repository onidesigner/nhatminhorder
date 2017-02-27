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
                        <li class="navbar-title">Dashboard</li>
                        <li class="navbar-search hidden-sm">
                            <input id="search" type="text" placeholder="Search.." autocomplete="off">
                            <button class="btn-search"><i class="fa fa-search"></i></button>
                        </li>
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
                                    <h4 class="username">{{ Auth::user()->name }}</h4>
                                </div>
                                <ul class="action">
                                    <li>
                                        <a href="{{ url('sua-nhan-vien', Auth::user()->id)  }}">
                                            Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                            Logout
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

        $('.modal').on('shown.bs.modal', function() {
            $("._autofocus").focus();
        });

        /**
         * created_by: vanhs
         * created_time: 04:27 22/06/2015
         * @returns {{}}
        */
        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                var value = this.value;
                //if use autoNumeric
                var $this = $("[name='" + this.name + "']");
                if($this.hasClass("autoNumeric")) { value = $this.autoNumeric('get'); }

                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(value || '');
                } else {
                    o[this.name] = value || '';
                }
            });
            return o;
        };


        /**
         * created_by: vanhs
         * created_time: 04:27 22/06/2015
         *
         * @param data
         */
        $.fn.setFormData = function(data) {
            try{
                $.each(data, function (name, value) {
                    var $self = $("[name='" + name + "']");
                    if($self.length){
                        var tagName = $self.prop("tagName").toUpperCase();
                        var type = $self.prop("type").toUpperCase();
                        switch (tagName){
                            case "INPUT":
                                switch (type){
                                    case "TEXT":
                                    case "HIDDEN":
                                        $self.val(value);
                                        if($self.hasClass("autoNumeric")){
                                            $self.autoNumeric('set', value);
                                        }
                                        break;
                                    case "RADIO":
                                        $self.filter("[value='" + value + "']").prop("checked", true);
                                        break;
                                    case "CHECKBOX":
                                        if(typeof value == "string"){ value = [value]; }//convert value to array if value is string
                                        for(var i = 0; i < value.length; i++){
                                            console.info("vao day");
                                            $self.filter("[value='" + value[i] + "']").prop("checked", true);
                                        }
                                        break;
                                    case "DATE":
                                        $self.val(value);
                                        if($self.hasClass("autoNumeric")){
                                            $self.autoNumeric('set', value);
                                        }
                                        break;
                                    default:
                                        console.warn("Not support type: " + type);
                                        break;
                                }
                                break;
                            case "SELECT":
                                switch (type){
                                    case "SELECT-ONE":
                                        $self.find("option[value='" + value + "']").prop("selected", true);
                                        break;
                                    case "SELECT-MULTIPLE":
                                        if(typeof value == "string"){ value = [value]; }//convert value to array if value is string
                                        for(var j = 0; j < value.length; j++){
                                            $self.find("option[value='" + value[j] + "']").prop("selected", true);
                                        }
                                        break;
                                    default:
                                        console.warn("Not support type: " + type);
                                        break;
                                }
                                break;
                            case "TEXTAREA":
                                $self.val(value);
                                break;
                            default:
                                console.warn("Not support tagName: " + tagName);
                                break;
                        }
                    }
                });
            }catch (e){
                console.warn("Exception: " + e.message);
            }
        };

    });
</script>

@show

</body>
</html>