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
                            ['name' => $page_title, 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">
                    <h3>{{$page_title}}</h3>


                    <p>
                        Tìm thấy 3 phiếu
                    </p>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>TT</th>
                                <th>Mã</th>
                                <th>Tạo</th>
                                <th>Khách</th>
                                <th>Đơn</th>
                                <th>Kiện</th>
                                <th>Thu hộ</th>
                                <th>Ship nội dịa</th>
                                <th>In phiếu</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

