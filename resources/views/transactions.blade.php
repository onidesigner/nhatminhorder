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


                    @if($can_create_transaction)
                        <a href="{{ url('transaction/adjustment')  }}" class="btn btn-danger text-uppercase pull-right">
                            TẠO GIAO DỊCH</a>
                    @endif

                    <table class="table no-padding-leftright">
                        <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Khách</th>
                            <th width="20%">Mã GD</th>
                            <th width="10%">Trạng thái</th>
                            <th width="10%">Đối tượng</th>
                            <th width="15%">Thời gian</th>
                            <th class="text-right">Giá trị</th>
                            <th class="text-right">Số dư cuối</th>
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
                                {{$transaction->id}}
                            </td>
                            <td>
                                <a href="{{ url('user/detail', $user->id)  }}">
                                    <strong>{{$user->email}}</strong>
                                </a><br>

                                <small>{{$user->name}}</small>

                                <code>{{$user->code}}</code>
                            </td>
                            <td>
                                {{$transaction->transaction_code}}<br>
                            <small class="" style="color: grey">{{$transaction->transaction_note}}</small>
                                <p>
                                    Loại GD: {{ App\UserTransaction::$transaction_type[$transaction->transaction_type]  }}
                                </p>
                            </td>

                            <td>


                                    <span class="@if($transaction->state == App\UserTransaction::STATE_COMPLETED) label label-success @endif">
                                {{ App\UserTransaction::$transaction_state[$transaction->state]  }}
                                    </span>
                            </td>
                            <td>
                                @if($transaction->object_type == App\UserTransaction::OBJECT_TYPE_ORDER)
                                    <a href="{{  url('order', $order->id) }}">{{$order->code}}</a>
                                @endif
                            </td>

                            <td>{{ App\Util::formatDate($transaction->created_at)  }}</td>
                            <td class="text-right">
                                <span class="text-danger">
                                    {{ App\Util::formatNumber($transaction->amount) }} <sup>d</sup>
                                </span>




                            </td>
                            <td class="text-right">
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

