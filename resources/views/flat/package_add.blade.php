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

    </div>
@endsection

@section('content')
    <div class="row-fluid">
        <div class="span12">
            @include('flat/partials/breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Bảng chung', 'link' => url('home')],
                            ['name' => 'Kiện hàng', 'link' => url('packages')],
                            ['name' => 'Tạo kiện', 'link' => null],
                        ]
                    ]
                )
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3">
            <div class="card">

                <div class="card-body">


                    <form class="___form" onsubmit="return false; " style="margin-bottom: 0;">

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

                    @if($barcode)
                        <div>Mã quét: {{$barcode}}</div>
                    @endif


                </div>
            </div>
        </div>
        <div class="span9 list-packages-view">
            @if(!empty($barcode))

                @if(count($packages))
                    @foreach($packages as $package)

                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            Kiện hàng #<a href="{{ url('package', $package->logistic_package_barcode)  }}">{{$package->logistic_package_barcode}}</a>
                                        </h3>
                                    </div>
                                    <div class="box-content">
                                        <form class="form-vertical _package-item-form" action="" method="post" onsubmit="return false;">
                                                {{csrf_field()}}

                                            <input type="hidden" name="action" value="update_package">
                                            <input type="hidden" name="package_id" value="{{ $package->id  }}">

                                            <div class="control-group">
                                                <label for="textfield" class="control-label">Text input</label>
                                                <div class="controls">
                                                    <input type="text" name="textfield" id="textfield" placeholder="Text input" class="input-xlarge">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label for="password" class="control-label">Password</label>
                                                <div class="controls">
                                                    <input type="password" name="password" id="password" placeholder="Password input" class="input-xlarge">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Checkboxes<small>More information here</small></label>
                                                <div class="controls">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="checkbox"> Lorem ipsum eiusmod
                                                    </label>
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="checkbox"> ipsum eiusmod
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label for="textarea" class="control-label">Textarea</label>
                                                <div class="controls">
                                                    <textarea name="textarea" id="textarea" rows="5" class="input-block-level">Lorem ipsum mollit minim fugiat tempor dolore sit officia ut dolore. </textarea>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                <button type="button" class="btn">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


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

            $(document).on('change', '._choose-weight-type', function(){
                var type = $(this).val();
                var $parent = $(this).parents('._package');
                $parent.find('._volume').addClass('hidden');
                if(type == 2){
                    $parent.find('._volume').removeClass('hidden');
                }
            });

            $(document).on('click', '._print', function(e){
                var parent = $(this).parents('._package');
                var href = parent.find('._link-in').attr('href');
                if(href){
                    parent.find('iframe').attr('src', href);
                }
            });

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

            $(document).on('change', '._package-item-form', function(){
                var data_send = $(this).serializeObject();

                data_send.service = [];
                var $package = $('._package[data-package-id="' + data_send.package_id + '"]');
                $package.find('._choose-package-service').each(function(i){
                    data_send.service.push({
                        code:$(this).val(),
                        checked:$(this).is(':checked') ? 1 : 0
                    });
                });

                $.ajax({
                  url: "{{ url('package/action')  }}",
                  method: 'post',
                  data: data_send,
                  success:function(response) {
                      if(response.success){
                          var parent = $('._package[data-package-id="' + response.result.package.id + '"]');
                          parent.find('input[name="converted_weight"]').val(response.result.package.converted_weight);
                          parent.find('._view-wood-crating').text(response.result.package.wood_crating_fee);
                      }else{
                          if(response.message){
                              bootbox.alert(response.message);
                          }
                      }
                  },
                  error: function(){

                  }
                });
            });

            $(document).on('click', '._delete-package', function(){
                var package_id = $(this).data('package-id');
                $.ajax({
                    url: "{{ url('package/action')  }}",
                    method: 'post',
                    data: {
                        _token: "{{csrf_token()}}",
                        action: 'delete_package',
                        package_id:package_id,
                    },
                    success:function(response) {
                        if(response.success){
                            $('._package[data-package-id="' + package_id + '"]').remove();
                        }else{
                            if(response.message){
                                bootbox.alert(response.message);
                            }
                        }
                    },
                    error: function(){

                    }
                });
            });
        });

    </script>
@endsection

@section('css_bottom')
    @parent
    <style>
        .list-packages-view .card{
            margin-bottom: 15px;
        }

        .list-packages-view .card:nth-child(2n) .card-header{
            /*background: #29c75f!important;*/
            /*color: #fff!important;*/
        }
    </style>
@endsection

