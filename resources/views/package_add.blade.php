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
                            ['name' => 'Kiện hàng', 'link' => url('packages')],
                            ['name' => 'Tạo kiện', 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">


                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
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
                        </div>
                        <div class="col-sm-9 col-xs-12">
                            @if(!empty($barcode))
                                <h3>Mã quét: {{$barcode}}</h3>

                                @if(count($packages))
                                    @foreach($packages as $package)
                                        {{$package->logistic_package_barcode}}<br>
                                    @endforeach
                                @endif
                            @endif
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

