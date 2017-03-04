@extends('layouts.app')

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
                                        ['name' => 'Trang chu', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Nhan Vien', 'link' => null],
                                    ]
                                ]
                            )
                            <p>
                                Tim thay ({{$users['total']}}) nhan vien
                            </p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ & tên</th>
                                    <th>Trạng thái</th>
                                    <th>Email</th>
                                    <th>Thời gian</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($users['data']))
                                    @foreach($users['data'] as $key => $user)
                                        <tr>
                                            <td>{{$user['id']}}</td>
                                            <td>
                                                [{{ App\User::getSectionName($user['section'])  }}]

                                                <a href="{{ url('nhan-vien', $user['id'])  }}">{{$user['name']}}</a>
                                            </td>
                                            <td>{{ App\User::getStatusName($user['status'])  }}</td>
                                            <td>
                                                {{$user['email']}}
                                            </td>

                                            <td>
                                                Gia nhập: {{ date('H:i d/m/Y', strtotime($user['created_at'])) }}
                                            </td>
                                            <td><a href="{{$app->make('url')->to('sua-nhan-vien/' . $user['id'])}}">Sửa</a></td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>

                            <nav>
                                <ul class="pagination">
                                    @if(!empty($users['prev_page_url']))
                                        <li>
                                            <a href="{{$users['prev_page_url']}}" aria-label="Previous">
                                                <span aria-hidden="true">« Trang truoc</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if(!empty($users['next_page_url']))
                                        <li>
                                            <a href="{{$users['next_page_url']}}" aria-label="Next">
                                                <span aria-hidden="true">Trang sau »</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

