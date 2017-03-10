@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            <div class="card">

                <div class="card-body">
                    {{$page_title}}
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-xs-12">

            @include('partials/__comment', [
                'object_id' => $order_id,
                'object_type' => App\Comment::TYPE_OBJECT_ORDER,
                'scope' => App\Comment::TYPE_EXTERNAL
            ])
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

