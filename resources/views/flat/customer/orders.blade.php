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

@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="card">

                @include('flat/partials/breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Bảng chung', 'link' => url('home')],
                                        ['name' => 'Đơn hàng', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">

                    <form onchange="this.submit();" action="{{ url('don-hang')  }}" method="get" id="_form-orders">

                        <input type="text" placeholder="Mã đơn..." name="order_code" value="{{  @$params['order_code'] }}">
                        <input type="text" placeholder="Mã khách hoặc email..."
                               class="hidden"
                               name="customer_code_email" value="{{ @$params['customer_code_email']  }}">

                        <br><br>

                        @foreach($status_list as $status_list_item)
                            @if($status_list_item['selected'])
                                <a class="_select-order-status selected" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                    <span class="label label-danger"><i class="glyphicon-remove_2" aria-hidden="true"></i> {{ $status_list_item['val']  }}</span>
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
                            <table class="table table-striped table-hover no-padding-leftright">
                                <thead>
                                    <tr>

                                        <th width="25%">Đơn hàng</th>
                                        <th width="50%%">Phí trên đơn</th>
                                        <th width="25%">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($orders as $order)
                                    <tr>
                                        <td style="vertical-align: top;">

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

                                        <td style="vertical-align: top;">

                                            <small>
                                                @foreach($order->order_fee as $order_fee_item)
                                                    <div style="width: 50%; display: inline-block; float: left;">
                                                        <div style="display: inline-block; padding: 5px 10px;">
                                                            {!! $order_fee_item['label'] !!}:
                                                            <span class="text-danger">
                                                                <strong>{{$order_fee_item['value']}}<sup>đ</sup></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </small>


                                        </td>
                                        <td style="vertical-align: top;">
                                            <small>
                                                <?php
                                                foreach(App\Order::$timeListOrderDetail as $k => $v){
                                                if(empty($order->$k)){
                                                    continue;
                                                }
                                                ?>
                                                    <div style="width: 50%; display: inline-block; float: left;">
                                                        <div style="display: inline-block; padding: 5px 10px;">{{$v}}: {{ App\Util::formatDate($order->$k) }}</div>
                                                    </div>

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

