@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    <h3>{{$page_title}}</h3>

                    @if(count($orders))

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Site</th>
                                <th>Shop avatar</th>
                                <th>Ma don</th>

                                <th>Trang thai</th>
                                <th>SL khach dat</th>
                                <th>Tien hang</th>
                                <th>Thoi gian</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>

                                    @if($order->site == 'tmall')
                                        <span class="label label-danger">tmall</span>&nbsp;
                                    @endif

                                    @if($order->site == '1688')
                                        <span class="label label-success">1688</span>&nbsp;
                                    @endif

                                    @if($order->site == 'taobao')
                                        <span class="label label-warning">taobao</span>&nbsp;
                                    @endif

                                </td>
                                <td>
                                    <img src="{{$order->shop_avatar}}" style="width: 50px; float: left; margin-right: 15px;" alt="">




                                </td>
                                <td><a href="{{ url('order', $order->id)  }}">{{$order->code}}</a></td>

                                <td>
                                    <span class="text-uppercase">Dat dat coc</span>
                                </td>
                                <td>{{ $order->order_quantity  }}</td>
                                <td>{{ $order->total_amount * $exchange_rage  }} <sup>d</sup> / {{ $order->total_amount  }} ¥</td>
                                <td>
                                    Tao: {{$order->created_at}}<br>
                                    Dat coc: {{$order->deposit_at}}<br>

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

