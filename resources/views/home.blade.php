@extends('layouts.app')

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
                                                        ['name' => 'Trang chủ', 'link' => null],
                                                    ]
                                                ]
                                            )

                <div class="card-body">
                    <h3>Bảng điều khiển!</h3>
                </div>
            </div>

        </div>
    </div>

@endsection
