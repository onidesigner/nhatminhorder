@extends('layouts.app')
{{--@extends('layouts.app_blank')--}}

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Nhân viên', 'link' => null],
                                    ]
                                ]
                            )

                            <p>
                                Tìm thấy ({{ $total_users }}) nhân viên
                            </p>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Họ & tên</th>
                                        <th>Trạng thái</th>
                                        <th>Email</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if(!empty($users))
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>
                                                [{{ App\User::getSectionName($user->section)  }}]

                                                <a href="{{ url('user/detail', $user->id)  }}">{{$user->name}}</a>
                                            </td>
                                            <td>{{ App\User::getStatusName($user->status)  }}</td>
                                            <td>
                                                {{$user->email}}
                                            </td>
                                            <td>
                                                Gia nhập: {{ date('H:i d/m/Y', strtotime($user->created_at)) }}
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

