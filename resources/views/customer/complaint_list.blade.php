@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                @include('partials/__breadcrumb',
                                                [
                                                    'urls' => [
                                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                                        ['name' => 'Danh sách khiếu nại', 'link' => null],
                                                    ]
                                                ]
                                            )

                <div class="card-body">
                    <div class="row">
                        <h4>Danh sách khiếu nại</h4>

                        <form method="get" action="{{ url('danh-sach-khieu-nai')}}" >
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value="{{ @request()->get('ordercode') }}" name="ordercode" placeholder="Nhập mã đơn hàng">
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="selectpicker" name="status_complaint">
                                            <option value="0">Trạng thái của KN</option>
                                            @foreach($complaint_status as $key => $val)
                                                <option value="{{$key}}"
                                                        @if($key == @request()->get('status_complaint'))
                                                        selected
                                                        @endif
                                                >
                                                    {{$val}}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    </div>

                                </div>

                            </div>
                        </form>

                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>

                                <th>Khiếu nại</th>
                                <th>Mã đơn</th>
                                <th>Trạng thái</th>
                                <th>Thời gian tạo</th>
                                <th>Chi tiết</th>
                            </tr>
                            </thead>

                            @if(!empty($data))
                                <tbody>
                                @foreach($data as $complaint)

                                    <tr>
                                        <td>{{ $complaint->title }}</td>
                                        <td>{{ App\Complaints::getOrderCode($complaint->order_id) }}</td>
                                        <td>{{ App\Complaints::$alias_array[$complaint->status] }}</td>
                                        <td>{{ $complaint->created_time }}</td>
                                        <td> <a target="_blank" href="/chi-tiet-khieu-nai/{{$complaint->id}}">chi tiết <i class="fa fa-angle-right"></i></a></td>
                                    </tr>

                                @endforeach
                                </tbody>
                            @else
                                <h3 align="center">Không tồn tại khiếu nại</h3>
                            @endif
                        </table>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>


    @if(!empty($data))

    @else
        <h3 align="center">Bạn chưa có khiếu nại !</h3>
    @endif



@endsection
@section('css_bottom')
    @parent
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endsection
@section('js_bottom')
    @parent
    <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker('refresh');
        });
    </script>
@endsection



