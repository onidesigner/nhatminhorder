@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('partials/__breadcrumb',
                    [
                        'urls' => [
                            ['name' => 'Trang chủ', 'link' => url('home')],
                            ['name' => $page_title, 'link' => null],
                        ]
                    ]
                )
                <div class="card-body">
{{--                    <h3>{{$page_title}}</h3>--}}

                    <div class="row">
                        <form action="{{ url('PaidStaffSaleValue')  }}"
                              method="get"
                              onchange="this.submit();">

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select class="_select_month form-control" name="month" id="">

                                                <?php
                                                $max = 10;
                                                for($i = 0; $i < $max; $i++){

                                                    $date = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                                                    $date->modify("-{$i} month");

                                                    $month_choose = date('m');
                                                    if(request()->get('month')){
                                                        $month_choose = explode('_', request()->get('month'))[0];
                                                    }

                                                    $selected = $date->format('m') == $month_choose
                                                        ? ' selected ' : '';


                                                    echo sprintf("<option %s value='%s'>Tháng %s</option>", $selected, $date->format('m') . '_' . $date->format('Y'), $date->format('m/Y'));
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>


                                </div>


                        </form>
                    </div>

                    <div class="row">
                        <h5>*** Lưu ý: </h5>
                        <i class="order-not-original-amount box-color"></i> Đơn chưa nhập giá thực mua
                    </div>

                    @if(count($crane_buying_list))
                        @foreach($crane_buying_list as $crane_buying_list_item)
                            <?php
                            $crane_value_setting = isset($crane_value_setting_list[$crane_buying_list_item->id])
                                ? $crane_value_setting_list[$crane_buying_list_item->id] : [];
                            ?>
                            <div class="row" style="margin-bottom: 30px;">
                                <h1>
                                    <a href="{{ url('user/detail', $crane_buying_list_item->id)  }}">{{$crane_buying_list_item->name}}</a>


                                    <a

                                            class="" data-toggle="modal" href='#modal-crane-buying-setup-{{$crane_buying_list_item->id}}'>

                                        <i
                                                data-toggle="tooltip"
                                                title="Cấu hình lương"
                                                class="fa fa-cog" aria-hidden="true"></i>

                                    </a>

                                    <div class="modal fade" id="modal-crane-buying-setup-{{$crane_buying_list_item->id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">Cấu hình bảng lương - {{$crane_buying_list_item->name}}</h4>
                                                </div>
                                                <div class="modal-body">

                                                    <div id="content-crane-buying-setup">

                                                        <table class="table table-striped table-bordered setting-list">
                                                            <thead>
                                                            <tr>
                                                                <th>Từ tháng</th>
                                                                <th>Đến tháng</th>
                                                                <th>Lương cơ bản</th>
                                                                <th>% mặc cả</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                                @if(isset($crane_value_setting_list[$crane_buying_list_item->id])
                                                                && count($crane_value_setting_list[$crane_buying_list_item->id]))

                                                                    @foreach($crane_value_setting_list[$crane_buying_list_item->id] as $crane_value_setting_list_item)
                                                                        <tr class="_row">
                                                                            <td>
                                                                                <select name="start_month">
                                                                                    <?php
                                                                                    for($i = 1; $i <= 12; $i++){
                                                                                        $selected = $i == date('m', strtotime($crane_value_setting_list_item->activated_at)) ? ' selected ' : '';
                                                                                        echo sprintf("<option %s value='%s'>%s</option>", $selected, $i, $i);
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <select name="start_year">
                                                                                    <?php
                                                                                    for($ii = $begin_year; $ii <= $end_year; $ii++){
                                                                                        $selected = $i == date('Y', strtotime($crane_value_setting_list_item->activated_at)) ? ' selected ' : '';
                                                                                        echo sprintf("<option %s value='%s'>%s</option>", $selected, $ii, $ii);
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="end_month">
                                                                                    <?php
                                                                                    for($j = 1; $j <= 12; $j++){
                                                                                        $selected = $j == date('m', strtotime($crane_value_setting_list_item->deadlined_at)) ? ' selected ' : '';
                                                                                        echo sprintf("<option %s value='%s'>%s</option>", $selected, $j, $j);
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <select name="end_year">
                                                                                    <?php
                                                                                    for($jj = $begin_year; $jj <= $end_year; $jj++){
                                                                                        $selected = $jj == date('Y', strtotime($crane_value_setting_list_item->deadlined_at)) ? ' selected ' : '';
                                                                                        echo sprintf("<option %s value='%s'>%s</option>", $selected, $jj, $jj);
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="salary_basic" value="{{$crane_value_setting_list_item->salary_basic}}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="rose_percent" max="100" value="{{$crane_value_setting_list_item->rose_percent}}">
                                                                            </td>
                                                                            <td>
                                                                                <a href="javascript:void(0)" class="_remove-row"><i class="fa fa-times"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <a href="javascript:void(0)"
                                                    class="_add-config"
                                                    ><i class="fa fa-plus"></i> Thêm cấu hình</a>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button>
                                                    <button type="button"

                                                            data-paid-user-id="{{$crane_buying_list_item->id}}"
                                                            class="btn btn-primary _save-setting">Lưu</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <small>{{$crane_buying_list_item->code}}</small>
                                </h1>

                                <h3
                                        style="cursor: pointer"
                                        data-toggle="collapse" data-target="#order-overrun-{{$crane_buying_list_item->id}}">Đơn hàng đã về <small>(
                                        @if(isset($orders_overrun_list[$crane_buying_list_item->id]))
                                            {{count($orders_overrun_list[$crane_buying_list_item->id])}}
                                        @else
                                            0
                                        @endif
                                        )</small></h3>

                                <div id="order-overrun-{{$crane_buying_list_item->id}}" class="collapse">
                                    <?php
                                    $total_amount_customer = 0;
                                    $total_amount_original = 0;
                                    $total_amount_bargain = 0;

                                    $total_amount_customer_vnd = 0;
                                    $total_amount_original_vnd = 0;
                                    $total_amount_bargain_vnd = 0;

                                    $total_real_amount_vnd = 0;
                                    $total_real_amount_ndt = 0;
                                    ?>

                                    @if(isset($orders_overrun_list[$crane_buying_list_item->id]))

                                        <table class="table table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <td>TT</td>
                                                <td>Đơn hàng</td>
                                                <td class="text-right">(1) Báo khách <i class="fa fa-question-circle" data-toggle="tooltip" title="tiền hàng + ship nội dịa TQ"></i></td>
                                                <td class="text-right">(2) Thực mua <i class="fa fa-question-circle" data-toggle="tooltip" title="tiền hàng + ship nội dịa TQ"></i></td>
                                                <td class="text-right">(1) - (2) Mặc cả</td>
                                                <td class="text-right">Nhận</td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($orders_overrun_list[$crane_buying_list_item->id] as $idx => $orders_overrun_list_item)
                                                <tr
                                                        class="
                                                        @if($orders_overrun_list_item->amount_original <= 0)
                                                            order-not-original-amount
                                                        @endif
                                                    "
                                                >
                                                    <td>{{$idx+1}}</td>
                                                    <td>
                                                        <a href="{{url('order/detail', $orders_overrun_list_item->id)}}">{{$orders_overrun_list_item->code}}</a>
                                                        <small>({{App\Order::getStatusTitle($orders_overrun_list_item->status)}})</small>

                                                        @if($orders_overrun_list_item->bought_at)

                                                            <p>Mua: {{App\Util::formatDate($orders_overrun_list_item->bought_at)}}</p>
                                                        @endif

                                                        @if($orders_overrun_list_item->received_at)
                                                            <p>Nhận: {{App\Util::formatDate($orders_overrun_list_item->received_at)}}</p>
                                                        @endif
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_customer)}}¥
                                                        /
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_customer_vnd)}}đ
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_original)}}¥
                                                        /
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_original_vnd)}}đ
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_bargain)}}¥ /
                                                        {{App\Util::formatNumber($orders_overrun_list_item->amount_bargain_vnd)}}đ
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        <?php
                                                        $rose_percent = App\UserPaidSaleSetting::getValuePercentWithCraneAndTime($crane_value_setting, $orders_overrun_list_item->bought_at);
                                                        $real_amount_vnd = App\UserPaidSaleSetting::getRealAmountVnd($orders_overrun_list_item->amount_bargain_vnd, $rose_percent);
                                                        $real_amount_ndt = App\UserPaidSaleSetting::getRealAmountNdt($orders_overrun_list_item->amount_bargain, $rose_percent);

                                                        $total_real_amount_vnd += $real_amount_vnd;
                                                        $total_real_amount_ndt += $real_amount_ndt;
                                                        ?>
                                                        {{App\Util::formatNumber($real_amount_ndt)}}¥ /
                                                        {{App\Util::formatNumber($real_amount_vnd)}}đ
                                                        <br>
                                                        <i style="color: #000;" class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="{{$rose_percent}}%"></i>
                                                    </td>
                                                </tr>

                                                <?php
                                                $total_amount_customer += $orders_overrun_list_item->amount_customer;
                                                $total_amount_original += $orders_overrun_list_item->amount_original;
                                                $total_amount_bargain += $orders_overrun_list_item->amount_bargain;

                                                $total_amount_customer_vnd += $orders_overrun_list_item->amount_customer_vnd;
                                                $total_amount_original_vnd += $orders_overrun_list_item->amount_original_vnd;
                                                $total_amount_bargain_vnd += $orders_overrun_list_item->amount_bargain_vnd;
                                                ?>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <h5>

                                                        {{App\Util::formatNumber($total_amount_customer)}}¥ / {{App\Util::formatNumber($total_amount_customer_vnd)}} đ

                                                    </h5>
                                                </td>
                                                <td class="text-right">
                                                    <h5>

                                                        {{App\Util::formatNumber($total_amount_original)}}¥ / {{App\Util::formatNumber($total_amount_original_vnd)}} đ

                                                    </h5>
                                                </td>
                                                <td class="text-right">
                                                    <h5>
                                                        {{App\Util::formatNumber($total_amount_bargain)}}¥ / {{App\Util::formatNumber($total_amount_bargain_vnd)}} đ
                                                    </h5>
                                                </td>

                                                <td class="text-right">
                                                    <h5>
                                                        {{App\Util::formatNumber($total_real_amount_ndt)}}¥ / {{App\Util::formatNumber($total_real_amount_vnd)}} đ
                                                    </h5>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    @else
                                        <h6>Không có gì!</h6>
                                    @endif
                                </div>



                                <h3
                                        style="cursor: pointer"
                                        data-toggle="collapse" data-target="#order-buying-{{$crane_buying_list_item->id}}">Đơn hàng chưa về <small>(
                                        @if(isset($orders_buying_list[$crane_buying_list_item->id]))
                                            {{count($orders_buying_list[$crane_buying_list_item->id])}}
                                        @else
                                            0
                                        @endif
                                        )</small></h3>

                                <div id="order-buying-{{$crane_buying_list_item->id}}" class="collapse">
                                    <?php
                                    $total_amount_customer1 = 0;
                                    $total_amount_original1 = 0;
                                    $total_amount_bargain1 = 0;

                                    $total_amount_customer_vnd1 = 0;
                                    $total_amount_original_vnd1 = 0;
                                    $total_amount_bargain_vnd1 = 0;
                                    ?>

                                    @if(isset($orders_buying_list[$crane_buying_list_item->id]))
                                        <table class="table table-hover table-striped">

                                            <thead>
                                            <tr>
                                                <td>TT</td>
                                                <td>Đơn hàng</td>
                                                <td class="text-right">(1) Báo khách <i class="fa fa-question-circle" data-toggle="tooltip" title="tiền hàng + ship nội dịa TQ"></i></td>
                                                <td class="text-right">(2) Thực mua <i class="fa fa-question-circle" data-toggle="tooltip" title="tiền hàng + ship nội dịa TQ"></i></td>
                                                <td class="text-right">(1) - (2) Mặc cả</td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($orders_buying_list[$crane_buying_list_item->id] as $idx => $orders_buying_list_item)
                                                <tr
                                                        class="
                                                        @if($orders_buying_list_item->amount_original <= 0)
                                                                order-not-original-amount
                                                            @endif
                                                                "
                                                >
                                                    <td>{{$idx+1}}</td>
                                                    <td>
                                                        <a href="{{url('order/detail', $orders_buying_list_item->id)}}">{{$orders_buying_list_item->code}}</a>
                                                        <small>({{App\Order::getStatusTitle($orders_buying_list_item->status)}})</small>

                                                        @if($orders_buying_list_item->bought_at)

                                                            <p>Mua: {{App\Util::formatDate($orders_buying_list_item->bought_at)}}</p>
                                                        @endif

                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_customer)}}¥
                                                        /
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_customer_vnd)}}đ
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_original)}}¥
                                                        /
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_original_vnd)}}đ
                                                    </td>
                                                    <td class="text-right text-danger">
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_bargain)}}¥ /
                                                        {{App\Util::formatNumber($orders_buying_list_item->amount_bargain_vnd)}}đ
                                                    </td>

                                                </tr>

                                                <?php
                                                $total_amount_customer1 += $orders_buying_list_item->amount_customer;
                                                $total_amount_original1 += $orders_buying_list_item->amount_original;
                                                $total_amount_bargain1 += $orders_buying_list_item->amount_bargain;

                                                $total_amount_customer_vnd1 += $orders_buying_list_item->amount_customer_vnd;
                                                $total_amount_original_vnd1 += $orders_buying_list_item->amount_original_vnd;
                                                $total_amount_bargain_vnd1 += $orders_buying_list_item->amount_bargain_vnd;
                                                ?>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <h5>

                                                        {{App\Util::formatNumber($total_amount_customer1)}}¥ / {{App\Util::formatNumber($total_amount_customer_vnd1)}} đ

                                                    </h5>
                                                </td>
                                                <td class="text-right">
                                                    <h5>

                                                        {{App\Util::formatNumber($total_amount_original1)}}¥ / {{App\Util::formatNumber($total_amount_original_vnd1)}} đ

                                                    </h5>
                                                </td>
                                                <td class="text-right">
                                                    <h5>
                                                        {{App\Util::formatNumber($total_amount_bargain1)}}¥ / {{App\Util::formatNumber($total_amount_bargain_vnd1)}} đ
                                                    </h5>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    @else
                                        <h6>Không có gì!</h6>
                                    @endif
                                </div>



                                <!-- ket thuc don hang phat sinh -->

                                <?php

                                $sale_finish = App\UserPaidSaleSetting::getSalaryWithCraneAndTime($crane_value_setting, request()->get('month')) + $total_real_amount_vnd;
                                $time_cal_value = date('m') . '/' . date('Y');
                                $month_selected = request()->get('month');
                                if(isset($month_selected)){
                                    $month_temp = explode('_', $month_selected);
                                    $time_cal_value = $month_temp[0] . '/' . $month_temp[1];
                                }

                                ?>
                                <h3>Lương tháng {{$time_cal_value}} <i class="fa fa-question-circle"

                                    data-toggle="tooltip"
                                             data-html="true"
                                             title="

