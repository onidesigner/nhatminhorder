@extends('layouts.app')
{{--@extends('layouts.app_blank')--}}

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
                                        ['name' => 'Trang chủ', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Lịch sử giao dịch', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Mã GD</th>
                            <th>Loại </th>
                            <th>Trạng thái </th>
                            <th>Đối tượng</th>
                            <th>Thời gian </th>
                            <th>Giá trị </th>
                            <th>Số dư cuối </th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($transactions as $transaction)
                        <?php

                            $user = App\User::find($transaction->user_id);
                            $order = App\Order::find($transaction->object_id);

                            if(!$user) $user = new App\User();
                            if(!$order) $order = new App\Order();
                        ?>
                        <tr>


                            <td>
                                {{$transaction->transaction_code}}<br>
                            <small class="" style="color: grey">{{$transaction->transaction_note}}</small>
                            </td>
                            <td>
                                {{ App\UserTransaction::$transaction_type[$transaction->transaction_type]  }}
                            </td>
                            <td>


                                    <span class="@if($transaction->state == App\UserTransaction::STATE_COMPLETED) label label-success @endif">
                                {{ App\UserTransaction::$transaction_state[$transaction->state]  }}
                                    </span>
                            </td>
                            <td>
                                @if($transaction->object_type == App\UserTransaction::OBJECT_TYPE_ORDER)
                                    <a href="{{ url('don-hang', $order->id) }}">{{$order->code}}</a>
                                @endif
                            </td>

                            <td>{{$transaction->created_at}}</td>
                            <td>
                                <span class="text-danger">
                                    {{ App\Util::formatNumber($transaction->amount) }} <sup>d</sup>
                                </span>
                            </td>
                            <td>
                                <strong>
                                    {{ App\Util::formatNumber($transaction->ending_balance) }} <sup>d</sup>
                                </strong>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $transactions->links() }}
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

