@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

<?php
if (!empty($_GET['status'])) {
    switch ($_GET['status']) {
        case 'succ':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Upload thành công';
            break;
        case 'err':
            $statusMsgClass = 'alert-danger';
            $statusMsg = ' Upload thất bại !';
            break;
        case 'invalid_file':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Hãy up đúng định dạng CSV';
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}
?>

@section('content')
    <div class="container">
        <?php if (!empty($statusMsg)) {
            echo '<div style="width: 90%" class="alert ' . $statusMsgClass . '">' . $statusMsg . '</div>';
        } ?>

        <div class="panel panel-default" style="width: 90%">
            <div class="panel-heading">
                Danh sách khách hàng
                <a href="javascript:void(0);" onclick="$('#importFrm').slideToggle();">upload danh sách</a>
            </div>
            <div class="panel-body">
                <form action="{{ url('send-sms-2') }}" method="post" enctype="multipart/form-data" id="importFrm">
                    <input type="file" name="file" style="float: left"/>
                    <input type="submit" class="btn btn-primary" name="importSubmit" value="UPLOAD" style="margin-bottom: 20px;">
                </form>

                <form action="{{ url('send-sms') }}"   method="get" >

                        <select class="selectpicker" name="level">
                            <option value=""
                                    @if(@request()->get('level') == '')
                                    selected
                                    @endif
                            >Level</option>
                            <option value="0"
                            @if(@request()->get('level') == 0)
                                selected
                                @endif
                            >0</option>
                            <option value="1"
                                    @if(@request()->get('level') == 1)
                                    selected
                                    @endif
                            >1</option>
                            <option value="2"
                                    @if(@request()->get('level') == 2)
                                    selected
                                    @endif
                            >2</option>
                            <option value="4"
                                    @if(@request()->get('level') == 4)
                                    selected
                                    @endif
                            >4</option>
                        </select>

                        <select class="selectpicker" name="status">
                            <option value="">Trang thái gửi tin nhắn</option>
                            <option value="SEND_SUCCESS"
                                    @if(@request()->get('status') == "SEND_SUCCESS")
                                    selected
                                    @endif
                            >Thành Công</option>
                            <option value="SEND_NOT_SUCCESS"
                                    @if(@request()->get('status') == "SEND_NOT_SUCCESS")
                                    selected
                                    @endif
                            >Thất bại</option>
                        </select>
                        <select class="selectpicker" name="number">
                            <option value="">Số lần gửi tin</option>
                            <option value="1"
                                    @if(@request()->get('number') == 1)
                                    selected
                                    @endif
                            >1</option>
                            <option value="2"
                                    @if(@request()->get('number') == 2)
                                    selected
                                    @endif
                            >2</option>
                            <option value="3"
                                    @if(@request()->get('number') == 3)
                                    selected
                                    @endif
                            >3</option>
                        </select>

                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>

                </form>

                    {{--<a href="{{ URL::to('downloadExcel') }}"><button class="btn btn-success">Download CSV</button></a>--}}
                    <br/>
                    {{--<span>Số bản ghi {{ $count_item }} </span>--}}


                        <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th><input type="checkbox" id="checkall" class="check_all" /> Gửi SMS </th>
                            <th>Tên khách</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Cấp độ(level)</th>
                            <th>Trạng thái SMS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($data_sms) > 0)
                            <?php $i = 1; ?>
                        @foreach($data_sms as $item_sms)
                            <tr>
                                <td>{{ $per_page * $page + $i++ }}</td>
                                <td><input type="checkbox" class="_checkboxSys"  value="{{ $item_sms->phone }}"/></td>
                                <td>{{ $item_sms->name }}</td>
                                <td>{{ $item_sms->email }}</td>
                                <td>{{ $item_sms->phone }}</td>
                                <td>{{ $item_sms->level }}</td>
                                <td>{{ \App\Library\Sms\SendSmsToCustomer::getStatus($item_sms->status) }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="6">Chưa có dữ liệu !</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>


                {{ $data_sms->links() }}
                <button type="button" class="btn btn-success _sendsms">Gửi tin</button>
            </div>
        </div>
    </div>



@endsection

@section('css_bottom')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .pagination{
            float: right;
            margin-right: 0px;
            }
    </style>

@endsection
@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){

            $('#importFrm').hide();

            $(".check_all").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });


            $("._sendsms").click(function (response) {
                var list_product_id = [];
                $('._checkboxSys:checked').each(function () {
                    list_product_id.push($(this).attr('value'));
                });
                if(list_product_id.length == 0){
                    alert('Chọn khách hàng để gửi tin');
                }
                
                $.ajax({
                    type : 'POST',
                    url : '/gui-tin-nhan',
                    data : {
                        list_phone : list_product_id
                    }
                }).done(function (response) {
                        console.info(response.status);
                });
            });





        });
    </script>
@endsection





