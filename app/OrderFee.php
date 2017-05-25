<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderFee extends Model
{
    protected $table = 'order_fee';

    public static $fee_field_order_detail = [
        'AMOUNT_VND' => 'Tiền hàng (1)',
        'DEPOSIT_AMOUNT_VND' => 'Đặt cọc',
        'BUYING_FEE_VND' => 'Mua hàng (2)',
        'DOMESTIC_SHIPPING_FEE_VND' => 'VC nội địa TQ (3)',
        'SHIPPING_CHINA_VIETNAM_FEE_VND' => 'VC quốc tế (4)',
        'WOOD_CRATING_VND' => 'Đóng gỗ (5)',
        'TOTAL_FEE_VND' => 'Phí đơn hàng (6=1+2+3+4+5)',
        'CUSTOMER_PAYMENT_AMOUNT_VND' => 'Đã thanh toán (7)',
        'NEED_PAYMENT_AMOUNT_VND' => 'Còn thiếu (8=6-7)',

        'WITHDREW_ORDER_VND' => 'Truy thu trên đơn',
        'REFUND_ORDER_VND' => 'Trả lại trên đơn',
        'REFUND_COMPLAINT_VND' => 'Trả lại từ KNDV',//chua tinh
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

    public function save(array $options = [])
    {
        //before save code
        $this->name = strtoupper($this->name);

        $saved = parent::save($options); // TODO: Change the autogenerated stub
        //end save code
        return $saved;
    }
}
