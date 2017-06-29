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

                        <form method="get" action="{{ url('tat-ca-thong-bao')}}" >
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-3">

                                        <select class="selectpicker" name="status">
                                            <option value="0">Trạng thái</option>
                                            <option value="READ"
                                            
                                            >Đã đọc</option>
                                            <option value="UNREAD">Chưa đọc</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    </div>

                                </div>

                            </div>
                        </form>

                        <br>

                        <table class="table table-hover">
                            <tbody>
                            @foreach($data as $item_notification)
                                <tr>
                                    <td>{{$item_notification->title}}</td>
                                    <td>{{$item_notification->notification_content}}</td>
                                    <td>Đã đọc</td>
                                    <td>{{ App\Util::formatDate($item_notification->created_at) }}</td>
                                    <td>
                                        <a href="{{ \App\Http\Controllers\Customer\CustomerSystemNotificationController::buidLink($item_notification) }}" target="_blank">
                                            Chi tiết
                                        </a>
                                    </td>

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
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endsection

@section('js_bottom')
    @parent

    <script src="{{ asset('bower_components/lightbox2/dist/js/lightbox.js')  }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.selectpicker').selectpicker('refresh');
        });

    </script>
@endsection

