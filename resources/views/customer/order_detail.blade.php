@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Đơn hàng', 'link' => url('don-hang')],
                                        ['name' => 'Đơn ' . $order->code, 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">
                    customer order detail

                    @if($can_cancel_order)
                        <button type="button" class="btn btn-danger _btn-action" data-action="cancel_order" data-status="{{ App\Order::STATUS_BOUGHT  }}">HỦY ĐƠN</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $(document).on('click', '._btn-action', function(){

                var action = $(this).data('action');

                var $that = $(this);

                if($that.hasClass('disabled')) return false;

                $that.addClass('disabled');

                $.ajax({
                    url: "{{ url('don-hang/' .$order_id. '/hanh-dong')  }}",
                    method: 'post',
                    data: {
                        action:action,
                        _token: "{{ csrf_token() }}",
                    },
                    success:function(response) {
                        if(response.success){
//                            window.location.reload();
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

        });

    </script>
@endsection

