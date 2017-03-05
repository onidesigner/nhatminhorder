@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="step">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="step" class="">
                        <a href="#step1" id="step1-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
                            <div class="icon fa fa-shopping-cart"></div>
                            <div class="heading">
                                <div class="title">Gio Hang</div>
                                <div class="description">Buoc 1</div>
                            </div>
                        </a>
                    </li>

                    <li role="step" class="active">
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Dia chi nhan hang
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">

                            @foreach($user_address as $user_address_item)

                                <?php

                                $province = App\Location::find($user_address_item->province_id)->label;
                                $district = App\Location::find($user_address_item->district_id)->label;

                                ?>

                            <div class="media _user-address"
                                 data-is-default="{{ $user_address_item->is_default }}"
                                 data-id="{{$user_address_item->id}}">
                                @if($user_address_item->is_default)
                                    <div class="media-left">
                                        <span class="label label-danger">MAC DINH</span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{$user_address_item->reciver_name}} / {{$user_address_item->reciver_phone}}</h4>
                                        <p>{{$user_address_item->detail}}, {{$district}}, {{$province}}</p>

                                        <p>
                                            <a href="javascript:void(0)" data-id="{{$user_address_item->id}}" data-json="{{ json_encode($user_address_item)  }}" class="_btn-action-edit">Sua</a> |
                                            <a href="javascript:void(0)" data-id="{{$user_address_item->id}}" class="_btn-action-delete">Xoa</a>
                                        </p>
                                    </div>
                                @else

                                    <div class="media-body">
                                        <h4 class="media-heading">{{$user_address_item->reciver_name}} / {{$user_address_item->reciver_phone}}</h4>
                                        <p>{{$user_address_item->detail}}, {{$district}}, {{$province}}</p>

                                        <p>
                                            <a href="javascript:void(0)" data-id="{{$user_address_item->id}}" data-json="{{ json_encode($user_address_item)  }}" class="_btn-action-edit">Sua</a> |
                                            <a href="javascript:void(0)" data-id="{{$user_address_item->id}}" class="_btn-action-delete">Xoa</a> |
                                            <a href="javascript:void(0)" data-id="{{$user_address_item->id}}" class="_btn-action-set-default">Dat mac dinh</a>
                                        </p>
                                    </div>
                                @endif


                            </div>

                            @endforeach

                            <a class="btn btn-primary" id="_add-user-address">Them dia chi nhan hang</a>

                            <div class="modal fade" id="modal-id">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Cap nhat dia chi nhan hang</h4>
                                        </div>
                                        <div class="modal-body">

                                            <form id="_form-update-user-address" action="{{ url('user/address')  }}" method="post">

                                            <div style="margin-bottom: 15px;">
                                                <select required id="province_id" autofocus name="province_id" class="_autofocus form-control" id="">
                                                    <option value="">Tinh/Thanh pho</option>
                                                    @foreach($all_provinces as $province)
                                                        <option value="{{$province->id}}">{{$province->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div style="margin-bottom: 15px;">
                                                <select required id="district_id" name="district_id" class="form-control" id="">
                                                    <option value="">Quan/Huyen</option>
                                                    @foreach($all_districts as $district)
                                                        <option class="hidden" data-province-id="{{ $district->parent_id  }}" value="{{$district->id}}">{{$district->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input required type="text" name="detail" class="form-control" placeholder="Dia chi">

                                            <input required type="text" id="reciver_name" name="reciver_name" class="form-control" placeholder="Ten nguoi nhan">

                                            <input required type="text" name="reciver_phone" class="form-control" placeholder="Dien thoai">

                                            <textarea name="note" rows="3" class="form-control" placeholder="Ghi chu"></textarea>

                                            <input type="hidden" name="user_address_id" id="user_address_id" value="0">

                                            </form>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="_btn-update-user-address">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <h3>Danh sách shop kết đơn ({{count($shops)}})</h3>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Shop</th>
                                    <th>SL / Link</th>
                                    <th>Tien hang</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($shops))

                                    @foreach($shops as $shop)
                                    <tr class="_shop-item" data-json="{{ json_encode($shop)  }}">
                                        <td>
                                        <?php
                                            $avatar = urldecode($shop->avatar);
                                        ?>
                                            <img style="width: 50px;
    margin-right: 15px; float: left;" src="{{ $avatar }}" alt="">
                                            <div>
                                                <span class="text-uppercase">[{{$shop->site}}]</span> <strong>{{$shop->shop_name}}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $shop->total_quantity  }} / {{ $shop->total_link  }}</td>
                                        <td>{{ $shop->total_amount  }} <sup>d</sup></td>
                                    </tr>

                                    @endforeach
                                @endif


                                </tbody>
                            </table>

                            <ul>
                                <li>
                                    Tong tien hang: {{$total_amount_shop}} <sup>d</sup>
                                </li>
                                <li>
                                    Dat coc ({{$deposit_percent}}%): {{$deposit_amount}} <sup>d</sup>
                                </li>
                                <li>
                                    So du hien tai: {{ Auth::user()->account_balance  }} <sup>d</sup>
                                </li>
                            </ul>


                            @if(Auth::user()->account_balance >= $deposit_amount)

                                <br>
                                <br>


                                <form action="">
                                    <div class="col-sm-6 col-xs-12"><input placeholder="Nhap mat khau de dat coc" type="password" class="form-control _input-password" autofocus></div>
                                    <br>
                                    <div class="col-sm-12">
                                        <input type="button" class="btn btn-danger btn-sm _action-deposit" value="DAT COC">
                                    </div>

                                </form>

                            @else

                                <p class="text-danger">
                                    Hien so tien trong tai khoan khong du de dat coc. <br>
                                    Hien ban con thieu {{  abs(Auth::user()->account_balance - $deposit_amount) }} <sup>d</sup> <br>
                                    Vui long nap tien vao tai khoan de tien hanh dat coc don. <br>
                                    Can giup do, lien he hotline 1988 02344. Xin cam on
                                </p>

                            @endif



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $(document).on('click', '._action-deposit', function () {
                 var $that = $(this);



                 var password = $('._input-password').val();

                 var address_id = $('._user-address[data-is-default=1]').data('id');

                 if(!$('._user-address').length){
                     bootbox.alert('Hien tai ban chua co dia chi nhan hang!');
                     return false;
                 }

                 if(!address_id){
                     bootbox.alert('Vui long thiet lap 1 dia chi nhan hang lam mac dinh!');
                     return false;
                 }

                $that.prop('disabled', true);

                 $.ajax({
                   url: "{{ url('cart/deposit')  }}",
                   method: 'post',
                   data: {
                       password: password,
                       shop_id: nhatminh247.shop_id,
                       address_id: address_id,
                       _token: "{{csrf_token()}}"
                   },
                   success:function(response) {
                       if(!response.success){

                           $that.prop('disabled', false);
                           bootbox.alert(response.message);
                           $('._input-password').focus();
                       }else{
                           bootbox.alert(response.message);
                       }

                   },
                   error: function(){
                       $that.prop('disabled', false);
                   }
                 });
            });

            $(document).on("change", "#province_id", function(event){
                var province_id = $(this).val();
                $('#district_id option:first').prop('selected', true);
                $('#district_id option:not(:first)').prop('selected', false);
                showDistrictByProvince(province_id);
            });

            function showDistrictByProvince(province_id){
                $('#district_id option:first').removeClass('hidden');
                $('#district_id option:not(:first)').addClass('hidden');

                if(province_id){
                    $('#district_id option[data-province-id=' + province_id + ']').removeClass('hidden');
                }
                $('#district_id').trigger('change');
            }

            $(document).on('click', '._btn-action-set-default', function () {
                var user_address_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('user/address/default')  }}",
                    method: 'put',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: user_address_id
                    },
                    success: function(response){
                        if(response.success){
                            window.location.reload();
                        }
                    },
                    error: function () {

                    }
                });
            });

            $(document).on('click', '._btn-action-delete', function () {
                var user_address_id = $(this).data('id');
                bootbox.confirm("Ban co chac muon xoa dia chi nay?", function(result){
                    if(result){
                        $.ajax({
                            url: "{{ url('user/address/delete')  }}",
                            method: 'put',
                            data: {
                                _token: "{{ csrf_token() }}",
                                action: 'delete',
                                id: user_address_id
                            },
                            success: function(response){
                                if(response.success){
                                    window.location.reload();
                                }
                            },
                            error: function () {

                            }
                        });
                    }
                });
            });

            $(document).on('click', '._btn-action-edit', function(event){
                var id = $(this).data('id');
                var data_json = $(this).data('json');

                $('#_form-update-user-address').setFormData(data_json);

                showDistrictByProvince(data_json.province_id);

                $('#user_address_id').val(id);
                $('#modal-id').modal('show');
            });

            $(document).on('click', '#_add-user-address', function(event){

                $('#_form-update-user-address').setFormData({
                    province_id:'',
                    district_id:'',
                    note:'',
                    reciver_phone:'',
                    reciver_name:'',
                    detail:''
                });

                showDistrictByProvince(0);

                $('#user_address_id').val(0);
                $('#modal-id').modal('show');
            });

            $(document).on('click', '#_btn-update-user-address', function () {

                var data = $('#_form-update-user-address').serializeObject();
                    data._token = "{{ csrf_token() }}";

                $.ajax({
                    url: "{{ url('user/address')  }}",
                    method: 'post',
                    data:data,
                    success: function(response){
                        if(response.success){
                            window.location.reload();
                        }else{
                            bootbox.alert(response.message);
                        }
                    },
                    error: function () {

                    }
                });
            });

        });

    </script>
@endsection

