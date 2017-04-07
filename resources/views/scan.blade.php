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
                        <div class="col-sm-4 col-xs-12">
                            <h3>Quét mã vạch</h3>


                            <form onsubmit="return false;" class="___form" id="_from-scan-barcode">

                                <select name="action" id="" class="form-control">
                                    @if(!empty($action_list))
                                        @foreach($action_list as $key => $val)
                                            <option value="{{$key}}">{{$val}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <br>

                                <select name="warehouse" id="" class="form-control">
                                    @if(!empty($warehouse_list))
                                        @foreach($warehouse_list as $key => $val)
                                            <option value="{{$val['code']}}">{{$val['name']}} - {{$val['description']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <br>

                                <input type="hidden" name="method" value="post">
                                <input type="hidden" name="url" value="{{ url('scan/action') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token()  }}">

                                <input
                                        autofocus
                                        type="text"
                                        name="barcode"
                                        class="form-control _scan-barcode"
                                        data-key-global="barcode-scan-input"
                                        placeholder="Quét mã kiện">

                            </form>


                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <h3>Kết quả quét</h3>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){
            $(document).on('keypress', '._scan-barcode', function(e){
                var that = this;
                if(e.keyCode == 13){
                    var barcode = $(this).val();
                    if(!barcode) return false;

                    request("{{ url('scan/action') }}", "post", $('#_from-scan-barcode').serializeObject()).done(function(response){
                         if(response.success){
                             $(that).val('').focus();
                         }else{
                             if(response.message){
                                 bootbox.alert(response.message);
                             }
                         }
                    });
                }
            });
        });

    </script>
@endsection

