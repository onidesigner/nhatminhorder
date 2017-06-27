@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="card">
                @include('partials/__breadcrumb',
                                                [
                                                    'urls' => [
                                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                                        ['name' => 'Chi tiết khiếu nại', 'link' => null],
                                                    ]
                                                ]
                                            )

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <table class="table-simple">
                                <tr>
                                    <th>Thông tin đơn hàng </th>
                                </tr>

                                <tr>
                                    {{--
                                    <td scope="row">{{ \App\Complaints::getOrderCode($data_complaint->order_id) }}</td>
                                    <td>{{$data_complaint->title}}</td>
                                    <td>{{App\Complaints::$alias_array[$data_complaint->status]}}</td>
                                    <td>
                                        {{ $data_complaint->description }}
                                    </td>
                                    <td>
                                        @foreach($data_complaint_file as $complaint_item)
                                            <img src="{{ asset($complaint_item->path) }}" width="90px" height="90px">
                                        @endforeach
                                    </td>--}}

                                    <td>Đơn hàng</td>
                                    <td>{{ \App\Complaints::getOrderCode($data_complaint->order_id) }}</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái</td>
                                    <td>Đã mua</td>
                                </tr>
                                <tr>
                                    <td>Kho nhận hàng</td>
                                    <td>VNHN</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái hàng</td>
                                    <td>Trong kho</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table-simple">
                                <tr>
                                    <th colspan="2">Thông tin khách hàng </th>
                                </tr>

                                <tr>
                                    <td>Mã khách</td>
                                    <td>{{ Auth::user()->code }}</td>
                                </tr>
                                <tr>
                                    <td>Nơi nhận hàng</td>
                                    <td>Số nhà 8 , ngõ số 3 , Lê trọng tấn thanh xuân hà nội</td>
                                </tr>
                                <tr>
                                    <td>SĐT</td>
                                    <td>01649647164</td>
                                </tr>

                                <tr>
                                    <td>Email</td>
                                    <td>nguyengiangdhxd@gmail.com</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!--phần khiếu nại-->


                    <div class="row">
                        <div class="col-md-12">
                            <table class="table-simple">
                                <tr>
                                    <th> </th>
                                </tr>

                                <tr>
                                    <td>Tên khiếu nại</td>
                                    <td>{{ $data_complaint->title }}</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái</td>
                                    <td>{{ \App\Complaints::$alias_array[$data_complaint->status] }} </td>
                                </tr>
                                <tr>
                                    <td>Nội dung</td>
                                    <td>
                                        The .table-responsive class creates a responsive table.
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12" id="anchor-box-comment">
            @include('partials/__comment', [
                'object_id' => $data_complaint->order_id,
                'object_type' => App\Comment::TYPE_OBJECT_COMPLAINT,
                 'scope_view' => App\Comment::TYPE_EXTERNAL

            ])

        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
@endsection