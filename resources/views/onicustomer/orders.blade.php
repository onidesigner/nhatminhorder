@extends($layout)

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-content orderbox-content">
                        <a class="btn btn-block btn-primary compose-mail" href="#">Tạo đơn hàng mới</a>
                        <div class="space-25"></div>
                        <div class="file-manager">
                            <h5>Đơn hàng</h5>
                            <ul class="folder-list m-b-md" style="padding: 0">
                                <li>
                                    <a href="{{ url('orders') }}">
                                        Tất cả <!--<span class="label label-warning pull-right">16</span>-->
                                    </a>
                                </li>

                                @foreach($status_list as $status_list_item)
                                    <?php   if($status_list_item['selected']) $selected='selected';
                                            else $selected='';?>
                                        <li>
                                            <a href="{{ url('orders?status='.$status_list_item['key']) }}" class="{{$selected}}" data-status="{{ $status_list_item['key'] }}">
                                                {{ $status_list_item['val']  }}
                                                @if($status_list_item['count'])
                                                <span class="label label-warning pull-right">{{ $status_list_item['count']  }}</span>
                                                @endif
                                            </a>
                                        </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 order-box animated fadeInRight">
                <div class="order-box-header">
                    <div class="order-tools tooltip-demo">
                        <form onchange="this.submit();" action="{{ url('orders')  }}" method="get" id="_form-orders">
                            <div class="row">
                                <!-- Ma don hang -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="order_code">Mã đơn hàng</label>
                                        <input type="text" class="form-control input-sm" placeholder="Nhập mã đơn hàng" id="order_code" name="order_code" value="{{  @$params['order_code'] }}">
                                    </div>
                                </div>
                                <!-- Thoi gian -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Thời gian</label>
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" class="input-sm form-control" name="start" value="{{  @$params['start'] }}"/>
                                            <span class="input-group-addon">đến</span>
                                            <input type="text" class="input-sm form-control" name="end" value="{{  @$params['end'] }}" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Trang thai -->
                                <div class="col-sm-12">
                                    <div>
                                        @foreach($status_list as $status_list_item)
                                            @if($status_list_item['selected'])
                                                <a class="btn btn-sm btn-danger m-b-xs _select-order-status selected" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                                    <i class="fa fa-times" aria-hidden="true"></i> {{ $status_list_item['val']  }}
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-primary m-b-xs _select-order-status" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                                    <span>{{ $status_list_item['val']  }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="status" value="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="alert alert-warning">
                    Đơn hàng ở trạng thái "Đang Giao Hàng", sau 3 ngày khách không ấn "Đã Nhận", hệ thống sẽ tự động chuyển sang trạng thái "Đã Nhận".
                </div>
                <div class="order-box-content">
                    @if(count($orders))
                        <table class="footable table table-stripped toggle-arrow-tiny table-hover table-order" data-page-size="15">
                            <thead>
                            <tr>

                                <th style="width:130px">Mã đơn hàng</th>
                                <th data-sort-ignore="true" data-hide="phone">Thông tin đơn hàng</th>
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
    <link href="{!! asset('oniasset/css/plugins/datapicker/datepicker3.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('oniasset/css/plugins/select2/select2.min.css') !!}" rel="stylesheet"/>
@endsection

@section('footer-scripts')
    <script src="{{ asset('oniasset/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('oniasset/js/plugins/footable/footable.all.min.js') }}"></script>
    <script src="{{ asset('oniasset/js/plugins/select2/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.footable').footable();
            $('.input-daterange').datepicker({
                format: 'dd-mm-yyyy',
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
            $(".select2_demo_2").select2();
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

