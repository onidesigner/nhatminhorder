@extends('layouts.app')

@section('page_title')
@section('page_title')
    {{$page_title}}
@endsection
@section('content')
    <span>Tổng sản lượng vận chuyển {{ $total_package }} kg</span>

    <table class="table">
        <thead class="thead-default">
        <tr>
            <th>#</th>
            <th>Khách Hàng</th>
            <th>Mã Kiện</th>
            <th>Mã đơn</th>
            <th>Cân nặng</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <th scope="row">1</th>
                <td></td>
                <td> {{ $item->logistic_package_barcode }} </td>
                <td></td>
                <td>@mdo</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection