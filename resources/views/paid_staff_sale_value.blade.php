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
                        <i class="order-not-original-amount box-color"></i> Đơn chưa nhập giá thực mua
                    </div>

                    @if(count($crane_buying_list))

                        @foreach($crane_buying_list as $crane_buying_list_item)
                            <div class="row" style="margin-bottom: 30px;">
                                <h1>
                                    <a href="{{ url('user/detail', $crane_buying_list_item->id)  }}">{{$crane_buying_list_item->name}}</a> <small>{{$crane_buying_list_item->code}}</small>
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
                                    @if(isset($orders_overrun_list[$crane_buying_list_item->id]))
                                        <?php
                                        $total_amount_customer = 0;
                                        $total_amount_original = 0;
                                        $total_amount_bargain = 0;

                                        $total_amount_customer_vnd = 0;
                                        $total_amount_original_vnd = 0;
                                        $total_amount_bargain_vnd = 0;
                                        ?>
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
                                                    <h4>

                                                        {{App\Util::formatNumber($total_amount_customer)}}¥ / {{App\Util::formatNumber($total_amount_customer_vnd)}} đ

                                                    </h4>
                                                </td>
                                                <td class="text-right">
                                                    <h4>

                                                        {{App\Util::formatNumber($total_amount_original)}}¥ / {{App\Util::formatNumber($total_amount_original_vnd)}} đ

                                                    </h4>
                                                </td>
                                                <td class="text-right">
                                                    <h4>
                                                        {{App\Util::formatNumber($total_amount_bargain)}}¥ / {{App\Util::formatNumber($total_amount_bargain_vnd)}} đ
                                                    </h4>
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
                                    @if(isset($orders_buying_list[$crane_buying_list_item->id]))
                                        <table class="table table-hover table-striped">
                                            <?php
                                            $total_amount_customer1 = 0;
                                            $total_amount_original1 = 0;
                                            $total_amount_bargain1 = 0;

                                            $total_amount_customer_vnd1 = 0;
                                            $total_amount_original_vnd1 = 0;
                                            $total_amount_bargain_vnd1 = 0;
                                            ?>

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
                                                    <h4>

                                                        {{App\Util::formatNumber($total_amount_customer1)}}¥ / {{App\Util::formatNumber($total_amount_customer_vnd1)}} đ

                                                    </h4>
                                                </td>
                                                <td class="text-right">
                                                    <h4>

                                                        {{App\Util::formatNumber($total_amount_original1)}}¥ / {{App\Util::formatNumber($total_amount_original_vnd1)}} đ

                                                    </h4>
                                                </td>
                                                <td class="text-right">
                                                    <h4>
                                                        {{App\Util::formatNumber($total_amount_bargain1)}}¥ / {{App\Util::formatNumber($total_amount_bargain_vnd1)}} đ
                                                    </h4>
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

                                $sale_rose = $crane_buying_list_item->sale_percent * $total_amount_bargain_vnd1 / 100;
                                $sale_finish = $crane_buying_list_item->sale_basic + $sale_rose;
                                ?>

                                <h3>Lương <i class="fa fa-question-circle"

                                    data-toggle="tooltip"
                                             data-html="true"
                                             title="

