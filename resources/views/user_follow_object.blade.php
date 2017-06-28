@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12 col-xs-12">

            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Đơn hàng đang theo dõi', ''],

                                    ]
                                ]
                            )

                <div class="card-body">

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>

                            <th>Đơn hàng</th>
                            <th>Theo Dõi/Bỏ</th>
                            <th>Thời gian tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($data))
                            @foreach($data as $item)

                                <tr>
                                    <td>{{ \App\Order::findOneByIdOrCode($item->object_id)->code  }}</td>
                                    <td>
                                        <select class="selectpicker _change_follow" name="status_complaint" data-order-id="{{$item->object_id}}">

                                                <option value="FOLLOW"
                                                 <?php
                                                         if($item->status == 'ACTIVE'){
                                                             echo 'selected';
                                                         }
                                                         ?>

                                                >Theo dõi</option>

                                                <option value="UNFOLLOW"
                                                <?php
                                                        if($item->status == 'INACTIVE'){
                                                            echo 'selected';
                                                        }
                                                        ?>
                                                >Bỏ theo dõi</option>

                                        </select>
                                    </td>
                                    <td>{{ \App\Util::formatDate($item->created_at) }}</td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_bottom')
            <script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
            <script>
                $( document ).ready(function() {

                    /**
                     * đổi trạng thái đơn sang đã đọc
                     */


                    $(document).on("change","._change_follow",function() {
                        var order_id = $(this).data('order-id');
                        $.ajax({
                            url: "{{ url('/bo-theo-doi') }}",
                            type: 'GET',
                            data: {
                                order_id : order_id
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.type == 'true') {

                                }
                            }
                        });
                    });


                });

            </script>
@endsection



