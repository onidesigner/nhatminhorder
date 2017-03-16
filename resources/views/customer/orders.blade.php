@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Đơn hàng', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">

                    <h3>{{$page_title}}</h3>

                    @if(count($orders))

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Đơn hàng</th>
                                <th>Đặt cọc</th>
                                <th>Tỉ giá</th>
                                <th>Tiền hàng</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td>

                                        <img src="{{$order->avatar}}" style="width: 50px; float: left; margin-right: 15px;" alt="">

                                        <strong>[{{strtoupper($order->site)}}]</strong>
                                        <a href="{{ url('don-hang', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>
                                        <p>
                                            {{ App\Order::getStatusTitle($order->status)  }}
                                        </p>

                                    </td>
                                    <td>
                                        <p>
                                            Đặt cọc ({{$order->deposit_percent}}%): <span class="text-danger">{{ App\Util::formatNumber($order->deposit_amount)  }} <sup>đ</sup></span>
                                        </p>
                                    </td>
                                    <td>
                                        <span class="text-danger">{{ App\Util::formatNumber($order->exchange_rate) }} <sup>đ</sup></span>
                                    </td>
                                    <td>
                                        <span class="text-danger">{{ App\Util::formatNumber($order->amount * $order->exchange_rate) }} <sup>đ</sup></span>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Tạo: {{$order->created_at}}</li>
                                            <li>Đặt cọc: {{$order->deposited_at}}</li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        {{ $orders->links() }}

                    @else
                        <h4>Hiện chưa có đơn hàng!</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

