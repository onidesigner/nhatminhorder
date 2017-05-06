@extends('flat/layouts.app')
{{--@extends('layouts.app_blank')--}}

@section('page_title')
    {{$page_title}}
@endsection

@section('page-header')
    @parent
    <div class="page-header">
        <div class="pull-left">
            <h1>{!! @$page_title !!}</h1>
        </div>

    </div>
@endsection

@section('content')

    <div class="row-fluid">
        <div class="span12">
            @include('flat/partials/cart-step', ['active' => 3])
        </div>
    </div>

    <div class="row-fluid">
        <div class="span6">
            <div class="card">

                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>

                        @if(count($orders))

                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <img class="lazy" style="float: left;
                                                margin-right: 10px;
                                                margin-top: 10px;" data-src="{{ $order->avatar  }}" width="50px" alt="">

                                        <h4>
                                            <a href="{{ url('don-hang', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>


                                        </h4>
                                        <p>
                                            Đã đặt cọc
                                        </p>
                                    </td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent

    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
    <script>
        $(function() {
            $('.lazy').lazy();
        });
    </script>

    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