{{--<p>Lương cơ bản: {{ App\Util::formatNumber($crane_buying_list_item->sale_basic)  }} đ</p>--}}
{{--<p>Phần trăm tính doanh số: {{$crane_buying_list_item->sale_percent}} %</p>--}}
<p>Doanh số: {{ App\Util::formatNumber($total_real_amount_vnd)  }} đ</p>
"
                                    ></i>: {{App\Util::formatNumber($sale_finish)}} đ</h3>

                            </div>

                        @endforeach

                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css_bottom')
    @parent
    <style>
        .done{
            background: rgba(41, 199, 95, 0.65);
        }
        .not-done{
            background: rgba(251, 255, 150, 0.56);
        }
        .box-color{
            width: 20px;
            height: 20px;
            display: inline-block;
            float: left;
            margin-right: 10px;
        }
        .order-not-original-amount{
            /*background: rgb(236, 45, 45);*/
            /*color: #fff;*/

            background: rgba(208, 89, 89, 0.23)!important;
        }


        .setting-list td, th{
            padding: 5px!important;
        }
    </style>
@endsection

@section('js_bottom')
    @parent
    <script src="{{ asset('js/bootstrap-datepicker.js')  }}"></script>
    <script>
        $(document).ready(function(){

            $('._datepicker').datepicker({

            });

            $(document).on('click', '._add-config', function(){

                var parent = $(this).parents('.modal-body');
                var row = '<tr class="_row">';
                    row += '<td>';
                        row += '<select name="start_month">';
                        for(var i = 1; i <= 12; i++){
                            row += '<option value="' +i+ '">'+i+'</option>';
                        }
                        row += '</select>';

                        row += '<select name="start_year">';
                        for(var j = parseInt("{{$begin_year}}"); j <= parseInt("{{$end_year}}"); j++){
                            row += '<option value="' +j+ '">'+j+'</option>';
                        }
                        row += '</select>';

                    row += '</td>';

                    row += '<td>';

                        row += '<select name="end_month">';
                        for(var i = 1; i <= 12; i++){
                            row += '<option value="' +i+ '">'+i+'</option>';
                        }
                        row += '</select>';

                        row += '<select name="end_year">';
                        for(var j = parseInt("{{$begin_year}}"); j <= parseInt("{{$end_year}}"); j++){
                            row += '<option value="' +j+ '">'+j+'</option>';
                        }
                        row += '</select>';

                    row += '</td>';
                    row += '<td><input type="number" name="salary_basic" max="100" value="0" /></td>';
                    row += '<td><input type="number" name="rose_percent" max="100" value="0" /></td>';
                    row += '<td><a href="javascript:void(0)" class="_remove-row"><i class="fa fa-times"></i></a></td>';
                    row += '</tr>';

                parent.find('tbody').append(row);
                parent.find('tbody tr:last select:first').focus();

            });

            $(document).on('click', '._remove-row', function(){
                $(this).parents('._row').remove();
            });

            $(document).on('click', '._save-setting', function(){
                var paid_user_id = $(this).data('paid-user-id');
                var items = [];
                var parent = $(this).parents('.modal');
                parent.find('._row').each(function(i){
                    items.push({
                        start_month:$(this).find('select[name="start_month"]').val(),
                        start_year:$(this).find('select[name="start_year"]').val(),
                        end_month:$(this).find('select[name="end_month"]').val(),
                        end_year:$(this).find('select[name="end_year"]').val(),
                        salary_basic:$(this).find('input[name="salary_basic"]').val(),
                        rose_percent:$(this).find('input[name="rose_percent"]').val()
                    });
                });
                request("{{url('PaidStaffSaleValue/Setting')}}", "post", {
                    paid_user_id:paid_user_id,
                    items:items
                }).done(function(response){
                    if(response.success){

                        $.notify("Lưu thành công", {type:"success"});
                        $('#modal-crane-buying-setup-' + paid_user_id).modal('hide');

                    }else{
                        $.notify("Lưu không thành công", {type:"error"});
                    }
                });
            });
        });

    </script>
@endsection

