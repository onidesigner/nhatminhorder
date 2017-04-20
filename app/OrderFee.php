<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderFee extends Model
{
    protected $table = 'order_fee';

    public static $fee_field_order_detail = [
        'amount_vnd' => 'Tiền hàng (1)',
        'deposit_amount_vnd' => 'Đặt cọc',
        'buying_fee_vnd' => 'Mua hàng (2)',
        'domestic_shipping_fee_vnd' => 'VC nội địa TQ (3)',
        'shipping_china_vietnam_fee_vnd' => 'VC quốc tế (4)',
        'wood_crating_vnd' => 'Đóng gỗ (5)',
        'total_fee_vnd' => 'Phí đơn hàng (6=1+2+3+4+5)',
        'customer_payment_amount_vnd' => 'Tổng thanh toán (7)',
        'refund_order_vnd' => 'Trả lại trên đơn (8)',
        'need_payment_amount_vnd' => 'Còn thiếu (9=6-8-7)',
        'refund_complaint_vnd' => 'Trả lại từ KNDV',//chua tinh
    ];

    public static function getListFee(Order $order){
        return OrderFee::where([
            'order_id' => $order->id,
            'user_id' => $order->user_id
        ])->get();
    }

    public static function calculatorAllFee(Order $order, $save = false){

    }

    /**
     *
     * @param Order $order
     * @param array $data [ 'name' => '', money => 100000 ]
     * @return bool
     */
    public static function createFee(Order $order, $data = []){
        $customer = User::find($order->user_id);

        foreach($data as $key => $value){
            $name = $value['name'];
            $money = doubleval($value['money']);
            $row = self::existFee($order, $name);
            if($row instanceof OrderFee){
                if(isset($value['update_money'])){
                    if($money > 0){
                        $raw = DB::raw("money+{$money}");
                    }else{
                        $money = abs($money);
                        $raw = DB::raw("money-{$money}");
                    }
                }else{
                    $raw = $money;
                }

                self::where([
                    'name' => $name,
                    'order_id' => $order->id
                ])->update([
                    'money' => $raw,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }else{
                $row = new OrderFee();
                $row->name = $name;
                $row->money = $money;
                $row->order_id = $order->id;
                $row->order_code = $order->code;

                $row->user_id = $customer->id;
                $row->user_code = $customer->code;
                $row->save();
            }
        }

        return true;
    }

    public static function existFee(Order $order, $name){
        $row = OrderFee::where([
            'name' => $name,
            'order_id' => $order->id,
        ])->first();
        if($row instanceof self){
            return $row;
        }
        return false;
    }

    public static function getFee(Order $order, $name){
        $row = self::existFee($order, $name);
        if($row instanceof OrderFee){
            return $row->money;
        }
        return 0;
    }
}
