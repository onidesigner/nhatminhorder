@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="step">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="step" class="active">
                        <a href="#step1" id="step1-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
                            <div class="icon fa fa-shopping-cart"></div>
                            <div class="heading">
                                <div class="title">Gio Hang</div>
                                <div class="description">Buoc 1</div>
                            </div>
                        </a>
                    </li>

                    <li role="step">
                        <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-credit-card"></div>
                            <div class="heading">
                                <div class="title">Dat coc & Thanh toan</div>
                                <div class="description">Buoc 2</div>
                            </div>
                        </a>
                    </li>
                    <li role="step" class="">
                        <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-truck"></div>
                            <div class="heading">
                                <div class="title">NM247 tiep nhan & xu ly</div>
                                <div class="description">Buoc 3</div>
                            </div>
                        </a>
                    </li>
                    <li role="step">
                        <a href="#step4" role="tab" id="step4-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-check"></div>
                            <div class="heading">
                                <div class="title">Nhan hang</div>
                                <div class="description">Buoc 4</div>
                            </div>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <strong>{{$data['statistic']['total_shops']}}</strong> shop / <strong>{{$data['statistic']['total_items']}}</strong> sản phẩm / <strong>{{$data['statistic']['total_amount']}}</strong>đ tiền hàng
        </div>
    </div>


    @foreach($data['shops'] as $shop)



    <div class="row _shop" data-shop-id="{{$shop->shop_id}}">
        <div class="col-xs-12">
            <div class="card ">
                <div class="card-header" style="position: relative">
                    <a href="javascript:void(0)" class="_delete-shop" data-shop-id="{{$shop->shop_id}}" data-toggle="tooltip" title="Xoa shop">
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
                                <th width="50%">San pham</th>
                                <th width="15%">Don gia</th>
                                <th width="15%" class="">SL</th>
                                <th width="15%">Tien hang</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($shop->items as $item)
                            <tr>
                                <td>
                                    <a class="_delete-item" data-shop-id="{{$shop->shop_id}}" data-item-id="{{$item->id}}" href="javascript:void(0)" data-toggle="tooltip" title="Xoa san pham">
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
                                    <input data-shop-id="{{$shop->shop_id}}" data-item-id="{{$item->id}}" placeholder="Ghi chu san pham..." style="width: 250px; padding: 0 5px;" type="text" class="_comment" value="{{$item->comment}}" />

                                </td>

                                <td>{{$item->price_vnd}}đ / ¥{{$item->price_promotion}}</td>
                                <td>
                                    <input data-shop-id="{{$shop->shop_id}}" data-item-id="{{$item->id}}" style="width: 80px" type="number" name="quantity" class="form-control text-center _quantity" value="{{$item->quantity}}" />
                                </td>
                                <td>{{$item->total_amount_item_vnd}}đ / ¥{{$item->total_amount_item}}</td>

                            </tr>

                            @endforeach
                            <tr>
                                <td class="text-right" colspan="5">
                                    Tong tien hang: {{$shop->total_amount_items}}đ + Phi tam tinh
                                    <a href="javascript:void(0)" title="" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Mua hang: 1000<br/>Kiem hang: 1000<br />Van chuyen TQ - VD: 1000<br/>Tong: 50000<br /><a href=''>>>>Bieu phi dich vu</a>"><i class="fa fa-question"></i></a>
                                    : {{$shop->fee_temp}}đ = Tong: {{$shop->total_amount_finish}}đ
                                    &nbsp;
                                    <a href="{{ url('dat-coc')  }}" class="btn btn-danger text-uppercase">Dat coc</a>
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

@endsection

@section('js_bottom')
@parent
    <script>
        $(document).ready(function(){

        });
        
        $(document).on('click', '._delete-shop', function(){

            var self = this;
            bootbox.confirm("Ban co chac muon xoa shop nay?", function(result){
                if(result){
//                    console.log(result);
//                    console.log($(self));
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
                        },
                        error: function () {

                        }
                    })
                }
            });


        });

        $(document).on('click', '._delete-item', function(){

            var self = this;
            bootbox.confirm("Ban co chac muon xoa san pham nay?", function(result) {
                if (result) {

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
                        },
                        error: function () {

                        }
                    })
                }
            });


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
                    if(!response.success && response.message){
                        bootbox.alert({
                            message: response.message,
                            size: 'small'
                        });
                    }


                },
                error: function () {

                }
            })
        });

        $(document).on('change', '._quantity', function(){
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
                },
                error: function () {

                }
            })
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
                },
                error: function () {

                }
            })
        });

        
    </script>
@endsection

