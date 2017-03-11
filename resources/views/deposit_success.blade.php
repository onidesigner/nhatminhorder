@extends('layouts.app')
{{--@extends('layouts.app_blank')--}}

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    {{--<div class="row">--}}
        {{--<div class="col-sm-3">--}}
            {{--<div class="app-messaging-container">--}}
                {{--<div class="app-messaging" id="collapseMessaging">--}}

                    {{--<div class="messaging">--}}
                        {{--<div class="footer">--}}
                            {{--<div class="message-box">--}}
                                {{--<textarea placeholder="type something..." class="form-control"></textarea>--}}
                                {{--<button class="btn btn-default"><i class="fa fa-paper-plane" aria-hidden="true"></i><span>Send</span></button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<ul class="chat">--}}
                            {{--<li class="line">--}}
                                {{--<div class="title">24 Jun 2016</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur eiusmod tempor incididunt</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="right">--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.46pm</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="line">--}}
                                {{--<div class="title">25 Jun 2016</div>--}}
                            {{--</li>--}}
                            {{--<li class="right">--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.46pm</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.45pm</div>--}}
                                    {{--<div class="status"><i class="fa fa-check" aria-hidden="true"></i> Read</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="right">--}}
                                {{--<div class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</div>--}}
                                {{--<div class="info">--}}
                                    {{--<div class="datetime">11.46pm</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                        {{--</ul>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}


    <div class="row">
        <div class="col-md-3">
            <div class="card">

                <div class="card-body">
                    <h3>{{$page_title}}</h3>

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
                                        <img style="float: left;
                                                margin-right: 10px;
                                                margin-top: 10px;" src="{{ $order->avatar  }}" width="50px" alt="">

                                        <h4>
                                            <a href="{{ url('order', $order->id)  }}" title="{{$order->code}}">{{$order->code}}</a>


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
    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

