@extends($layout)

@section('page_title')
    {{@$page_title}}
@endsection

@section('page-header')
    @parent
    <div class="page-header">
        <div class="pull-left">
            <h1>{{$page_title}}</h1>
        </div>

        <div class="pull-right">

            <ul class="minitiles">
                <li class="satgreen">
                    @if($can_create_package)
                    <a href="{{ url('package')  }}" class="">
                        <i class="icon-plus"></i>
                    </a>
                    @endif
                </li>

            </ul>
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
                            ['name' => 'Kiện hàng', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">

                    {{--@if($can_create_package)--}}
                        {{--<a href="{{ url('package')  }}" class="btn btn-danger pull-right">Tạo kiện</a>--}}
                    {{--@endif--}}

                    <p>Tìm thấy {{$total_packages}} kiện hàng</p>

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


                    {{--{{ $packages->links()  }}--}}


                    <div class="pagination">
                        {{ $packages->appends(request()->input())->links() }}
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

