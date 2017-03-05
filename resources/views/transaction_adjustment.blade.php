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
                        <div class="col-sm-4">
                            <h3>{{$page_title}}</h3>

                            <br>
                            <form id="_form-transaction-adjustment">
                                <fieldset>

                                    <select id="transaction_type" class="_form-control select2" autofocus name="transaction_type">
                                        <option value="">Loai giao dich</option>
                                        @foreach(App\UserTransaction::$transaction_adjustment as $k => $v)
                                            <option value="{{ $k }}">{{$v}}</option>
                                        @endforeach
                                    </select>

                                    <div class="_form-item _form-item-sub ADJUSTMENT GIFT hidden">

                                        <select class="_form-control select2"  name="user_id" id="">
                                            <option value="">Khach hang</option>
                                            @if(!empty($users_customer))
                                                @foreach($users_customer as $users_customer_item)
                                                    <option value="{{$users_customer_item['id']}}">{{$users_customer_item['email']}} - {{$users_customer_item['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>


                                    <div class="_form-item _form-item-sub PAYMENT REFUND ORDER DEFAULT_SUB hidden">
                                        <select class="_form-control select2"  name="object_type" id="transaction_adjustment_object">
                                            <option value="">Chon doi tuong</option>

                                                @foreach(App\UserTransaction::$transaction_adjustment_object as $kk1 => $vv1)
                                                    <option value="{{$kk1}}">{{$vv1}}</option>
                                                @endforeach

                                        </select>
                                    </div>

                                    <input type="text" name="order_code" class="form-control _form-item _form-item-sub ORDER hidden" placeholder="Ma don">

                                    <div class="select-transaction-adjustment-type _form-item _form-item-sub ADJUSTMENT hidden">

                                        <select class="_form-control select2"  name="transaction_adjustment_type" id="">
                                            <option value="">Loai dieu chinh</option>
                                            @foreach(App\UserTransaction::$transaction_adjustment_type as $kk => $vv)
                                                <option value="{{$kk}}">{{$vv}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <input type="text" class="form-control _autoNumeric _form-item _form-item-sub GIFT REFUND PAYMENT ORDER DEFAULT_SUB ADJUSTMENT hidden DEFAULT" name="amount" placeholder="So tien">

                                    <textarea name="transaction_note" placeholder="Ly do" class="form-control _form-item _form-item-sub GIFT REFUND PAYMENT ORDER DEFAULT_SUB ADJUSTMENT hidden DEFAULT"></textarea>

                                    <button class="btn btn-danger" id="_save-transaction-adjustment">TAO DIEU CHINH</button>
                                    {{ csrf_field()  }}
                                </fieldset>
                            </form>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

@endsection

@section('css_bottom')
<style>
    ._form-item span.select2{
        width: 100%!important;
    }
</style>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $('._form-item.DEFAULT').removeClass('hidden');

            $(document).on('change', '#transaction_type', function(event){
                var selected_value = $(this).val();

                var classType = 'DEFAULT';
                if(selected_value){
                    classType = selected_value;
                }
                $('._form-item').addClass('hidden');
                $('._form-item.' + classType).removeClass('hidden');

                $('#transaction_adjustment_object option:first').prop('selected', true);
                $('#transaction_adjustment_object').select2();
            });

            $(document).on('click', '#_save-transaction-adjustment', function(event){
                 var $that = $(this);

                 $(this).prop('disabled', true);

                 var data = $('#_form-transaction-adjustment').serializeObject();
                    data.amount = $('[name="amount"]').autoNumeric('get');
                 $.ajax({
                   url: "{{ url('transaction/adjustment')  }}",
                   method: 'post',
                   data: data,
                   success:function(response) {
                       bootbox.alert(response.message);
                       $that.prop('disabled', false);
                   },
                   error: function(){
                       $that.prop('disabled', false);
                   }
                 });
            });

            $(document).on('change', '#transaction_adjustment_object', function(event){
                var selected_value = $(this).val();
                changeViewSub(selected_value);
            });

            function changeViewSub(selected_value){
                var classType = 'DEFAULT_SUB';
                if(selected_value){
                    classType = selected_value;
                }

                $('._form-item-sub').addClass('hidden');
                $('._form-item-sub.' + classType).removeClass('hidden');
            }

        });

    </script>
@endsection

