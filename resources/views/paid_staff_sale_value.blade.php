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
                    <h3>{{$page_title}}</h3>

                    Tháng cần xem
                    <form action="{{ url('PaidStaffSaleValue')  }}"
                          method="get"
                          onchange="this.submit();">

                    <select class="_select_month" name="month" id="">

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
                    </form>

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


                    @if(count($crane_buying_list))

                        @foreach($crane_buying_list as $crane_buying_list_item)
                            <div class="row" style="margin-bottom: 30px;">
                                <h4>
                                    <a href="{{ url('user/detail', $crane_buying_list_item->id)  }}">{{$crane_buying_list_item->name}}</a> <small>{{$crane_buying_list_item->code}}</small>
                                </h4>

                                <?php

//                                function sum($v1,$v2)
//                                {
//                                    return $v1->amount_bargain + $v2->amount_bargain;
//                                }
//                                print_r(array_reduce($orders_with_crane_buying[$crane_buying_list_item->id], "sum"));

                                ?>

                                {{--<p>Lương cơ bản: {{App\Util::formatNumber($crane_buying_list_item->sale_basic)}}đ</p>--}}
                                {{--<p>Hoa hồng: {{$crane_buying_list_item->sale_percent}}%</p>--}}

                                <a class="btn btn-primary" data-toggle="modal" href='#modal-id-{{$crane_buying_list_item->id}}'>>> Xem đơn hàng</a>

                                <div class="modal fade" id="modal-id-{{$crane_buying_list_item->id}}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Đơn hàng</h4>
                                            </div>
                                            <div class="modal-body">
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
                                                                    <small>{{App\Order::getStatusTitle($orders_with_crane_buying_item->status)}}</small>


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
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_customer)}}¥
                                                                    <br>
                                                                    {{App\Util::formatNumber($orders_with_crane_buying_item->amount_customer_vnd)}}đ
                                                                </td>
                                                                <td class="text-right">
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
                                                {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <h5>
                                    <?php
                                    $hoa_hong_mac_ca_kha_dung = $statistic['total_amount_bargain_done_vnd'] * $crane_buying_list_item->sale_percent / 100;
                                    $tien_thuc_linh = $hoa_hong_mac_ca_kha_dung + $crane_buying_list_item->sale_basic;
                                    ?>
                                    Lương thực lĩnh (Lương cơ bản + {{$crane_buying_list_item->sale_percent}}% hoa hồng, số tiền mặc cả khả dụng):
                                    {{App\Util::formatNumber($crane_buying_list_item->sale_basic)}} đ
                                        + {{App\Util::formatNumber($hoa_hong_mac_ca_kha_dung)}} đ
                                        = {{App\Util::formatNumber($tien_thuc_linh)}} đ
                                </h5>

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
    </style>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){


        });

    </script>
@endsection

