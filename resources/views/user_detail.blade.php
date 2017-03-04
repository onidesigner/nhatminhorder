@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">


                    <div class="row">
                        <div class="col-sm-6">
                            <h3>Thong tin nhan vien</h3>

                            Ho & ten: {{$user->name}}<br>

                            Ma: <code>{{$user->code}}</code><br>

                            @if($user->section == App\User::SECTION_CUSTOMER)

                                So du hien tai: {{$user->account_balance}}<br>

                            @endif

                            Email: {{$user->email}}<br>

                            Doi tuong: {{ App\User::getSectionName($user->section)  }}<br>

                            Trang thai: {{ App\User::getStatusName($user->status) }}<br>

                            Gia nhap luc: {{$user->created_at}}<br>

                            Cap nhat lan cuoi luc: {{$user->updated_at}}<br>

                            <a href="{{ url('sua-nhan-vien', $user->id)  }}">Sua</a>

                            <br>
                            <br>
                            <br>
                            <h3>Dien thoai</h3>

                            @if(!empty($user_mobiles))
                            <ul id="_list-user-phone">
                                @foreach($user_mobiles as $user_mobile)
                                    <li class="_row-user-phone">{{$user_mobile->mobile}} <a
                                                data-phone="{{$user_mobile->mobile}}"
                                                data-id="{{ $user_mobile->id }}" href="javascript:void(0)" class="_remove-user-phone">Xoa</a></li>
                                @endforeach
                            </ul>
                            @endif
                            <input type="text" class="_input-user-phone"> <button class="_add-user-phone">Them</button>

                        </div>

                        <div class="col-sm-12">
                            <h3>Lich su giao dich</h3>


                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Khach</th>
                                    <th>Ma GD</th>
                                    <th>Loai</th>
                                    <th>Trang thai</th>
                                    <th>Don hang</th>
                                    <th>Thoi gian</th>
                                    <th>Gia tri</th>
                                    <th>So du cuoi</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($transactions as $transaction)
                                    <?php
                                    $user = App\User::find($transaction->user_id);
                                    $order = App\Order::find($transaction->object_id);
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="">{{$transaction->id}}</a>
                                        </td>
                                        <td>
                                            <a href=""><strong>{{$user->email}}</strong></a><br>
                                            <small>{{$user->name}}</small>

                                            <code>{{$user->code}}</code>
                                        </td>
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
                                        <td>
                                            @if($transaction->object_type == App\UserTransaction::OBJECT_TYPE_ORDER)
                                                <a href="">{{$order->code}}</a>
                                            @endif
                                        </td>

                                        <td>{{$transaction->created_at}}</td>
                                        <td>
                                <span class="text-danger">
                                    {{$transaction->amount}} <sup>d</sup>
                                </span>
                                        </td>
                                        <td>
                                            <strong>
                                                {{$transaction->ending_balance}} <sup>d</sup>
                                            </strong>
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


    </div>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $(document).on('click', '._remove-user-phone', function(){
                 var user_phone = $(this).data('phone');
                 var user_phone_id = $(this).data('id');

                 var $that = $(this);

                $.ajax({
                    url: "{{ url('user/phone') }}",
                    method: 'put',
                    data: {
                        user_phone:user_phone,
                        user_phone_id:user_phone_id,
                        _token: "{{csrf_token()}}"
                    },
                    success:function(response) {
                        if(response.success){
                            $that.parents('._row-user-phone').remove();
                        }else{
                            bootbox.alert(response.message);
                        }
                    },
                    error: function(){


                    }
                });
            });

            $(document).on('click', '._add-user-phone', function(){
                var user_phone = $('._input-user-phone').val();

                $.ajax({
                  url: "{{ url('user/phone')  }}",
                  method: 'post',
                  data: {
                      user_phone:user_phone,
                      _token: "{{csrf_token()}}"
                  },
                  success:function(response) {
                    if(response.success){
                        window.location.reload();
                    }else{
                        $('._input-user-phone').focus();
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