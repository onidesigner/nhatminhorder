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
                                <th>Khách hàng</th>
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
                                    <a href="{{ url('order', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>
                                    <p>
                                        {{ App\Order::getStatusTitle($order->status)  }}
                                    </p>

                                </td>
                                <td>
                                    <?php
                                    $user = App\User::find($order->user_id);
                                    echo '<p><strong>' . $user->email . '</strong></p>';
//                                    echo '<p>' . $user->name . '</p>';
                                    ?>

                                    <p>
                                        Đặt cọc ({{$order->deposit_percent}}%): <span class="text-danger">{{$order->deposit_amount}} <sup>đ</sup></span>
                                    </p>
                                </td>
                                <td>
                                    <span class="text-danger">{{$order->exchange_rate}} <sup>đ</sup></span>
                                </td>
                                <td>
                                    <span class="text-danger">{{$order->amount * $order->exchange_rate}} <sup>đ</sup></span>
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

                        <h4>Hien chua co don hang!</h4>

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

