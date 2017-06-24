@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')


    <div class="container">

        <form class="well form-horizontal" enctype="multipart/form-data" action="/create-complaint" method="post"  id="contact_form" style="width: 90%">
            <fieldset>

                <!-- Form Name -->
                <legend>Tạo khiếu nại đơn hàng {{ $data->code }}</legend>

                <!-- Text input-->
                @if(session('message'))

                    <div class="alert alert-success">
                       {{session('message')}}
                    </div>
                @endif

                @if(session('error'))
                <div class="alert alert-warning">
                    {{session('error')}}
                </div>
                @endif

                <div class="form-group">
                    <label class="col-md-4 control-label">Mã đơn hàng </label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <input  name="order_code" placeholder="Mã đơn hàng" class="form-control"  value="{{ $data->code }}" type="text">
                        </div>
                    </div>
                </div>

                <!-- Text input-->

                <div class="form-group">
                    <label class="col-md-4 control-label" >Tên khiếu nại</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <input name="title_complaint" placeholder="Tên khiếu nại" class="form-control"  type="text">
                        </div>
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label">Upload ảnh</label>
                 <div class="col-md-4 inputGroupContainer">
                        <div class="input-group" style=" border: none;!important">
                            {{--<input type="file" name="image[]" multiple/>--}}
                            <input type="file" name="file" id="file" />
                            <br>
                            <span id="uploaded_image"></span>
                        </div>
                    </div>
                </div>

                <!-- Text area -->

                <div class="form-group">
                    <label class="col-md-4 control-label">Mô tả lỗi</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <textarea class="form-control" name="comment" placeholder="Bạn hãy mô tả lỗi cho chúng tôi"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning" >Tạo khiếu nại </button>
                    </div>
                </div>

            </fieldset>
        </form>
    </div><!-- /.container -->





@endsection


@section('js_bottom')
    @parent
    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('change', '#file', function(){
                var name = document.getElementById("file").files[0].name;
                var form_data = new FormData();
                var ext = name.split('.').pop().toLowerCase();
                if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
                {
                    alert("Invalid Image File");
                }
                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("file").files[0]);
                var f = document.getElementById("file").files[0];
                var fsize = f.size||f.fileSize;
                if(fsize > 2000000)
                {
                    alert("Image File Size is very big");
                }
                else
                {
                    form_data.append("file", document.getElementById('file').files[0]);
                    $.ajax({
                        url: '',
                        method:"POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend:function(){
                            $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
                        },
                        success:function(data)
                        {
                            $('#uploaded_image').html(data);
                        }
                    });
                }
            });
        });
    </script>


@endsection
