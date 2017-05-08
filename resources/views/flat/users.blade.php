@extends('flat/layouts.app')
{{--@extends('layouts.app_blank')--}}

@section('page_title')
    {{$page_title}}
@endsection

@section('page-header')
    @parent
    <div class="page-header">
        <div class="pull-left">
            <h1>{{$page_title}}</h1>
        </div>

    </div>
@endsection

@section('content')

    <div class="row-fluid">
        <div class="span12">
            <div class="card">

                @include('flat/partials/breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Bảng chung', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Nhân viên', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">
                    <div class="row-fluid">
                        <div class="span12">



                            <form onchange="this.submit();" class="form-inline" method="get" action="{{ url('user')  }}">
                                <div class="span2">
                                    <input value="{{@$condition['code']}}" placeholder="Mã NV" autofocus type="text" name="code" class="form-control1">
                                </div>
                                <div class="span2">
                                    <input value="{{@$condition['email']}}" placeholder="Email" type="text" name="email" class="form-control1">
                                </div>

                                <div class="span2">
                                    <select class="form-control1" name="section" id="">
                                        <option value="">Đối tượng</option>
                                        @foreach(App\User::$section_list as $k => $v)
                                            <option

                                                    @if(isset($condition['section']) && $k == $condition['section'])
                                                        selected
                                                    @endif

                                                    value="{{$k}}">{{$v}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="span2">
                                    <select class="form-control1" name="status" id="">
                                        <option value="">Trạng thái</option>
                                        @foreach(App\User::$status_list as $k => $v)
                                            <option

                                                    @if(isset($condition['status']) && $k == $condition['status'])
                                                    selected
                                                    @endif

                                                    value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="span2">
                                    <button type="submit" class="btn btn-danger">Tìm kiếm</button>
                                </div>

                            </form>


                            <br>

                            <p>
                                Tìm thấy ({{ $total_users }}) nhân viên
                            </p>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Đối tượng</th>
                                        <th>Họ & tên</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                        <th>Số dư cuối</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if(!empty($users))
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{ App\User::getSectionName($user->section)  }}</td>
                                            <td>
                                                <a href="{{ url('user/detail', $user->id)  }}">{{$user->email}}</a>

                                                ({{$user->code}})

                                                <br>
                                                <small>{{$user->name}}</small>
                                                <br>

                                                @if($can_view_cart_customer)
                                                <small>
                                                    <a href="{{ url('gio-hang?hosivan_user_id=' . $user->id)  }}">Xem giỏ hàng</a>
                                                </small>
                                                @endif

                                                <br>
                                                <small>
                                                    <a href="{{ url('order?customer_code_email=' . $user->code)  }}">Xem đơn hàng</a>
                                                </small>
                                            </td>
                                            <td>{{ App\User::getStatusName($user->status)  }}</td>
                                            <td>
                                                Gia nhập: {{ date('H:i d/m/Y', strtotime($user->created_at)) }}
                                            </td>
                                            <td class="text-right">
                                                {{ App\Util::formatNumber($user->account_balance)  }} <sup>đ</sup>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>

                            {{--{{ $users->links() }}--}}

                            <div class="pagination">
                                {{ $users->appends(request()->input())->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

