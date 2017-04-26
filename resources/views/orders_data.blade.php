<p>Tìm thấy {{ $total_orders }} đơn hàng</p>

@if(count($orders))

    <div class="table-responsive">

        <table class="table table-striped table-hover no-padding-leftright">
            <thead>
            <tr>

                <th width="25%">Đơn hàng</th>
                <th width="30%">Khách hàng</th>
                <th width="25%">Phí trên đơn</th>
                <th width="20%">Thời gian</th>
            </tr>
            </thead>
            <tbody>

            @foreach($orders as $order)
                <tr>

                    <td>

                        <img
                                class="lazy"
                                data-src="{{$order->avatar}}"
                                alt="" style="float: left; margin-right: 10px;" width="100px">

                        <div>
                            {!! App\Util::showSite($order->site) !!}
                            <br>

                            <a href="{{ url('order', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>

                            <p>
                                {{ App\Order::getStatusTitle($order->status)  }}
                                <br>
                                @if(isset($services[$order->id]))
                                    @foreach($services[$order->id] as $service)

                                        <span data-toggle="tooltip"
                                              title="{{ $service['name']  }}"
                                              class=""
                                              data-code="{{ $service['code']  }}">

                                                        <i class="fa {{ $service['icon']  }}"></i>

                                                    </span>

                                    @endforeach
                                @endif
                            </p>
                        </div>


                    </td>

                    <td>
                        <?php
                        $customer = App\User::find($order->user_id);
                        echo '<p><strong><a href="' . url('user/detail', $customer->id) . '">' . $customer->email . '</a></strong> ('. $customer->code .')</p>';
                        ?>

                        <p>
                            Đặt cọc ({{$order->deposit_percent}}%): <span class="text-danger">{{ App\Util::formatNumber($order->deposit_amount) }} <sup>đ</sup></span>
                        </p>
                        <p>
                            Số dư: <span class="text-danger">{{App\Util::formatNumber($order->customer->account_balance)}} <sup>đ</sup></span>
                        </p>
                    </td>

                    <td>
                        {{--<span class="text-danger">{{ App\Util::formatNumber($order->amount * $order->exchange_rate) }} <sup>đ</sup></span>--}}

                        <small>
                            @foreach($order->order_fee as $order_fee_item)
                                <p>
                                    {!! $order_fee_item['label'] !!}:
                                    <span class="text-danger">
                                                            <strong>{{$order_fee_item['value']}}<sup>đ</sup></strong>
                                                        </span>
                                </p>
                            @endforeach
                        </small>


                    </td>
                    <td>
                        <small>
                            <?php
                            foreach(App\Order::$timeListOrderDetail as $k => $v){
                            if(empty($order->$k)){
                                continue;
                            }
                            ?>
                            <p>{{$v}}: {{ App\Util::formatDate($order->$k) }}</p>
                            <?php } ?>
                        </small>

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>

    {{ $orders->appends(request()->input())->links() }}
@else
    <h4>Hiện chưa có đơn hàng!</h4>
@endif