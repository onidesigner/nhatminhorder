@extends($layout)

@section('page_title')
    {{@$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                @include('partials/__breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Trang chủ', 'link' => url('home')],
                            ['name' => 'Kiện hàng', 'link' => url('packages')],
                            ['name' => 'Tạo kiện', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">


                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Tạo kiện</h3>

                            <form class="___form" onsubmit="return false;">

                                <input type="hidden" name="method" value="post">
                                <input type="hidden" name="url" value="{{ url('package/action') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token()  }}">
                                <input type="hidden" name="response" value="package_add">
                                <input type="hidden" name="action" value="create_package">

                                <input
                                        autofocus
                                        type="text"
                                        name="barcode"
                                        id="_barcode"
                                        class="form-control _______input-action"
                                        data-key-global="barcode-scan-input-create-package"
                                        placeholder="Quét mã vận đơn...">

                            </form>

                            <h5>Mã quét: {{$barcode}}</h5>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-9">
            @if(!empty($barcode))


                @if(count($packages))
                    @foreach($packages as $package)
                        <div class="card">
                            <div class="card-header">
                                <h3 style="margin: 0">
                                    Kiện hàng #<a href="{{ url('package', $package->logistic_package_barcode)  }}" target="_blank">{{$package->logistic_package_barcode}}</a>
                                </h3>

                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">

                                        <ul class="form-list-item">
                                            @if($package->order)
                                                <li>
                                                    <strong>Đơn hàng</strong>: <a href="{{ url('order', $package->order->id)  }}" target="_blank">{{$package->order->code}}</a>, kho đích {{$package->order->destination_warehouse}}
                                                </li>
                                                <li>
                                                    <strong>Thông tin nhận hàng</strong>:
                                                    <i class="fa fa-user"></i> {{$package->customer_address->reciver_name}} - <i class="fa fa-phone"></i> {{$package->customer_address->reciver_phone}}
                                                    <i class="fa fa-map-marker"></i> {{$package->customer_address->detail}}, {{$package->customer_address->district_label}}, {{$package->customer_address->province_label}}
                                                </li>
                                            @else
                                                <li>
                                                    <strong>Đơn hàng</strong>: --
                                                </li>
                                                <li>
                                                    <strong>Thông tin nhận hàng</strong>: ---
                                                </li>
                                            @endif

                                            <li>
                                                <strong>Cân nặng (kg):</strong>

                                                Tịnh
                                                <input type="radio" checked="checked" name="weight_type_{{$package->id}}" value="1">
                                                <input type="text" style="width: 15%;" class="!form-control">

                                                Quy đổi <input type="radio" name="weight_type_{{$package->id}}" value="2">
                                                <input disabled type="text" style="width: 15%;" class="!form-control">
                                            </li>

                                            <li>
                                                <strong>Thể tích (cm):</strong>
                                                <input type="text" style="width: 10%;" class="!form-control" placeholder="Dài">
                                                x<input type="text" style="width: 10%;" class="!form-control" placeholder="Rộng">
                                                x<input type="text" style="width: 10%;" class="!form-control" placeholder="Cao">

                                            </li>
                                            <li>
                                                <strong>Ghi chú:</strong>
                                                <textarea name="" id="" cols="30" rows="3" class="form-control"></textarea>
                                            </li>
                                            <li>
                                                <a class="btn-link" target="_blank" href="{{ url('package?action=print&logistic_package_barcode=' . $package->logistic_package_barcode)  }}"><i class="fa fa-print"></i> In tem</a>
                                            </li>
                                        </ul>


                                    </div>
                                </div>


                            </div>

                        </div>
                        <br>
                    @endforeach
                @endif
            @endif
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $(document).on('keypress', '#_barcode', function(e){
               if(e.keyCode == 13){
                   var barcode = $(this).val();
                   if(!barcode) return false;

                   $.ajax({
                     url: "{{ url('package/action') }}",
                     method: 'post',
                     data: {
                         barcode:barcode,
                         _token: "{{csrf_token()}}",
                         action: 'create_package',
                         response: 'package_add',
                     },
                     success:function(response) {

                         if(response.success){
                             window.location.href = "{{ url('package?barcode=')  }}" + barcode;
                         }else{
                             if(response.message){
                                 bootbox.alert(response.message);
                             }
                         }
                     },
                     error: function(){

                     }
                   });
               }
            });
        });

    </script>
@endsection

