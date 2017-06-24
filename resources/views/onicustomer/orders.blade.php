@extends($layout)

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="alert alert-warning">
        Đơn hàng ở trạng thái "Đang Giao Hàng", sau 3 ngày khách không ấn "Đã Nhận", hệ thống sẽ tự động chuyển sang trạng thái "Đã Nhận".
    </div>
    <div class="ibox-content m-b-sm border-bottom">
        <form onchange="this.submit();" action="{{ url('orders')  }}" method="get" id="_form-orders">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="order_code">Order ID</label>
                        <input type="text" placeholder="Mã đơn..." id="order_code" name="order_code" value="{{  @$params['order_code'] }}" class="form-control">
                        <input type="hidden" placeholder="Mã khách hoặc email..." name="customer_code_email" value="{{ @$params['customer_code_email']  }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
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
                </div>
            </div>
            <input type="hidden" name="status" value="">
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    @if(count($orders))
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                            <tr>

                                <th style="width:120px">Mã đơn hàng</th>
                                <th data-sort-ignore="true" data-hide="phone">Đơn hàng</th>
                                <th data-sort-ignore="true" data-hide="phone,tablet">Phí trên đơn</th>
                                <th data-sort-ignore="true" data-hide="phone">Thời gian</th>
                                <th>Trạng thái</th>
                                <th data-sort-ignore="true" class="text-right">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        {{$order->code}}
                                    </td>
                                    <td>
                                        <div style="position: relative; display: inline-block;">
                                            <img src="{{ $order->avatar }}" style="width: 100px;" alt="">
                                            <div style="position: absolute; bottom: 2px;">
                                                {!! App\Util::showSite($order->site) !!}
                                            </div>
                                        </div>
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
                                        <small id="_time_change_status_{{$order->id}}">
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
                                    <td>
                                    <span id="_order_status_{{  $order->id }}"class="label label-primary">
                                        {{ App\Order::getStatusTitle($order->status)  }}
                                    </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ url('order', $order->id)  }}" class="btn-white btn btn-xs">Chi tiết</a>
                                            <!--<button class="btn-white btn btn-xs">Edit</button>-->
                                            @if($order->status == \App\Order::STATUS_DELIVERING)
                                                <button class="btn-white btn btn-xs _btn_change_status" data-order-id="{{ $order->id }}" id ="_btn_change_status_{{ $order->id }}" type="button">Đã nhận</button>
                                            @endif
                                            @if($order->status == \App\Order::STATUS_BOUGHT)
                                                <form class="___form">
                                                    <input type="hidden" name="action" value="cancel_order">
                                                    <input type="hidden" name="method" value="post">
                                                    <input type="hidden" name="url" value="{{ url('don-hang/' .$order_id. '/hanh-dong')  }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token()  }}">
                                                    <input type="hidden" name="response" value="onicustomer/order_detail">

                                                    <a
                                                        style="display: inline-block;width: 100%;padding: 0 15px;"
                                                        href="javascript:void(0)"
                                                        class="btn-white btn btn-xs ___btn-action">Hủy đơn</a>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4>Hiện chưa có đơn hàng!</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header-scripts')
    <link href="{!! asset('oniasset/css/plugins/footable/footable.core.css') !!}" rel="stylesheet"/>
@endsection

@section('footer-scripts')
    <script src="{{ asset('oniasset/js/plugins/footable/footable.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.footable').footable();
        });
    </script>

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

            /**
             * event xay ra khi click nut da nhan hang
             */
            $(document).on('click','._btn_change_status',function () {
                var order_id = $(this).data('order-id');
                $("#_btn_change_status_"+order_id).prop('disabled',true);
                $.ajax({
                    url : '/change-status-order',
                    type : 'POST',
                    data : {
                        order_id : order_id
                    }
                }).done(function (response) {
                    if(response.type == 'success'){
                        $("#_order_status_"+order_id).html('Đã nhận hàng');
                        $("#_btn_change_status_"+order_id).addClass('hidden');
                        $("#_time_change_status_"+order_id).append("<p>Đã nhận hàng:</p>"+response.date);

                    }else{
                        $("#_btn_change_status_"+order_id).prop('disabled',false);
                    }
                });
            });
        });
    </script>
@endsection

