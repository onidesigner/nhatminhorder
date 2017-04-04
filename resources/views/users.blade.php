@extends('layouts.app')
{{--@extends('layouts.app_blank')--}}

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Nhân viên', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


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
                                                <a href="{{ url('user/detail', $user->id)  }}">{{$user->name}}</a>

                                                (<code>{{$user->code}}</code>)

                                                <br>
                                                <small>{{$user->email}}</small>
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

                            {{ $users->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

