@extends('layouts.app')

@section('page_title')
    {{@$page_title}}
@endsection

@section('content')
    <div id="order-detail-page" class="row">
        <div class="col-sm-12 col-xs-12">

            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Đơn hàng', 'link' => url('don-hang')],

                                    ]
                                ]
                            )

                <div class="card-body">
                    <div role="tabpanel">
                        <h4><strong>Thông báo của bạn</strong></h4>
                        <table class="table table-hover">
                            <tbody>
                            @foreach($data as $item_notification)
                                <tr>
                                    <td>{{$item_notification->title}}</td>
                                    <td>{{$item_notification->notification_content}}</td>
                                    <td>{{ App\Util::formatDate($item_notification->created_at) }}</td>
                                </tr>
                            @endforeach
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
    </div>
@endsection

@section('css_bottom')
    @parent

    <link rel="stylesheet" href="{{ asset('bower_components/lightbox2/dist/css/lightbox.css')  }}">
@endsection

@section('js_bottom')
    @parent

    <script src="{{ asset('bower_components/lightbox2/dist/js/lightbox.js')  }}"></script>
    <script>
        $(document).ready(function(){
            //todo
        });

    </script>
@endsection

