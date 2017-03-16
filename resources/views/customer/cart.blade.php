@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @include('partials/__cart_step', ['active' => 1])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <strong>{{$data['statistic']['total_shops']}}</strong> shop / <strong>{{$data['statistic']['total_items']}}</strong> sản phẩm / <strong><span class="_autoNumeric">{{$data['statistic']['total_amount']}}</span></strong>đ tiền hàng
        </div>
    </div>

    @if(!empty($data['shops']))
        @foreach($data['shops'] as $shop)



        <div class="row _shop" data-shop-id="{{$shop->shop_id}}">
            <div class="col-xs-12">
                <div class="card ">
                    <div class="card-header" style="position: relative">
                        <a href="javascript:void(0)" class="_delete-shop" data-shop-id="{{$shop->shop_id}}" data-toggle="tooltip" title="Xoá shop ">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        &nbsp;
                        &nbsp;
                        @if($shop->site == 'tmall')
                        <span class="label label-danger">tmall</span>&nbsp;
                        @endif

                        @if($shop->site == '1688')
                            <span class="label label-success">1688</span>&nbsp;
                        @endif

                        @if($shop->site == 'taobao')
                            <span class="label label-warning">taobao</span>&nbsp;
                        @endif

                        {{$shop->shop_name}}

                        <div style="position: absolute;
        top: 18px;
        right: 20px;">
                            @foreach($data['services'] as $service)
                                <div class="checkbox checkbox-inline">
                                    <input @if(in_array($service['code'], $shop->services)) checked @endif type="checkbox" value="{{$service['code']}}" class="_chk-service" data-shop-id="{{$shop->shop_id}}" id="checkbox_{{$service['code']}}_{{$shop->id}}">
                                    <label for="checkbox_{{$service['code']}}_{{$shop->id}}">
                                        {{$service['title']}}
                                    </label>
                                </div>
                            @endforeach


                        </div>
                    </div>
                    <div class="card-body no-padding">
                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="50%">Sản phẩm </th>
                                    <th width="15%">Đơn giá </th>
                                    <th width="15%" class="">SL</th>
                                    <th width="15%">Tiền hàng </th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($shop->items as $item)
                                <tr class="_shop-item" data-shop-id="{{$shop->shop_id}}" data-shop-item-id="{{$item->id}}">
                                    <td>
                                        <a class="_delete-item" data-shop-id="{{$shop->shop_id}}" data-item-id="{{$item->id}}" href="javascript:void(0)" data-toggle="tooltip" title="Xoá sản phẩm ">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <img style="margin-right: 10px;" src="{{ urldecode($item->image_model) }}" class="pull-left" width="50px" />
                                        <a href="{{$item->link_origin}}" target="_blank">
                                            {{$item->title_origin}}
                                        </a>
                                        <br />
                                        <small>{{$item->property}}</small>
                                        <br>
                                        <input data-shop-id="{{$shop->shop_id}}" data-item-id="{{$item->id}}" placeholder="Ghi chú sản phẩm..." style="width: 250px; padding: 0 5px;" type="text" class="_comment" value="{{$item->comment}}" />

                                    </td>

                                    <td><span class="_autoNumeric">{{$item->price_calculator_vnd}}</span>đ / ¥{{$item->price_calculator}}</td>
                                    <td>
                                        <input
                                                data-shop-id="{{$shop->shop_id}}"
                                                data-item-id="{{$item->id}}" style="width: 80px"
                                                type="number"
                                                name="quantity" class="form-control text-center _quantity" value="{{$item->quantity}}" />
                                    </td>
                                    <td><span class="_autoNumeric">{{$item->total_amount_item_vnd}}</span>đ / ¥{{$item->total_amount_item}}</td>

                                </tr>

                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">
                                        Tổng tiền hàng: <span class="_autoNumeric">{{$shop->total_amount_items}}</span>đ

                                        <a href="{{ url('dat-coc?shop_id=' . $shop->shop_id)  }}" class="btn btn-danger text-uppercase">Đặt cọc</a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    @else

        <div class="row">

            <div class="col-sm-12">
                <div class="card ">
                    <div class="card-body">
                        <h4>Giỏ hàng hiện đang trống!</h4>

                        Click vào <a href="">đây</a> để được huớng dẫn đặt hàng một cách chi tiết nhất!
                    </div>
                </div>
            </div>

        </div>
    @endif

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

        });
        
        $(document).on('click', '._delete-shop', function(){

            var self = this;
            bootbox.confirm("Bạn có chắc muốn xoá shop này?", function(result){
                if(result){
                    var shop_id = $(self).data('shop-id');
                    $.ajax({
                        url: "{{ url('cart/shop') }}",
                        method: 'delete',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            shop_id:shop_id
                        },
                        success: function(response){
                            if(!response.success && response.message){
                                bootbox.alert({
                                    message: response.message,
                                    size: 'small'
                                });
                            }

                            if(response.success){
//                                $('._shop[data-shop-id="' + shop_id + '"]').remove();
//
//                                if(!$('._shop').length){
//                                    window.location.reload();
//                                }

                                window.location.reload();
                            }
                        },
                        error: function () {

                        }
                    })
                }
            });


        });

        $(document).on('click', '._delete-item', function(){

            var self = this;


            var shop_id = $(self).data('shop-id');
            var item_id = $(self).data('item-id');

            $.ajax({
                url: "{{ url('cart/item') }}",
                method: 'delete',
                data: {
                    "_token": "{{ csrf_token() }}",
                    shop_id:shop_id,
                    item_id:item_id
                },
                success: function(response){
                    if(!response.success && response.message){
                        bootbox.alert({
                            message: response.message,
                            size: 'small'
                        });
                    }

                    if(response.success){
//                        $('._shop-item[data-shop-id="' + shop_id + '"][data-shop-item-id="' + item_id + '"]').remove();
//
//                        if( !$('._shop-item[data-shop-id="' + shop_id + '"]').length ){
//                            $('._shop[data-shop-id="' + shop_id + '"]').remove();
//                        }

                        window.location.reload();

                    }
                },
                error: function () {

                }
            })


        });

        $(document).on('click', '._chk-service', function(){
            var shop_id = $(this).data('shop-id');
            var service = $(this).val();
            var checked = $(this).is(':checked');

            $.ajax({
                url: "{{ url('cart/shop/service') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    shop_id:shop_id,
                    service:service,
                    checked:checked ? 1 : 0
                },
                success: function(response){
//                    if(!response.success && response.message){
//                        bootbox.alert({
//                            message: response.message,
//                            size: 'small'
//                        });
//                    }

                    if(response.success){
//                        window.location.reload();
                    }else{
                        if(response.message){
                            bootbox.alert({
                                message: response.message,
                                size: 'small'
                            });
                        }
                    }

                },
                error: function () {

                }
            })
        });

        $(document).on('keypress', '._quantity', function(e){
            if(e.keyCode == 13){
                var shop_id = $(this).data('shop-id');
                var item_id = $(this).data('item-id');
                var quantity = $(this).val();

                $.ajax({
                    url: "{{ url('cart/quantity') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        shop_id:shop_id,
                        item_id:item_id,
                        quantity:quantity
                    },
                    success: function(response){
                        if(!response.success && response.message){
                            bootbox.alert({
                                message: response.message,
                                size: 'small'
                            });
                        }

                        if(response.success){
                            window.location.reload();
                        }
                    },
                    error: function () {

                    }
                })
            }


        });

        $(document).on('change', '._comment', function(){
            var shop_id = $(this).data('shop-id');
            var item_id = $(this).data('item-id');
            var comment = $(this).val();

            $.ajax({
                url: "{{ url('cart/item/comment') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    shop_id:shop_id,
                    item_id:item_id,
                    data: { comment:comment }
                },
                success: function(response){
                    if(!response.success && response.message){
                        bootbox.alert({
                            message: response.message,
                            size: 'small'
                        });
                    }

                    if(response.success){
//                        window.location.reload();
                    }
                },
                error: function () {

                }
            })
        });

        
    </script>
@endsection

