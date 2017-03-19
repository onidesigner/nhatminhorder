@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('css_bottom')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
@endsection

@section('widget')

@endsection

@section('js_bottom')
    @parent

    <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            var freight_bill_tpl = _.template($('#_freight-bill-tpl').html());
            var original_bill_tpl = _.template($('#_original-bill-tpl').html());

            $(document).on('keypress', '._input-action', function(e){

                if(e.keyCode == 13){
                    var value = $(this).val();
                    if($(this).hasClass('_autoNumeric')){
                        value = $(this).autoNumeric('get');
                    }
                    var action = $(this).data('action');
                    var item_id = $(this).data('item-id');
                    var message = $(this).val();
                    var $that = $(this);

                    if(!value){
                        return false;
                    }

                    $.ajax({
                        url: "{{ url('order/' .$order_id. '/action')  }}",
                        method: 'post',
                        data: {
                            item_id:item_id,
                            order_id:"{{$order_id}}",
                            message:message,
                            value:value,
                            action:action,
                            _token: "{{ csrf_token() }}",
                        },
                        success:function(response) {
                            if(response.success){
//                                $that.val('').focus();
                                window.location.reload();
                            }else{
                                bootbox.alert(response.message);
                            }

                        },
                        error: function(){

                        }
                    });
                }


            });

            $(document).on('click', '._btn-action', function(){

                var action = $(this).data('action');

                var $that = $(this);

                if($that.hasClass('disabled')) return false;

                $that.addClass('disabled');

                $.ajax({
                    url: "{{ url('order/' .$order_id. '/action')  }}",
                    method: 'post',
                    data: {
                        deposit:$('#_change_deposit').val(),
                        status: $that.data('status'),
                        domestic_shipping_china:$('#_domestic_shipping_china').val(),
                        action:action,
                        _token: "{{ csrf_token() }}",
                    },
                    success:function(response) {
                        if(response.success){
                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }

                        $that.removeClass('disabled');
                    },
                    error: function(){
                        $that.removeClass('disabled');
                    }
                });

            });

            $(document).on('change', '._select-action', function(){

                var action = $(this).data('action');

                var value = $(this).val();

                $.ajax({
                  url: "{{ url('order/' .$order_id. '/action')  }}",
                  method: 'post',
                  data: {
                      value:value,
                      action:action,
                      order_id:"{{$order_id}}",
                      _token: "{{ csrf_token() }}",
                  },
                  success:function(response) {
                        if(response.success){

                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }
                  },
                  error: function(){

                  }
                });

            });

            $(document).on('click', '#_save-freight-bill', function(){
                var $that = $(this);

                $that.prop('disabled', true);

                var freight_bill = $('#_freight_bill').val();

                $.ajax({
                    url: "{{ url('order/' . $order_id .'/freight_bill')  }}",
                    method: 'post',
                    data: {
                        order_id:"{{$order_id}}",
                        freight_bill:freight_bill,
                        _token: "{{ csrf_token() }}"
                    },
                    success:function(response) {
                        if(response.success){
                            {{--$('#_freight-bill-list').append( freight_bill_tpl ({--}}
                                {{--order_id: "{{$order_id}}",--}}
                                {{--freight_bill:freight_bill,--}}
                            {{--}) );--}}

                            {{--if(response.message){--}}
                                {{--bootbox.alert(response.message);--}}
                            {{--}--}}

                            {{--$('#_freight_bill').val('');--}}

                            window.location.reload();

                        }else{
                            bootbox.alert(response.message);

                        }

                        $that.prop('disabled', false);
                    },
                    error: function(){
                        $that.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '#_save-original-bill', function(){
                var $that = $(this);

                $that.prop('disabled', true);

                var original_bill = $('#_original_bill').val();

                $.ajax({
                    url: "{{ url('order/' . $order_id .'/original_bill')  }}",
                    method: 'post',
                    data: {
                        order_id:"{{$order_id}}",
                        original_bill:original_bill,
                        _token: "{{ csrf_token() }}"
                    },

                    success:function(response) {
                        if(response.success){
                            {{--$('#_original-bill-list').append( original_bill_tpl ({--}}
                                {{--order_id: "{{$order_id}}",--}}
                                {{--original_bill:original_bill,--}}
                            {{--}) );--}}

                            {{--if(response.message){--}}
                                {{--bootbox.alert(response.message);--}}
                            {{--}--}}

                            {{--$('#_original_bill').val('');--}}

                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }

                        $that.prop('disabled', false);
                    },
                    error: function(){
                        $that.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '._remove-freight-bill', function(){

                var $that = $(this);

                var order_id = $that.data('order-id');
                var freight_bill = $that.data('freight-bill');

                $.ajax({
                    url: "{{ url('order/' . $order_id .'/freight_bill')  }}",
                    method: 'put',
                    data: {
                        order_id: order_id,
                        freight_bill: freight_bill,
                        _token: "{{ csrf_token() }}"
                    },
                    success:function(response) {
                        if(response.success){
//                            $that.parents('._freight-bill-list-item').remove();
                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }
                    },
                    error: function(){

                    }
                });
            });

            $(document).on('click', '._remove-original-bill', function(){

                var $that = $(this);

                var order_id = $that.data('order-id');
                var original_bill = $that.data('original-bill');

                $.ajax({
                    url: "{{ url('order/' . $order_id .'/original_bill')  }}",
                    method: 'put',
                    data: {
                        order_id: order_id,
                        original_bill: original_bill,
                        _token: "{{ csrf_token() }}"
                    },
                    success:function(response) {
                        if(response.success){
//                            $that.parents('._original-bill-list-item').remove();
                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }
                    },
                    error: function(){

                    }
                });


            });

        });
    </script>
@endsection

@section('content')



    <div class="row">
        <div class="col-sm-8 col-xs-12">

            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Đơn hàng', 'link' => url('order')],
                                        ['name' => 'Đơn ' . $order->code, 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">
                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Thông tin chung</a>
                            </li>
                            <li role="presentation">
                                <a href="#order-transaction" aria-controls="tab" role="tab" data-toggle="tab">LS Giao dịch</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">


                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <table class="table no-padding-leftright">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="border-top-none">Acc mua</td>
                                                <td class="border-top-none">

                                                    @if($permission['can_change_order_account_purchase_origin'])
                                                    <select data-action="account_purchase_origin" class="form-control _select-action">
                                                        <option value="">Chọn Acc mua hàng site gốc</option>

                                                        @if($user_origin_site)
                                                            @foreach($user_origin_site as $key => $val)
                                                                <option data-site="{{$val->site}}" @if($val->username == $order->account_purchase_origin) selected @endif value="{{$val->username}}">{{$val->site}} - {{$val->username}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @else
                                                        {{$order->account_purchase_origin}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tỉ lệ đặt cọc (%)</td>
                                                <td>
                                                    <input id="_change_deposit" type="text" value="{{$order->deposit_percent}}">
                                                    <button class="_btn-action" data-action="change_deposit">
                                                        <i class="fa fa-save"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tỉ giá</td>
                                                <td>{{ App\Util::formatNumber($order->exchange_rate) }} <sup>đ</sup></td>
                                            </tr>
                                            <tr>
                                                <td>Người bán</td>
                                                <td>
                                                    <img src="{{ App\Order::getFavicon($order->site)  }}" width="16px" alt="">
                                                    <span>{{$order->seller_id}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Wangwang</td>
                                                <td>
                                                    <!-- aliwangwang -->
                                                    <a style="padding: 0 45px;position: relative;" target="_blank"
                                                       href="http://www.taobao.com/webww/ww.php?ver=3&amp;touid={{ $order->wangwang  }}&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8">
                                                        <img style="position: absolute;left: 3px;top: -4px;" border="0"
                                                             src="http://amos.alicdn.com/realonline.aw?v=2&amp;uid={{ $order->wangwang  }}&amp;site=cntaobao&amp;s=1&amp;charset=utf-8"
                                                             title="Click vào đây để chat với người bán">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Địa điểm bán</td>
                                                <td>
                                                    {{$order->location_sale}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Hóa đơn gốc</td>
                                                <td>

                                                    <input id="_original_bill" placeholder="" type="text" name="original_bill" value="" pattern="">

                                                    <button id="_save-original-bill">
                                                        <i class="fa fa-save"></i>
                                                    </button>

                                                    <ul style="margin: 0;padding: 0;list-style: none;" id="_original-bill-list">
                                                        @if(count($original_bill))
                                                            @foreach($original_bill as $key => $val)
                                                                <li class="_original-bill-list-item">{{$val->original_bill}}
                                                                    <a
                                                                            data-order-id="{{$val->order_id}}"
                                                                            data-original-bill="{{$val->original_bill}}"
                                                                            href="javascript:void(0)" class="_remove-original-bill"><i class="fa fa-times"></i></a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>



                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Vận đơn</td>
                                                <td>

                                                    <input id="_freight_bill" placeholder="" type="text" name="freight_bill" value="" pattern="">

                                                    <button id="_save-freight-bill">
                                                        <i class="fa fa-save"></i>
                                                    </button>

                                                    <ul style="margin: 0;padding: 0;list-style: none;" id="_freight-bill-list">
                                                        @if(count($freight_bill))
                                                            @foreach($freight_bill as $key => $val)
                                                                <li class="_freight-bill-list-item">{{$val->freight_bill}}
                                                                    <a
                                                                            data-order-id="{{$val->order_id}}"
                                                                            data-freight-bill="{{$val->freight_bill}}"
                                                                            href="javascript:void(0)" class="_remove-freight-bill"><i class="fa fa-times"></i></a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>




                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Phí VC nội địa TQ (¥)</td>
                                                <td>

                                                    {{--<div class="input-group">--}}
                                                        {{--<input type="text" class="form-control" placeholder="Input group" aria-describedby="basic-addon1" value="">--}}
                                                        {{--<span class="input-group-addon" id="basic-addon1" style="padding: 0;">--}}
                                                            {{--<button class="btn btn-danger" style="margin: 0;border-radius: 0;"><i class="fa fa-user" aria-hidden="true"></i></button>--}}
                                                        {{--</span>--}}
                                                    {{--</div>--}}

                                                    @if($permission['can_change_order_domestic_shipping_fee'])
                                                    <input id="_domestic_shipping_china" placeholder="Đơn vị NDT" type="text" name="" value="{{ $order->domestic_shipping_fee  }}" pattern="">

                                                    <button data-action="domestic_shipping_china" class="_btn-action">
                                                        <i class="fa fa-save"></i>
                                                    </button>



                                                    @else
                                                        {{ $order->domestic_shipping_fee  }}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Kho nhận hàng</td>
                                                <td>

                                                    <select data-action="receive_warehouse" class="form-control _select-action">
                                                        <option value="">Chọn kho</option>

                                                        @if($warehouse_receive)
                                                            @foreach($warehouse_receive as $key => $val)
                                                                <option @if($val->code == $order->receive_warehouse) selected @endif value="{{$val->code}}">[{{$val->alias}}] {{$val->name}} ({{$val->code}})</option>
                                                            @endforeach
                                                        @endif
                                                    </select>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kho phân phối</td>
                                                <td>
                                                    <select data-action="destination_warehouse" class="form-control _select-action">
                                                        <option value="">Chọn kho</option>

                                                        @if($warehouse_distribution)
                                                            @foreach($warehouse_distribution as $key => $val)
                                                                <option @if($val->code == $order->destination_warehouse) selected @endif value="{{$val->code}}">[{{$val->alias}}] {{$val->name}} ({{$val->code}})</option>
                                                            @endforeach
                                                        @endif
                                                    </select>

                                                </td>
                                            </tr>
                                            @if($order->user_address_id)
                                            <tr>
                                                <td>Đ/C nhận hàng</td>
                                                <td>
                                                    <i class="fa fa-user"></i> {{$user_address->reciver_name}} - <i class="fa fa-phone"></i> {{$user_address->reciver_phone}}
                                                    <br>
                                                    <i class="fa fa-map-marker"></i> {{$user_address->detail}}, {{$user_address->district_label}}, {{$user_address->province_label}}<br>
                                                </td>
                                            </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">

                                        <table class="table no-padding-leftright">
                                            <tbody>
                                            <?php $count = 0; ?>

                                            <?php
                                                foreach(App\Order::$timeListOrderDetail as $k => $v){
                                                $count++;
                                                if(empty($order->$k)){
                                                    continue;
                                                }
                                            ?>

                                                @if($count == 1)

                                                    <tr>
                                                        <td width="50%" class="border-top-none">{{$v}}</td>
                                                        <td class="border-top-none">{{ App\Util::formatDate($order->$k) }}</td>
                                                    </tr>

                                                @else
                                                    <tr>
                                                        <td>{{$v}}</td>
                                                        <td>{{ App\Util::formatDate($order->$k)  }}</td>
                                                    </tr>
                                                @endif

                                            <?php } ?>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="order-transaction">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Mã GD</th>
                                        <th>Loại</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                        <th>Giá trị</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($transactions as $transaction)
                                        <?php

                                        $user = App\User::find($transaction->user_id);
                                        $order2 = App\Order::find($transaction->object_id);

                                        if(!$user) $user = new App\User();
                                        if(!$order2) $order2 = new App\Order();
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


                                            <td>{{ App\Util::formatDate($transaction->created_at)  }}</td>
                                            <td>
                                <span class="text-danger">
                                    {{ App\Util::formatNumber($transaction->amount) }} <sup>d</sup>
                                </span>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <br>

            <div class="card">

                <div class="card-body">
                    <?php
                    $total_order_quantity = 0;
                    $total_price_ndt = 0;
                    $total_price_vnd = 0;
                    if(count($order_items)){
                        foreach($order_items as $order_item){
                            $total_order_quantity += $order_item->order_quantity;
                            $total_price_ndt += $order_item->getPriceCalculator() * $order_item->order_quantity;
                            $total_price_vnd += $order_item->getPriceCalculator() * $order_item->order_quantity * $order->exchange_rate;
                        }
                    }

                    ?>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50%">SẢN PHẨM</th>
                            <th>SL ({{ $total_order_quantity }})</th>
                            <th>{{ $total_price_ndt  }}¥ · {{ App\Util::formatNumber($total_price_vnd)  }} <sup>đ</sup></th>
                        </tr>
                        </thead>
                        @if(count($order_items))
                        <tbody>
                        @foreach($order_items as $order_item)
                            <tr>

                                <td>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <a href="{{$order_item->link}}" target="_blank">
                                                <img class="img-responsive" width="90px" src="{{$order_item->image}}" alt="">
                                            </a>
                                        </div>
                                        <div class="col-sm-10">
                                            ID: #{{$order_item->id}}<br>
                                            <a href="{{$order_item->link}}" target="_blank">Link gốc</a>
                                            <br>

                                            <p>
                                                Địa điểm đăng bán: {{$order_item->location_sale}}
                                            </p>
                                            <p>
                                                Mẫu: {{$order_item->property}}
                                            </p>

                                            <input style="width: 100%; margin-bottom: 10px;"
                                                    data-action="order_item_comment"
                                                    data-item-id="{{$order_item->id}}"
                                                    class="_input-action" type="text" placeholder="Chat về sản phẩm...">


                                            <ul style="    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 13px;">
                                                @if(!empty($order_item_comments[$order_item->id]))
                                                    @foreach($order_item_comments[$order_item->id] as $order_item_comment)
                                                        <li style="margin-bottom: 5px;

                                                        @if(in_array($order_item_comment->type_context, [App\Comment::TYPE_CONTEXT_ACTIVITY, App\Comment::TYPE_CONTEXT_LOG]))

                                                                color: grey;
                                                        @endif

">
                                                            @if($order_item_comment->type_context != App\Comment::TYPE_CONTEXT_LOG)

                                                                <strong>{{$order_item_comment->user->name}}</strong>

                                                            @endif

                                                            {{$order_item_comment->message}}
                                                            <small>
                                                                {{ App\Util::formatDate($order_item_comment->created_at)  }}
                                                            </small>

                                                        </li>
                                                    @endforeach
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($permission['can_change_order_item_quantity'])
                                        <input
                                                style="width: 80px;"
                                                class="_input-action"
                                                data-action="change_order_item_quantity"
                                                data-item-id="{{$order_item->id}}"
                                                type="number"
                                                value="{{$order_item->order_quantity}}" placeholder="">
                                    @else
                                        {{$order_item->order_quantity}}
                                    @endif
                                </td>
                                <td>
                                    <p>
                                        Đơn giá:
                                        @if($permission['can_change_order_item_price'])
                                        <input
                                                style="width: 90px;"
                                                class="_input-action _autoNumeric"
                                                data-action="change_order_item_price"
                                                data-item-id="{{$order_item->id}}"
                                                type="text"
                                                value="{{ $order_item->getPriceCalculator() }}" placeholder="">¥ ·
                                        @else
                                            <span class="text-success">{{ $order_item->getPriceCalculator() }}</span>¥ ·
                                        @endif
                                        {{ App\Util::formatNumber($order_item->getPriceCalculator() * $order->exchange_rate) }}

                                        <sup>đ</sup>
                                    </p>
                                    <p>
                                        Tổng: <span class="text-success">{{$order_item->getPriceCalculator() * $order_item->order_quantity}}¥</span> · {{ App\Util::formatNumber($order_item->getPriceCalculator() * $order_item->order_quantity * $order->exchange_rate) }} <sup>đ</sup>
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>



        </div>

        <div class="col-sm-4 col-xs-12">

            @include('partials/__comment', [
                'object_id' => $order_id,
                'object_type' => App\Comment::TYPE_OBJECT_ORDER,
                'scope' => App\Comment::TYPE_EXTERNAL
            ])

        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Hành động
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    @if($permission['can_change_order_bought'])
                        <li><a href="javascript:void(0)" class="_btn-action" data-action="bought_order">ĐÃ MUA</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>



    <script type="text/template" id="_freight-bill-tpl">
        <li><%= freight_bill %>
            <a
                    data-order-id="<%= order_id %>"
                    data-freight-bill="<%= freight_bill %>"
                    href="javascript:void(0)" class="_remove-freight-bill"><i class="fa fa-times"></i></a>
        </li>
    </script>

    <script type="text/template" id="_original-bill-tpl">
        <li><%= original_bill %>
            <a
                    data-order-id="<%= order_id %>"
                    data-original-bill="<%= original_bill %>"
                    href="javascript:void(0)" class="_remove-original-bill"><i class="fa fa-times"></i></a>
        </li>
    </script>

@endsection



