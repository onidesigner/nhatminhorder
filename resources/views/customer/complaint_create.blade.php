@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')




    <div class="container">

        <form class="well form-horizontal" style="width: 90%">
            <fieldset>

                <!-- Form Name -->
                <legend>Tạo khiếu nại đơn hàng {{ $data->code }}</legend>

                <!-- Text input-->
                @if(session('message'))

                    <div class="alert alert-success">
                       {{session('message')}}
                    </div>
                @endif


                <div class="alert alert-warning _error hidden">

                </div>


                <div class="form-group">
                    <label class="col-md-2 control-label">Mã đơn hàng </label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <input  name="order_code" placeholder="Mã đơn hàng" class="form-control"  value="{{ $data->code }}" type="text">
                        </div>
                    </div>
                </div>

                <!-- Text input-->

                <div class="form-group">
                    <label class="col-md-2 control-label" >Tên khiếu nại</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <input name="title_complaint" placeholder="Tên khiếu nại" class="form-control"  type="text">
                        </div>
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">

                    <label class="col-md-2 control-label">Upload ảnh</label>
                 <div class="col-md-4 inputGroupContainer">
                     <a id="_upload_image">
                         <img src="{{ url('/images/upload-cloud.png') }}" height="40" width="60">
                     </a>
                        <div class="input-group hidden" style=" border: none;!important">
                            <input type="file" name="file"  id="file" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-10" id="uploaded_image" style="float: left">


                    </div>
                </div>

                <br>
                <!-- Text area -->

                <div class="form-group">
                    <label class="col-md-2 control-label">Mô tả lỗi</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <textarea class="form-control" name="comment" placeholder="Bạn hãy mô tả lỗi cho chúng tôi"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-4">
                        <a class="btn btn-warning _create_complaint" >Tạo khiếu nại </a>
                    </div>
                </div>

            </fieldset>
        </form>
    </div><!-- /.container -->




@endsection


@section('js_bottom')
    @parent
    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootbox.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            /**
             * xóa ảnh gián tiếp
             */
            $("#_upload_image").click(function () {
                $("#file").click();
            });

            $(document).on('change', '#file', function(){
                uploadImage();
            });

            /**
             * hàm xử lý up ảnh
             */
            function uploadImage(){
                    var name = document.getElementById("file").files[0].name;

                    var form_data = new FormData();
                    var ext = name.split('.').pop().toLowerCase();
                    if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
                    {
                        bootbox.alert('Định dạng ảnh không được hỗ trợ !');
                        return;
                    }
                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(document.getElementById("file").files[0]);
                    var f = document.getElementById("file").files[0];
                    var fsize = f.size||f.fileSize;
                    if(fsize > 2000000)
                    {
                        bootbox.alert('Kích thước ảnh quá lơn !');
                        return;
                    }
                    else
                    {
                        form_data.append("file", document.getElementById('file').files[0]);
                        $.ajax({
                            url:  "{{ url('/upload-image') }}",
                            method:"POST",
                            data: form_data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend:function(){
                                //$('#_progress').html("<label class='text-success'>Đang upload... </label>");
                            },
                            success:function(data)
                            {
                                $('#uploaded_image').append(data);
                                //$('#_progress').html("");
                            }
                        });
                    }
            }

            // click xóa ảnh
            $(document).on('click','._remove_class',function () {
                $(this).parent().parent().remove();
            });

            /**
             * sự kiện click lưu lại value
             */
            $(document).on('click','._create_complaint',function () {
                // value của đơn hàng
                var order_code = $('input[name="order_code"]').val();
                var complaint_name = $('input[name="title_complaint"]').val(); // tên khiếu nại
                var image_path = [];
                $("._display_image").each(function (index, value) {
                    //console.info($(this).attr('src'));
                    image_path.push($(this).attr('src'));
                });

                var description = $('textarea[name="comment"]').val();
                var title = $('input[name="title_complaint"]').val();

                $.ajax({
                    data : {
                        order_code : order_code,
                        title : title,
                        complaint_name : complaint_name,
                        description : description,
                        image_path : image_path,
                    },
                    url:  "{{ url('/khach-hang-tao-khieu-nai') }}",
                    method:"POST",
                    success:function(data){
                        if(data.type == 'success'){
                            window.location.href ="{{ url('chi-tiet-khieu-nai') }}"+"/" + data.complaint_id;
                        }else{
                            $("._error").removeClass('hidden');
                            $("._error").html(data.message);
                        }
                    }
                });
            });
        });
    </script>


@endsection
