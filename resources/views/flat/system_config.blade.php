@extends('flat/layouts.app')

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
                            ['name' => 'Bảng chung', 'link' => url('home')],
                            ['name' => 'Cấu hình chung', 'link' => null],
                        ]
                    ]
                )

                <div class="box">
                    <div class="box-title">
                        <h3><i class="icon-list"></i> Cấu hình hệ thống</h3>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" action="{{ url('setting')  }}" method="post">
                            @foreach($data as $key => $data_item)
                            <div class="control-group">
                                <label for="textfield" class="control-label">{{$data_item['field_name']}}</label>
                                <div class="controls">
                                    {{--<input type="text" name="textfield" id="textfield" class="input-xlarge mask_date">--}}

                                    @if(@$data_item['tag_name'] == '' || @$data_item['tag_name'] == 'input')
                                        <input type="text"
                                               name="{{$data_item['key']}}"
                                               class="form-control input-xlarge"
                                               placeholder=""
                                               value="{{ @$data_inserted[$data_item['key']]  }}">
                                    @elseif(@$data_item['tag_name'] == 'textarea')
                                        <textarea placeholder=""
                                                  class="form-control input-xlarge"
                                                  name="{{$data_item['key']}}"
                                                  id=""
                                                  cols="30"
                                                  rows="10">{{ @$data_inserted[$data_item['key']]  }}</textarea>

                                    @elseif(@$data_item['tag_name'] == 'select')
                                        <select
                                                name="{{$data_item['key']}}" id="" class="form-control chosen-select">

                                            @foreach($data_item['options'] as $key => $value)
                                                <option
                                                        @if(!empty($data_inserted[$data_item['key']])
                                                        && $key == $data_inserted[$data_item['key']]) selected @endif
                                                value="{{$key}}">{{$value}}</option>
                                            @endforeach


                                        </select>
                                        <br>

                                    @endif

                                    {{--<span class="help-block">Format: 9999/99/99</span>--}}
                                </div>
                            </div>




                            @endforeach
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">LƯU</button>
                            </div>

                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('css_bottom')
    @parent

    <!-- Nprogress CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('flat/css/plugins/chosen/chosen.css') }}">
@endsection

@section('js_bottom')
    @parent
    <!-- Chosen JS -->
    <script type="text/javascript" src="{{ asset('flat/js/plugins/chosen/chosen.jquery.min.js')  }}"></script>
    <script>
        $(document).ready(function(){


            $(".chosen-select").chosen({width: "100%"});
        });

    </script>
@endsection

