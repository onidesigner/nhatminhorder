@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection
@section('content')
    <form action="/san-luong-van-chuyen" method="GET">
        <div class="row" class="col-sm-12" >
            <p>
                <div class="col-sm-3">
                    <span>

                        <?php

                            $date_from = empty(request()->get('date1')) ? date('Y-m-d') : request()->get('date1');
                            $date_to = empty(request()->get('date2')) ? date('Y-m-d') : request()->get('date2');

                        ?>

                        {{ Form::text('date1', $date_from, array('id' => 'datepicker') )}}
                    </span>
                </div>

                <div class="col-sm-3">
                    <span>
                        {{ Form::text('date2', $date_to, array('id' => 'datepicker2') )}}
                    </span>
                </div>

                <div class="col-sm-3">
                    <span>
                            <div class="form-group">
                              <select class="form-control" name="warehouse">
                                  <option value="0">Chọn kho</option>
                                  <option value="CNGZ">CNGZ - Kho Quảng Châu</option>
                                  <option value="CNPX">CNPX - Bằng Tường</option>
                                  <option value="K-HN">K-HN - Hà Nội</option>
                                  <option value="S-SG">S-SG - Sài Gòn</option>
                              </select>
                            </div>
                    </span>
                </div>

                <div class="col-sm-3">
                    <span>
                            <div class="form-group">
                              <select class="form-control" name="warehouse_status">
                                <option value="0">Chọn trạng thái</option>
                                <option value="IN">Nhập</option>
                                <option value="OUT">Xuất</option>
                              </select>
                            </div>
                    </span>
                </div>

                <div>
                        <span>
                            <button  type="submit" class="btn btn-primary"
                             style="padding: 0px !important;"
                            >Tìm Kiếm</button>
                            </span>
                </div>

            </p>
        </div>
        <p>
            <span>Tổng sản lượng vận chuyển <strong>{{ $total_package }} kg</strong></span>
        </p>
        <p>
            <span>Tổng phí mua hàng ( 1% ) <strong>{{ $total_buying_fee }} VND</strong></span>
        </p>
        <p>
            <span>Tổng tiền vận chuyển nội địa Trung Quốc <strong>{{$total_domictic_shipping_fee}} VND</strong> </span>
        </p>

        <p>
            <span>Tổng tiền vận chuyển Quốc tế (Trung - Việt) <strong>{{$total_domictic_shipping_fee}} VND</strong> </span>
        </p>
        <p>
            <span>Tổng tiền vận chuyển Nội địa việt nam <strong>{{$total_domictic_shipping_fee}} VND</strong> </span>
        </p>


   {{-- <table class="table">
        <thead class="thead-default">
        <tr>
            <th>Khách Hàng</th>
            <th>Mã Kiện</th>
            <th>Mã đơn</th>
            <th>Cân nặng (kg)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <td>
                    {{ \App\Util::getUserName($item->buyer_id) }}

                </td>
                <td> {{ $item->logistic_package_barcode }} </td>
                <td>
                    {{ \App\Order::findOneByIdOrCode($item->order_id)->code }}
                </td>
                <td>
                    {{ \App\Util::getWeightFee($item) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>--}}
    </form>
@endsection

@section('js_bottom')
    @parent
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
            $( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>

@endsection

@section('css_bottom')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
@endsection