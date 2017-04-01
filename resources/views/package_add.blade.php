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
                    <h3>Tạo kiện</h3>

                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <form class="___form">

                                <input type="hidden" name="method" value="post">
                                <input type="hidden" name="url" value="{{ url('package/action') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token()  }}">
                                <input type="hidden" name="response" value="package_add">
                                <input type="hidden" name="action" value="create_package">

                                <input
                                        autofocus
                                        type="text"
                                        name="barcode"
                                        class="form-control ___input-action"
                                        data-key-global="barcode-scan-input-create-package"
                                        placeholder="Quét mã">

                            </form>
                        </div>
                        <div class="col-sm-9 col-xs-12">

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

