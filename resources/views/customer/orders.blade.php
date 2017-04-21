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

                    <form onchange="this.submit();" action="{{ url('don-hang')  }}" method="get" id="_form-orders">

                        <input type="text" placeholder="Mã đơn..." name="order_code" value="{{  @$params['order_code'] }}">
                        <input type="text" placeholder="Mã khách hoặc email..."
                               class="hidden"
                               name="customer_code_email" value="{{ @$params['customer_code_email']  }}">

                        <br><br>

                        @foreach($status_list as $status_list_item)
                            @if($status_list_item['selected'])
                                <a class="_select-order-status selected" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                    <span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i> {{ $status_list_item['val']  }}</span>
                                </a>
                            @else
                                <a class="_select-order-status" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                    <span class="label label-success"><span>{{ $status_list_item['val']  }}</span></span>
                                </a>
                            @endif
                        @endforeach

                        <input type="hidden" name="status" value="">

                    </form>
                    <br>

                    @if(count($orders))

                        <p>Tìm thấy {{ $total_orders }} đơn hàng</p>

                        <div class="table-responsive">
                            <table class="table no-padding-leftright table-striped">
                                <thead>
                                    <tr>

                                        <th width="25%">Đơn hàng</th>
                                        {{--<th width="30%">Khách hàng</th>--}}
                                        <th width="25%">Phí trên đơn</th>
                                        <th width="20%">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($orders as $order)
                                    <tr>
                                        <td>

                                            <img
                                                    data-src="{{ $order->avatar }}"
                                                    src=""
                                                    class="lazy"
                                                    style="width: 100px; float: left; margin-right: 10px;" alt="">



                                            <p>
                                                {!! App\Util::showSite($order->site) !!}
                                            </p>


                                            <p>
                                                <a href="{{ url('don-hang', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>
                                            </p>
                                            <p>
                                                {{ App\Order::getStatusTitle($order->status)  }}
                                            </p>

                                        </td>

                                        <td>

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



                        {{--{{ $orders->links() }}--}}

                        {{ $orders->appends(request()->input())->links() }}

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
    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
    <script>
        $(function() {
            $('.lazy').lazy();

            $(document).on('click', '._select-order-status', function(){
                var selected = $(this).hasClass('selected');
                if(selected){
                    $(this).removeClass('selected');
                }else{
                    $(this).addClass('selected');
                }

                var order_status_list = [];
                $('._select-order-status.selected').each(function(){
                    order_status_list.push($(this).data('status'));
                });

                $('[name="status"]').val(order_status_list.join(','));

                $('#_form-orders').submit();
            });
        });
    </script>
@endsection

