@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    @include('partials/breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chu', 'link' => $app->make('url')->to('home')],
                                        ['name' => 'Nhan Vien', 'link' => $app->make('url')->to('nhan-vien')],
                                        ['name' => $page_title, 'link' => null],
                                    ]
                                ]
                            )

                    <div class="row">



                        <div class="col-md-4">

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

                                echo Form::open(array('url' => $app->make('url')->to('sua-nhan-vien/' . $user['id']) ));
                                echo Form::text('name', $user['name'], ['class' => 'form-control', 'placeholder' => 'Name', 'autofocus' => 'autofocus']);
                                echo Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']);
                                echo Form::text('email', $user['email'], ['class' => 'form-control', 'placeholder' => 'Email', 'disabled' => 'disabled']);
                                echo Form::select('section', $section_list, $user['section'], ['class' => 'select2 form-control']);
                                echo Form::select('status', $status_list, $user['status'], ['class' => 'select2 form-control']);
                                echo Form::submit('Luu', ['class' => 'btn btn-primary']);
                                echo Form::hidden('id', $user_id);
                                echo Form::close();

                            ?>

                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

