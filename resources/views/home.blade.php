@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">

            @if($current_user->section == App\User::SECTION_CRANE)

                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <a class="card card-banner card-green-light">
                            <div class="card-body">
                                <i class="icon fa fa-shopping-basket fa-4x"></i>
                                <div class="content">
                                    <div class="title">Đơn đặt cọc trong ngày</div>
                                    <div class="value">{{$total_order_deposit_today}}</div>
                                </div>
                            </div>
                        </a>

                        <br>

                        <a class="card card-banner card-yellow-light">
                            <div class="card-body">
                                <i class="icon fa fa-user-plus fa-4x"></i>
                                <div class="content">
                                    <div class="title">Khách đăng ký trong ngày</div>
                                    <div class="value"><span class="sign"></span>{{$total_customer_register_today}}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="card card-mini">
                            <div class="card-header">
                                <div class="card-title text-uppercase">Thống kê</div>

                                <ul class="card-action">
                                    <li>
                                        <a href="{{ url('statistic/users')  }}" style="color: #5e6263;">
                                            Xem chi tiết >>
                                        </a>
                                    </li>
                                </ul>

                            </div>
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
                </div>
                <br>


            @endif

            <div class="card">
                @include('partials/__breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Bảng chung', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">
                    <h3 class="cart-title">Hướng dẫn dành cho khách hàng mới</h3>
                    <a class="" href="{{ url('ho-tro', 4)  }}">Hướng dẫn cài đặt công cụ đặt hàng & đặt cọc đơn hàng</a><br>
                    <a class="" href="{{ url('ho-tro', 5)  }}">Hướng dẫn tìm nguồn hàng trên website taobao.com, tmall.com, 1688.com</a><br>
                    <a class="" href="{{ url('ho-tro', 1)  }}">Hướng dẫn nạp tiền vào tài khoản</a><br>
                    <a class="" href="{{ url('ho-tro', 3)  }}">Xem biểu phí</a><br>

                    <br>

                    <a href="{{ url('')  }}"><< Về trang chủ</a>
                </div>
            </div>

        </div>
    </div>

@endsection
