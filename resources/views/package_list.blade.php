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
                            ['name' => 'Kiện hàng', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">
                    <h3 class="cart-title">Danh sách kiện hàng</h3>

                    @if($can_create_package)
                        <a href="{{ url('package')  }}" class="btn btn-danger pull-right">Tạo kiện</a>
                    @endif

                    <p>Tìm thấy {{$total_packages}} kiện hàng</p>

                    <form onchange="this.submit();" action="{{ url('packages')  }}" method="get">
                        <div class="row">
                            {{--<div class="col-sm-3 col-xs-12">--}}
                                {{--<input type="checkbox"--}}
                                       {{--@if(request()->get('package_has_weight') == 'on')--}}
                                        {{--checked--}}
                                       {{--@endif--}}

                                       {{--name="package_has_weight"> cân nặng > 0--}}
                            {{--</div>--}}

                            <div class="col-sm-3 col-xs-12">
                                <input type="text" name="logistic_package_barcode"

                                       placeholder="Mã kiện"
                                       class="form-control"
                                       value="{{request()->get('logistic_package_barcode')}}">
                            </div>

                            <div class="col-sm-3 col-xs-12">
                                <select name="current_warehouse" id="" class="form-control _selectpicker">
                                    <option value="">Chọn kho</option>
                                    @foreach($warehouse_list as $warehouse_list_item)
                                        <option
                                                @if($warehouse_list_item->code == request()->get('current_warehouse'))

                                                         selected

                                                @endif

                                                value="{{$warehouse_list_item->code}}">{{$warehouse_list_item->name}} - {{$warehouse_list_item->code}}</option>
                                    @endforeach
                                </select>


                            </div>

                            <div class="col-sm-3 col-xs-12">
                                <select name="warehouse_status" id="" class="form-control _selectpicker">
                                    <option value="">Hành động</option>
                                    @foreach($action_list as $key => $value)
                                        <option
                                                @if($key == request()->get('warehouse_status'))

                                                selected

                                                @endif

                                                value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>


                    <table class="table no-padding-leftright">
                        <thead>
                        <tr>
                            <th class="">Mã kiện</th>
                            <th class="">Trạng thái</th>
                            <th class="">Đơn hàng</th>
                            <th class="">Người tạo</th>
                            <th class="">Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($packages))
                            @foreach($packages as $package)
                                <tr>
                                    <td>
                                        @if($package->logistic_package_barcode)
                                            <a href="{{ url('package', $package->logistic_package_barcode)  }}">{{$package->logistic_package_barcode}}</a>
                                        @endif

                                        @if($package->weight)
                                            <br>
                                            <small>
                                                {{ $package->weight }} kg
                                            </small>
                                        @endif

                                            <br>
                                        VĐ: {{$package->freight_bill}}
                                    </td>

                                    <td>
                                        {{ App\Package::getStatusTitle($package->status)  }}
                                        <br>

                                        <small>
                                            @if($package->current_warehouse)
                                                Kho hiện tại: {{$package->current_warehouse}} <br>
                                            @endif

                                            @if($package->warehouse_status)
                                                Tình trạng: {{ App\Package::getWarehouseStatusName($package->warehouse_status) }}
                                                @if($package->warehouse_status == App\Package::WAREHOUSE_STATUS_IN)
                                                    ({{ App\Util::formatDate($package->warehouse_status_in_at)}})
                                                @endif

                                                @if($package->warehouse_status == App\Package::WAREHOUSE_STATUS_OUT)
                                                    ({{ App\Util::formatDate($package->warehouse_status_out_at)}})
                                                @endif
                                            @endif

                                        </small>
                                    </td>
                                    <td>
                                        @if($package->order instanceof App\Order)
                                            <a href="{{ url('order', $package->order->id) }}">{{$package->order->code}}</a>
                                            (<small>{{  App\Order::getStatusTitle($package->order->status) }}</small>)
                                        @else
                                            --
                                        @endif

                                        <br>

                                        <small>
                                            @if($package->customer instanceof App\User)
                                                <a href="{{ url('user/detail', $package->customer->id) }}">{{$package->customer->email}}</a>
                                                (<small>{{$package->customer->name}}</small>)
                                            @else
                                                --
                                            @endif
                                        </small>

                                    </td>

                                    <td>
                                        <?php
                                        $created_user = App\User::find($package->created_by);
                                        ?>
                                        <a href="{{ url('user/detail', $package->created_by)  }}">
                                            {{ $created_user->email  }}
                                        </a>
                                        <br>
                                        <small>{{ $created_user->name  }}</small>
                                    </td>
                                    <td>

                                        <small>
                                            @foreach(App\Package::$timeListOrderDetail as $key => $value)
                                                @if($package->$key)
                                                    {{$value}}: {{ App\Util::formatDate($package->$key) }}<br>
                                                @endif
                                            @endforeach
                                        </small>


                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>


{{--                    {{ $packages->links()  }}--}}

                    {{ $packages->appends(request()->input())->links() }}

                </div>
            </div>
        </div>
    </div>

@endsection

@section('css_bottom')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
@endsection

@section('js_bottom')
    @parent
    <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            $('._selectpicker').selectpicker({
//                style: 'btn-info',
//                width: 'fit',
            });

        });

    </script>
@endsection

