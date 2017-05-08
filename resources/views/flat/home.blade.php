@extends('flat/layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('page-header')
    @parent
    <div class="page-header">
        <div class="pull-left">
            <h1>{{$page_title}}</h1>
        </div>

    </div>
@endsection

@section('breadcrumbs')
    @parent
    @include('flat/partials/breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Bảng chung', 'link' => null],
                        ]
                    ]
                )
@endsection

@section('content')

    <div class="row-fluid">

            @if($current_user->section == App\User::SECTION_CRANE)


                    <div class="span6">

                        <ul class="tiles tiles-center1 nomargin">

                            <li class="satgreen">
                                <span class="label label-inverse">{{$total_order_deposit_today}}</span>
                                <a href="#"><span><i class="icon-shopping-cart"></i></span><span class="name">Đơn đặt cọc</span></a>
                            </li>
                            <li class="orange">
                                <span class="label label-inverse">{{$total_customer_register_today}}</span>
                                <a href="#"><span><i class="icon-comments"></i></span><span class="name">Đăng ký mới</span></a>
                            </li>
                        </ul>
                    </div>

                    @if($permission['can_view_statistic_money_quick'])
                    <div class="span6">
                        <div class="card card-mini">
                            <h4>
                                Thống kê trong ngày
                                &nbsp;&nbsp;&nbsp;&nbsp;

                                @if($permission['can_view_statistic_money_detail'])
                                    <a href="{{ url('statistic/users')  }}" style="color: #5e6263;">
                                        <small>Xem chi tiết >></small>
                                    </a>
                                @endif

                            </h4>
                            <div class="card-body no-padding table-responsive">
                                <table class="table card-table">

                                    <tbody>
                                    @foreach($statistic as $s)
                                    <tr>
                                        <td>{{$s['name']}}</td>
                                        <td class="text-right">
                                            <span class="text-danger">{{$s['value']}}đ</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif


            @endif



    </div>

    <div class="row-fluid">
        <div class="span12">

            <h4>Hướng dẫn dành cho khách hàng mới</h4>
            <a class="" href="{{ url('ho-tro', 4)  }}">Hướng dẫn cài đặt công cụ đặt hàng & đặt cọc đơn hàng</a><br>
            <a class="" href="{{ url('ho-tro', 5)  }}">Hướng dẫn tìm nguồn hàng trên website taobao.com, tmall.com, 1688.com</a><br>
            <a class="" href="{{ url('ho-tro', 1)  }}">Hướng dẫn nạp tiền vào tài khoản</a><br>
            <a class="" href="{{ url('ho-tro', 3)  }}">Xem biểu phí</a><br>

            <br>

            <a href="{{ url('')  }}"><< Về trang chủ</a>
        </div>
    </div>

@endsection
