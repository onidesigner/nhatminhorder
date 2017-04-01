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


                            <form class="___form">

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
                                <input type="hidden" name="response" value="scan">

                                <input
                                        autofocus
                                        type="text"
                                        name="barcode"
                                        class="form-control ___input-action"
                                        data-key-global="barcode-scan-input"
                                        placeholder="Quét mã kiện">

                            </form>


                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <h3>Kết quả quét</h3>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>TT</th>
                                        <th>Mã quét</th>
                                        <th>Hành động</th>
                                        <th>Người quét</th>
                                        <th>Lúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($history_scan_list))
                                        @foreach($history_scan_list as $k => $v)
                                            <tr>
                                                <td>{{$k + 1}}</td>
                                                <td>
                                                    <code>{{$v['barcode']}}</code>
                                                </td>
                                                <td>{{ App\Scan::getActionName($v['action']) }} - {{$v['warehouse']}}</td>
                                                <td>{{ Auth::user()->email  }}</td>
                                                <td>{{ App\Util::formatDate($v['created_at'])  }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
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


        });

    </script>
@endsection