<p>Lương cơ bản: {{ App\Util::formatNumber($crane_buying_list_item->sale_basic)  }} đ</p>
<p>Phần trăm tính doanh số: {{$crane_buying_list_item->sale_percent}} %</p>
<p>Lương doanh số: {{ App\Util::formatNumber($sale_rose)  }} đ</p>
"
                                    ></i>: {{App\Util::formatNumber($sale_finish)}} đ</h3>

                                <?php

                                $statistic = [
                                'total_amount_customer' => 0,
                                'total_amount_original' => 0,
                                'total_amount_bargain' => 0,

                                'total_amount_customer_vnd' => 0,
                                'total_amount_original_vnd' => 0,
                                'total_amount_bargain_vnd' => 0,

                                'total_amount_bargain_done' => 0,
                                'total_amount_bargain_not_done' => 0,
                                'total_amount_bargain_done_vnd' => 0,
                                'total_amount_bargain_not_done_vnd' => 0
                                ];
                                ?>

                                {{--<p>Lương cơ bản: {{App\Util::formatNumber($crane_buying_list_item->sale_basic)}}đ</p>--}}
                                {{--<p>Hoa hồng: {{$crane_buying_list_item->sale_percent}}%</p>--}}

                                {{--<a class="btn btn-primary" data-toggle="modal" href='#modal-id-{{$crane_buying_list_item->id}}'>>> Xem đơn hàng</a>--}}

                                <div class="modal fade" id="modal-id-{{$crane_buying_list_item->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Đơn hàng</h4>
                                            </div>
                                            <div class="modal-body">

                                                <p>
                                                    <i class="box-color" style="background: rgba(41, 199, 95, 0.65);"></i> Đơn hàng khách đã nhận hàng, tiền mặc cả của đơn được tính vào lương
                                                </p>

                                                <p>
                                                    <i class="box-color" style="background: rgba(251, 255, 150, 0.56);"></i> Đơn hàng khách chưa ấn nhận hàng, tiền mặc cả của đơn chưa được tính vào lương
                                                </p>

                                                <p>
                                                    <i class="box-color" style="background: rgb(236, 45, 45)"></i> Đơn hàng chưa điền tổng giá thực mua (nếu không điền thì hệ thống mặc định đặt tiền mặc cả của đơn là 0¥)
                                                </p>


                                                @if(isset($orders_with_crane_buying[$crane_buying_list_item->id])
                                                                            && count($orders_with_crane_buying[$crane_buying_list_item->id]))

                                                    <table class="table">
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

                                                        @foreach ($orders_with_crane_buying[$crane_buying_list_item->id] as $idx => $orders_with_crane_buying_item)

                                                            <tr class="@if($orders_with_crane_buying_item->is_done) done @else not-done @endif">

                                                                <td>{{$idx+1}}</td>
                                                                <td>
                                                                    <a href="{{url('order/detail', $orders_with_crane_buying_item->id)}}">{{$orders_with_crane_buying_item->code}}</a>
                                                                    <small>({{App\Order::getStatusTitle($orders_with_crane_buying_item->status)}})</small>


                                                                    <br>
                                                                    <p>
                                                                        Mua lúc: {{ App\Util::formatDate($orders_with_crane_buying_item->bought_at)  }}
                                                                    </p>
                                                                    @if($orders_with_crane_buying_item->received_at)
                                                                    <p>
                                                                        Nhận hàng lúc: {{ App\Util::formatDate($orders_with_crane_buying_item->received_at)  }}
                                                                    </p>
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">
                                                                    <span class="_amount_customer @if($orders_with_crane_buying_item->is_done) _done @else _not-done @endif" data-value="{{$orders_with_crane_buying_item->amount_customer}}">{{App\Util::formatNumber($orders_with_crane_buying_item->amount_customer)}}</span>¥
                                                                    <br>
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_customer_vnd)}}đ
                                                                </td>
                                                                <td class="text-right

@if($orders_with_crane_buying_item->amount_original == 0) order-not-original-amount @endif
">
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_original)}}¥
                                                                    <br>
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_original_vnd)}}đ
                                                                </td>
                                                                <td class="text-right">
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_bargain)}}¥<br>
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_bargain_vnd)}}đ
                                                                </td>

                                                            </tr>

                                                            <?php
                                                            $statistic['total_amount_customer'] += $orders_with_crane_buying_item->amount_customer;
                                                            $statistic['total_amount_original'] += $orders_with_crane_buying_item->amount_original;
                                                            $statistic['total_amount_bargain'] += $orders_with_crane_buying_item->amount_bargain;


                                                            $statistic['total_amount_customer_vnd'] += $orders_with_crane_buying_item->amount_customer_vnd;
                                                            $statistic['total_amount_original_vnd'] += $orders_with_crane_buying_item->amount_original_vnd;
                                                            $statistic['total_amount_bargain_vnd'] += $orders_with_crane_buying_item->amount_bargain_vnd;


                                                            if($orders_with_crane_buying_item->is_done){
                                                                $statistic['total_amount_bargain_done'] += $orders_with_crane_buying_item->amount_bargain_done;
                                                                $statistic['total_amount_bargain_done_vnd'] += $orders_with_crane_buying_item->amount_bargain_done_vnd;
                                                            }else{
                                                                $statistic['total_amount_bargain_not_done'] += $orders_with_crane_buying_item->amount_bargain_not_done;
                                                                $statistic['total_amount_bargain_not_done_vnd'] += $orders_with_crane_buying_item->amount_bargain_not_done_vnd;
                                                            }

                                                            ?>
                                                        @endforeach

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">{{App\Util::formatNumber($statistic['total_amount_customer'])}} ¥
                                                                <br>
                                                                {{App\Util::formatNumber($statistic['total_amount_customer_vnd'])}} đ
                                                            </td>
                                                            <td class="text-right">{{App\Util::formatNumber($statistic['total_amount_original'])}} ¥
                                                                <br>
                                                                {{App\Util::formatNumber($statistic['total_amount_original_vnd'])}} đ
                                                            </td>
                                                            <td class="text-right">{{App\Util::formatNumber($statistic['total_amount_bargain'])}} ¥
                                                                <br>
                                                                {{App\Util::formatNumber($statistic['total_amount_bargain_vnd'])}} đ

                                                                <i class="fa fa-question-circle" data-toggle="tooltip" data-html="true" title="


Khả dụng {{  App\Util::formatNumber($statistic['total_amount_bargain_done']) }}¥ / {{  App\Util::formatNumber($statistic['total_amount_bargain_done_vnd']) }}đ<br/>
Đóng băng {{  App\Util::formatNumber($statistic['total_amount_bargain_not_done']) }}¥ / {{  App\Util::formatNumber($statistic['total_amount_bargain_not_done_vnd']) }}đ

"></i>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>

                                                @else
                                                    <h5>Không có đơn hàng!</h5>
                                                @endif


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>






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


    </style>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

