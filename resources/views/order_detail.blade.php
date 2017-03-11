@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('js_bottom')
    @parent

    <script>
        $(document).ready(function(){

            var freight_bill_tpl = _.template($('#_freight-bill-tpl').html());
            var original_bill_tpl = _.template($('#_original-bill-tpl').html());

            $(document).on('click', '#_save-freight-bill', function(){
                var $that = $(this);

                if($(this).hasClass('disabled')) return false;

                $(this).addClass('disabled');

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
                            $('#_freight-bill-list').append( freight_bill_tpl ({
                                order_id: "{{$order_id}}",
                                freight_bill:freight_bill,
                            }) );

                            if(response.message){
                                bootbox.alert(response.message);
                            }

                            $('#_freight_bill').val('');

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

            $(document).on('click', '#_save-original-bill', function(){
                var $that = $(this);

                if($(this).hasClass('disabled')) return false;

                $(this).addClass('disabled');

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
                            $('#_original-bill-list').append( original_bill_tpl ({
                                order_id: "{{$order_id}}",
                                original_bill:original_bill,
                            }) );

                            if(response.message){
                                bootbox.alert(response.message);
                            }

                            $('#_original_bill').val('');
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
                            $that.parents('._freight-bill-list-item').remove();
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
                            $that.parents('._original-bill-list-item').remove();
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


                                <h4>Mã vận đơn</h4>

                                <ul id="_freight-bill-list">
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


                                <input id="_freight_bill" placeholder="" type="text" name="freight_bill" value="" pattern="">
                                <a href="javascript:void(0)" id="_save-freight-bill">Thêm</a>

                                <br>

                                <h4>Mã đơn gốc</h4>

                                <ul id="_original-bill-list">
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




                                <input id="_original_bill" placeholder="" type="text" name="original_bill" value="" pattern="">
                                <a href="javascript:void(0)" id="_save-original-bill">Thêm</a>

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


                                            <td>{{$transaction->created_at}}</td>
                                            <td>
                                <span class="text-danger">
                                    {{$transaction->amount}} <sup>d</sup>
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
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th>SẢN PHẨM</th>
                            <th>SL</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if(count($order_items))
                        <tbody>
                        @foreach($order_items as $order_item)
                            <tr>
                                <td>{{$order_item->id}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <a href="{{$order_item->link}}" target="_blank">
                                                <img class="img-responsive" width="90px" src="{{$order_item->image}}" alt="">
                                            </a>
                                        </div>
                                        <div class="col-sm-10">
                                            <a href="{{$order_item->link}}" target="_blank">Link gốc</a>
                                            <br>

                                            <p>
                                                Địa điểm đăng bán: {{$order_item->location_sale}}
                                            </p>
                                            <p>
                                                Mẫu: {{$order_item->property}}
                                            </p>

                                            <input type="text" placeholder="Chat về sản phẩm...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{$order_item->order_quantity}}
                                </td>
                                <td>
                                    <p>
                                        Đơn giá: {{$order_item->order_quantity}} <sup>đ</sup>
                                    </p>
                                    <p>
                                        Tổng: {{$order_item->order_quantity}} <sup>đ</sup>
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



