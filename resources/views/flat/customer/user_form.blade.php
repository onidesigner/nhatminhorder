@extends('flat/layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('page-header')
    @parent
    <div class="page-header">
        <div class="pull-left">
            <h1>{{@$page_title}}</h1>
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
                                        ['name' => 'Bảng chung', 'link' => url('home')],
                                        ['name' => 'Cá nhân', 'link' => url('nhan-vien', $user_id)],
                                        ['name' => 'Chỉnh sửa thông tin', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">



                    <div class="row-fluid">

                        <div class="span4">

                            @if (count($errors) > 0)
                                <div class = "alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <?php

                                $section_metadata = ['class' => 'select2 form-control'];
                                if($user['section'] == App\User::SECTION_CRANE):
                                    $section_metadata['disabled'] = 'disabled';
                                endif;

                                echo Form::open(array('url' => url( 'nhan-vien/sua/' . $user_id )));
                                echo Form::text('name', $user['name'], ['class' => 'form-control', 'placeholder' => 'Họ & tên', 'autofocus' => 'autofocus']);
                                echo Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mật khẩu']);
                                echo "<br/>";
                                echo Form::submit('Lưu', ['class' => 'btn btn-primary']);
                                echo Form::hidden('id', $user_id);
                                echo Form::close();

                            ?>

                        </div>
                        <div class="span6">

                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

