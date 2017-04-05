@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="card">

                @include('partials/__breadcrumb',
                                                [
                                                    'urls' => [
                                                        ['name' => 'Trang chủ', 'link' => null],
                                                    ]
                                                ]
                                            )

                <div class="card-body">
                    <h3>Hướng dẫn dành cho khách hàng mới</h3>
                    <a class="" href="{{ url('ho-tro', 4)  }}">Hướng dẫn cài đặt công cụ đặt hàng & đặt cọc đơn hàng</a><br>
                    <a class="" href="{{ url('ho-tro', 5)  }}">Hướng dẫn tìm nguồn hàng trên website taobao.com, tmall.com, 1688.com</a><br>
                    <a class="" href="{{ url('ho-tro', 1)  }}">Hướng dẫn nạp tiền vào tài khoản</a><br>
                    <a class="" href="{{ url('ho-tro', 3)  }}">Xem biểu phí</a><br>

                    <br>
                    Click vào <a href="{{ url('')  }}">đây</a> để về trang chủ NhatMinh247.
                </div>
            </div>

        </div>
    </div>

@endsection
