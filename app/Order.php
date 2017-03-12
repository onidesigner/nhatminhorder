<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\OrderItem;

class Order extends Model
{
    protected $table = 'order';

    const STATUS_DEPOSITED = 'DEPOSITED';
    const STATUS_BUYING = '';
    const STATUS_NEGOTIATING = '';
    const STATUS_NEGOTIATED = '';
    const STATUS_BOUGHT = '';
    const STATUS_SELLER_DELIVERY = '';
    const STATUS_RECEIVED_FROM_SELLER = '';
    const STATUS_TRANSPORTING = '';
    const STATUS_CHECKING = '';
    const STATUS_CHECKED = '';
    const STATUS_WAITING_FOR_DELIVERY = '';
    const STATUS_CUSTOMER_CONFIRM_DELIVERY = '';
    const STATUS_DELIVERING = '';
    const STATUS_RECEIVED = '';
    const STATUS_OUT_OF_STOCK = '';
    const STATUS_CANCELLED = '';
    const STATUS_LOST = '';

    public static $statusTitle = array(
        self::STATUS_DEPOSITED => 'Đã đặt cọc',
        self::STATUS_BUYING => 'Đang mua hàng',
        self::STATUS_NEGOTIATING => 'Đang đàm phán',
        self::STATUS_NEGOTIATED => 'Đã đàm phán',
        self::STATUS_BOUGHT => 'Đã mua hàng',
        self::STATUS_SELLER_DELIVERY => "Người bán giao",
        self::STATUS_RECEIVED_FROM_SELLER => "NhatMinh247 Nhận",
        self::STATUS_TRANSPORTING => "Vận chuyển",
        self::STATUS_CHECKING => 'Đang kiểm hàng',
        self::STATUS_CHECKED => 'Đã kiểm hàng',
        self::STATUS_WAITING_FOR_DELIVERY => "Chờ giao hàng",
        self::STATUS_CUSTOMER_CONFIRM_DELIVERY => "Yêu cầu giao",
        self::STATUS_DELIVERING => "Đang giao hàng",
        self::STATUS_RECEIVED => 'Khách nhận hàng',
        self::STATUS_OUT_OF_STOCK => 'Hết hàng',
        self::STATUS_CANCELLED => "Hủy bỏ",
        self::STATUS_LOST => 'Thất lạc'
    );

    public static $timeListOrderDetail = [
        'deposited_at' => 'Đặt cọc',
        'out_of_stock_at' => 'Hết hàng',
        'cancelled_at' => 'Hủy đơn'
    ];

    public static function getFavicon($site){
        if(empty($site)) return null;
        $site = strtolower($site);
        return asset('images/favicon_site_china/' . $site . '.png');
    }

    public static function isAfterStatus($status, $include_current){
        return true;
    }

    public static function isBeforeStatus($status, $include_current){

    }

    public static function getStatusTitle($code){
        if(empty($code)){
            return null;
        }

        return empty(self::$statusTitle[$code]) ? '' : self::$statusTitle[$code];
    }

    /**
     * @desc Lay danh sach san pham nam trong don hang
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getItemInOrder()
    {
        return OrderItem::where([
            'order_id' => $this->id
        ])->get();
    }

    /**
     * @desc Lay ra tong so san pham loai thuong
     * @return int
     */
    public function getItemNormalQuantity()
    {
        $total = 0;
        $items = $this->getItemInOrder();

        if (!empty($items)) {
            foreach ($items as $item) {
                if (!$item->checkItemAssess($this->exchange)) {
                    $total += $item->check_quantity;
                }
            }
        }

        return $total;
    }

    /**
     * @desc Lay ra tong so san pham la phu kien
     * @return int
     */
    public function getItemAssessQuantity()
    {
        $total = 0;
        $items = $this->getItemInOrder();

        if (!empty($items)) {
            foreach ($items as $item) {
                if ($item->checkItemAssess($this->exchange)) {
                    $total += $item->check_quantity;
                }
            }
        }

        return $total;
    }

    public function has_origin_bill($original_bill){
        return OrderOriginalBill::where([
            'original_bill' => $original_bill,
            'order_id' => $this->id
        ])->count();
    }

    public function has_freight_bill($freight_bill){
        return OrderFreightBill::where([
            'order_id' => $this->id,
            'freight_bill' => $freight_bill
        ])->count();
    }

    public function original_bill(){
        return $this->hasMany('App\OrderOriginalBill', 'order_id');
    }

    public function freight_bill(){
        return $this->hasMany('App\OrderFreightBill', 'order_id');
    }

    public function item(){
        return $this->hasMany('App\OrderItem', 'order_id');
    }

    public function service(){
        return $this->hasMany('App\OrderService', 'order_id');
    }

    public function package(){
        return $this->hasMany('App\Package', 'order_id');
    }

    /**
     * @author vanhs
     * @desc Tao ma don hang
     * @param $user
     * @return string
     * @throws \Exception
     */
    public static function createCode($user)
    {
        if(!$user):
            throw new \Exception('User not found!');
        endif;
        $user_code = $user->code;

        //fail safe: if user deposit without user code, create one
        if (!$user_code) {
            $user_code = User::genCustomerCode();
            User::where(['id' => $user->id])->update([
                'updated_at' => date('Y-m-d H:i:s'),
                'code' => $user_code
            ]);
        }

        //remove shipping address province's code
        $current_order_no = self::select('id')->where([

            [ 'user_id', '=', $user->id ],
            [ 'created_at', '>=', date('Y-m-d') . ' 00:00:00' ],
            [ 'created_at', '<=', date('Y-m-d') . ' 23:59:59' ]

        ])->count();

        $serial_part = str_pad($current_order_no + 1, 1, '0', STR_PAD_LEFT);
        $time_part = date('d');

        $working_month_sequence = Util::getWorkingMonthSequence();

        return "{$user_code}_{$working_month_sequence}{$time_part}{$serial_part}";
    }
}
