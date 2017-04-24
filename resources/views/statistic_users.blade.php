@extends('layouts.app')

@section('page_title')
    {{@$page_title}}
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
                    <h3 class="card-title">{{@$page_title}}</h3>

                    <p>Tìm thấy {{$total_users}} khách hàng</p>

                    @if($total_users > 0)

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Nạp tiền</th>
                                    <th>Tiền hàng(1)</th>
                                    <th>Đặt cọc(2)</th>
                                    <th>Còn thiếu(3=1-2)</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <h5>{{$user->email}} </h5>
                                        <p>{{$user->code}}</p>

                                        <p>
                                            <small>{{$user->name}}</small>
                                        </p>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    @else
                        <p>Hiện chưa có khách hàng nào!</p>
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


        });

    </script>
@endsection

