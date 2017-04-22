@extends($layout)

@section('page_title')
    {{@$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('partials/__breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Trang chủ', 'link' => url('home')],
                            ['name' => 'Quét mã vạch', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">

                            <h3 class="cart-title">Quét mã vạch</h3>

                            <div class="stepwizard">
                                <div class="stepwizard-row">
                                    <div class="stepwizard-step" style="width: 20%">
                                        <button type="button" class="btn btn-default btn-circle">1</button>
                                        <p>Chọn kho</p>
                                    </div>
                                    <div class="stepwizard-step" style="width: 20%">
                                        <button type="button" class="btn btn-primary btn-circle">2</button>
                                        <p>Chọn hành động</p>
                                    </div>
                                    <div class="stepwizard-step" style="width: 60%">
                                        <button type="button" class="btn btn-primary btn-circle">3</button>
                                        <p>Quét mã</p>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3 col-xs-12">
                                        <h4>Kho nhận Trung Quốc</h4>

                                        @if(!empty($warehouse_list))
                                            @foreach($warehouse_list as $key => $val)

                                                @if($val['type'] == \App\WareHouse::TYPE_RECEIVE)
                                                    <p style="padding-left: 30px;">


                                                        <input name="warehouse" type="radio" value="{{$val['code']}}" id="{{$val['code']}}"> <label for="{{$val['code']}}">{{$val['name']}} ({{$val['code']}})</label>
                                                    </p>
                                                @endif



                                                {{--<option--}}
                                                {{--data-warehouse-type="{{$val['type']}}"--}}
                                                {{--value="{{$val['code']}}">{{$val['name']}} - {{$val['description']}}</option>--}}
                                            @endforeach
                                        @endif

                                    <h4>Kho phân phối Việt Nam</h4>


                                    @if(!empty($warehouse_list))
                                        @foreach($warehouse_list as $key => $val)

                                            @if($val['type'] == \App\WareHouse::TYPE_DISTRIBUTION)
                                                <p style="padding-left: 30px;">
                                                    <input name="warehouse"
                                                           type="radio"
                                                           value="{{$val['code']}}"
                                                           id="{{$val['code']}}">
                                                    <label for="{{$val['code']}}">{{$val['name']}} ({{$val['code']}})</label>
                                                </p>
                                            @endif

                                            {{--<option--}}
                                            {{--data-warehouse-type="{{$val['type']}}"--}}
                                            {{--value="{{$val['code']}}">{{$val['name']}} - {{$val['description']}}</option>--}}
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-sm-3 col-xs-12">

                                    <h4>&nbsp;</h4>

                                    @if(!empty($action_list))
                                        @foreach($action_list as $key => $val)
                                            <p>
                                                <input name="action"
                                                       type="radio"
                                                       id="{{$key}}"
                                                       value="{{$key}}">
                                                <label for="{{$key}}">{{$val}}</label>
                                            </p>

                                        @endforeach
                                    @endif


                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <input

                                            id="_scan-logistic-package-barcode"
                                            autofocus
                                            type="text"
                                            name="barcode"
                                            class="form-control _scan-barcode scan-barcode"
                                            data-key-global="barcode-scan-input"
                                            placeholder="Mã kiện">
                                </div>

                            </div>




                            {{--<form onsubmit="return false;" class="___form" id="_from-scan-barcode">--}}

                                {{--<select name="action" id="" class="form-control">--}}
                                    {{--@if(!empty($action_list))--}}
                                        {{--@foreach($action_list as $key => $val)--}}
                                            {{--<option value="{{$key}}">--}}
                                                {{--{{$val}}--}}
                                            {{--</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                {{--</select>--}}
                                {{--<br>--}}

                                {{--<select name="warehouse" id="" class="form-control">--}}
                                    {{--@if(!empty($warehouse_list))--}}
                                        {{--@foreach($warehouse_list as $key => $val)--}}
                                            {{--<option--}}
                                                    {{--data-warehouse-type="{{$val['type']}}"--}}
                                                    {{--value="{{$val['code']}}">{{$val['name']}} - {{$val['description']}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                {{--</select>--}}
                                {{--<br>--}}

                                {{--<input type="hidden" name="method" value="post">--}}
                                {{--<input type="hidden" name="url" value="{{ url('scan/action') }}">--}}
                                {{--<input type="hidden" name="_token" value="{{ csrf_token()  }}">--}}

                                {{--<input--}}
                                        {{--id="_scan-logistic-package-barcode"--}}
                                        {{--autofocus--}}
                                        {{--type="text"--}}
                                        {{--name="barcode"--}}
                                        {{--class="form-control _scan-barcode"--}}
                                        {{--data-key-global="barcode-scan-input"--}}
                                        {{--placeholder="Quét mã kiện">--}}

                            {{--</form>--}}


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('css_bottom')
    @parent
    <style>
        .scan-barcode{
            border-left: 0!important;
            border-right: 0!important;;
            border-top: 0!important;;
            border-bottom: 2px solid #29c75f!important;;
        }
        .scan-barcode:focus{
            box-shadow: none!important;
        }

        .stepwizard-step{
            /*width: 25%;*/
        }
        .well{
            padding: 0 19px;
        }
    </style>
@endsection

@section('js_bottom')
    @parent
    <script src="{{asset('js/notify.min.js')}}"></script>
    <script src="{{asset('js/ion.sound.min.js')}}"></script>

    <script>
        $(document).ready(function(){

            // init bunch of sounds
            ion.sound({
                sounds: [
                    {name: "success"},
                    {name: "error"}
                ],

                // main config
                path: "{{ asset('sounds')  }}/",
                preload: true,
                multiplay: true,
                volume: 0.9
            });

            $(document).on('keypress', '._scan-barcode', function(e){
                var that = this;
                if(e.keyCode == 13){
                    var barcode = $(this).val();
                    if(!barcode) return false;

                    request("{{ url('scan/action') }}", "post", $('#_from-scan-barcode').serializeObject()).done(function(response){
                        var msg_type = 'success';
                        if(response.success){
                            ion.sound.play("success");
                        }else{
                            msg_type = 'error';
                            ion.sound.play("error");
                        }

                        if(response.message){
                            $.notify(response.message, msg_type);
                        }
                        $(that).val('').focus();
                    });
                }
            });

//            $(document).on('change', 'select[name="warehouse"]', function(){
//                var warehouse_type = $(this).data('warehouse-type');
//                Action.chooseWarehouse(warehouse_type);
//            });

        });

        {{--var Action = {--}}
            {{--chooseWarehouse: function (warehouse_type) {--}}
                {{--var $dom = $('select[name="action"]');--}}
                {{--$dom.find('option').remove();--}}
                {{--if(warehouse_type == "{{ App\WareHouse::TYPE_DISTRIBUTION  }}"){--}}
                    {{--$dom.append('<option value="IN">Nhập</option>');--}}
                    {{--$dom.append('<option value="OUT">Xuất</option>');--}}
                {{--}else if(warehouse_type == "{{ App\WareHouse::TYPE_RECEIVE  }}"){--}}
                    {{--$dom.append('<option value="OUT">Xuất</option>');--}}
                {{--}--}}
            {{--}--}}
        {{--};--}}

        {{--Action.chooseWarehouse("{{ App\WareHouse::TYPE_RECEIVE  }}");--}}

    </script>
@endsection

