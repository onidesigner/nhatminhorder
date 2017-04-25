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
                                                        ['name' => 'Thông báo', 'link' => null],
                                                    ]
                                                ]
                                            )

                <div class="card-body">

                    <table class="table table-hover ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nội dung</th>
                            <th>Thời gian</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($data))
                            <?php $i = 1; ?>
                            @foreach($data as $notification)

                                <tr class="
                                    <?php
                                if(in_array($notification->is_view, [\App\CustomerNotification::CUSTOMER_NOTIFICATION_VIEW,\App\CustomerNotification::CUSTOMER_NOTIFICATION_READ])){
                                    echo "_unread";
                                }
                                ?>

                                        "

                                >
                                    <th scope="row" class="_isread" data-value="{{$notification->id}}"><a target="_blank" href="{{ url('order') }}/{{$notification->order_id}}">{{ $per_page * $page + $i++ }}</a></th>
                                    <td class="_isread" data-value="{{$notification->id}}"><a target="_blank" href="{{ url('order') }}/{{$notification->order_id}}">{{ $notification->notification_content }}</a></td>
                                    <td class="_isread" data-value="{{$notification->id}}"><a target="_blank" href="{{ url('order') }}/{{$notification->order_id}}">{{ $notification->created_time }}</a> </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(!empty($data))
                        {{ $data->links() }}
                    @else
                        <h3 align="center">Chưa có thông báo mới !</h3>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
@section('css_bottom')
    <style>
        ._unread{
            background-color: #ddd
        }
    </style>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){
            $("._isread").click(function () {
                $(this).parent().removeClass("_unread");
                $.ajax({
                    url : '/change-type-notification',
                    type :'GET',
                    data : {
                        notification_id : $(this).data('value')
                    }
                }).done(function (response) {
                    if(response.type == 'error'){
                        alert('Có lỗi !');
                    }
                })
            })

        });

    </script>
@endsection

