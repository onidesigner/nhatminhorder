@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection
@section('content')
    <p>
        <span>Tổng sản lượng vận chuyển <strong>{{ $total_package }} kg</strong></span>
    </p>

    <table class="table">
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
    </table>

@endsection

@section('js_bottom')
    @parent

@endsection