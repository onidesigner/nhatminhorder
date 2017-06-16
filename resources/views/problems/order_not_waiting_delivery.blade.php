<?php
//Đơn quá ? ngày mà chưa về kho phân phối tại việt nam, kể từ sau khi băt đầu vận chuyển từ trung quốc về vn
$l_time = strtotime("-{$long_time} day", strtotime(date('Y-m-d H:i:s')));
$l_time = date('Y-m-d H:i:s', $l_time);

$orders = \Illuminate\Support\Facades\DB::select("
            select * from `order` where `status` = '".App\Order::STATUS_TRANSPORTING."' and `transporting_at` <= '".$l_time."'
        ");
if(count($orders)){
?>

<table class="table">
    <thead>
    <tr>
        <th>TT</th>
        <th>Đơn hàng</th>
        <th>Vận chuyển về VN lúc</th>
        <th>Khách</th>
        <th>NV Mua hàng</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($orders as $idx => $order){
    $customer = App\User::find($order->user_id);
    $crane_buying = App\User::find($order->paid_staff_id);
    ?>
    <tr>
        <td>{{$idx+1}}</td>
        <td>
            <a href="{{ url('order/detail', $order->code)}}">{{$order->code}}</a>
            <small>{{ App\Order::getStatusTitle($order->status) }}</small>
        </td>
        <td>
            {{App\Util::formatDate($order->transporting_at)}}
        </td>
        <td>
            <a href="{{url('user/detail', $customer->id)}}">{{$customer->name}}</a> <small>{{$customer->code}}</small>
        </td>
        <td>
            <a href="{{url('user/detail', $crane_buying->id)}}">{{$crane_buying->name}}</a> <small>{{$crane_buying->code}}</small>
        </td>
    </tr>

    <?php
    }
    ?>
    </tbody>
</table>

<?php
}
?>