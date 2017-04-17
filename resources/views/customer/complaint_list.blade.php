@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Khiếu nại</th>
            <th>Mã đơn</th>
            <th>Trạng thái</th>
            <th>Thời gian</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($data))
            <?php $i = 1; ?>
            @foreach($data as $complaint)
                <tr>
                    <th scope="row">{{ $i++ }}</th>
                    <td>{{ $complaint->title }}</td>
                    <td>{{ App\Complaints::getOrderCode($complaint->order_id)  }}</td>
                    <td>{{ App\Complaints::$alias_array[$complaint->status] }}</td>
                    <td>{{ $complaint->created_time }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    @if(!empty($data))
        {{ $data->links() }}
    @else
        <h3 align="center">Bạn chưa có khiếu nại !</h3>
    @endif



@endsection



