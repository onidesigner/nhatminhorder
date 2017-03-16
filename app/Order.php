<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\OrderItem;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'order';

    const STATUS_DEPOSITED = 'DEPOSITED';
    const STATUS_BOUGHT = 'BOUGHT';
    const STATUS_SELLER_DELIVERY = '';
    const STATUS_RECEIVED_FROM_SELLER = '';
    const STATUS_TRANSPORTING = '';
//    const STATUS_CHECKING = '';
//    const STATUS_CHECKED = '';
//    const STATUS_WAITING_FOR_DELIVERY = '';
//    const STATUS_CUSTOMER_CONFIRM_DELIVERY = '';
//    const STATUS_DELIVERING = '';
    const STATUS_RECEIVED = '';
    const STATUS_OUT_OF_STOCK = '';
    const STATUS_CANCELLED = 'CANCELLED';

    public static $statusTitle = array(
        self::STATUS_DEPOSITED => 'Đã đặt cọc',
        self::STATUS_BOUGHT => 'Đã mua hàng',
        self::STATUS_SELLER_DELIVERY => "Người bán giao",
        self::STATUS_RECEIVED_FROM_SELLER => "NhatMinh247 Nhận",
        self::STATUS_TRANSPORTING => "Vận chuyển",
//        self::STATUS_CHECKING => 'Đang kiểm hàng',
//        self::STATUS_CHECKED => 'Đã kiểm hàng',
//        self::STATUS_WAITING_FOR_DELIVERY => "Chờ giao hàng",
//        self::STATUS_CUSTOMER_CONFIRM_DELIVERY => "Yêu cầu giao",
//        self::STATUS_DELIVERING => "Đang giao hàng",
        self::STATUS_RECEIVED => 'Khách nhận hàng',
        self::STATUS_OUT_OF_STOCK => 'Hết hàng',
        self::STATUS_CANCELLED => "Đã hủy",
    );

    public static $fieldTime = [
        self::STATUS_DEPOSITED => 'deposited_at',
        self::STATUS_BOUGHT => 'bought_at',
        self::STATUS_SELLER_DELIVERY => "seller_delivery_at",
        self::STATUS_RECEIVED_FROM_SELLER => "received_from_seller_at",
        self::STATUS_TRANSPORTING => "transporting_at",
        self::STATUS_RECEIVED => 'received_at',
        self::STATUS_OUT_OF_STOCK => 'out_of_stock_at',
        self::STATUS_CANCELLED => 'cancelled_at',
    ];

    public static $timeListOrderDetail = [
        'deposited_at' => 'Đặt cọc',
        'bought_at' => 'Đã mua',
        'received_at' => 'Khách nhận hàng',
        'out_of_stock_at' => 'Hết hàng',
        'cancelled_at' => 'Hủy đơn'
    ];

    public static $statusLevel = array(
        self::STATUS_DEPOSITED,
        self::STATUS_BOUGHT,
        self::STATUS_SELLER_DELIVERY,
        self::STATUS_RECEIVED_FROM_SELLER,
        self::STATUS_TRANSPORTING,



        self::STATUS_RECEIVED,
        self::STATUS_OUT_OF_STOCK,
        self::STATUS_CANCELLED,
    );

    protected static $_endingStatus = [
        self::STATUS_RECEIVED,
        self::STATUS_CANCELLED,
        self::STATUS_OUT_OF_STOCK,
    ];

    public static function getFieldTimeByStatus($field_time){
        return self::$fieldTime[$field_time] ? self::$fieldTime[$field_time] : null;
    }

    /**
     * Is before status
     * @param $status
     * @param bool $includedCurrentStatus
     * @return bool
     */
    public function isBeforeStatus($status, $includedCurrentStatus = false)
    {
        if ($includedCurrentStatus && $this->status == $status) {
            return true;
        }

        $before_status = Order::getBeforeStatus($status);
        if (empty($before_status)) {
            return false;
        }
        if (in_array($this->status, $before_status)) {
            return true;
        }
        return false;
    }

    /**
     * Is After Status
     * @param $status
     * @param bool $includedCurrentStatus
     * @return bool
     */
    public function isAfterStatus($status, $includedCurrentStatus = false)
    {
        if ($includedCurrentStatus && $this->status == $status) {
            return true;
        }
        $after_status = Order::getAfterStatus($status);

        if (empty($after_status)) {
            return false;
        }
        if (in_array($this->status, $after_status)) {
            return true;
        }
        return false;
    }

    /**
     * @ Ham kiem tra xem don hang co phai la trang thai cuoi cung hay chua?
     * @return bool
     */
    public function isEndingStatus(){
        if( in_array($this->status, self::$_endingStatus) ){
            return true;
        }
        return false;
    }

    /**
     * get Left Status
     * @param $status
     * @return array Status
     */
    public static function getBeforeStatus($status){
        if($status == ''){
            return array();
        }

        $status_array = array();

        $key = array_search($status, Order::$statusLevel);

        for ($i = $key-1 ; $i >= 0 ;$i--) {
            $status_array[] = Order::$statusLevel[$i];
        }

        return $status_array;
    }

    public function changeStatus($status){

        $this->status = $status;

        $field_time = self::getFieldTimeByStatus($status);
        if($field_time){
            $this->$field_time = date('Y-m-d H:i:s');
        }

        $this->save();
    }

    /**
     * get Right Status
     * @param $status
     * @return array Status
     */
    public static function getAfterStatus($status){
        if($status == ''){
            return array();
        }

        $status_array = array();

        $key = array_search($status,Order::$statusLevel);

        for ($i = $key+1 ; $i < count(Order::$statusLevel) ;$i++) {
            $status_array[] = Order::$statusLevel[$i];
        }

        return $status_array;
    }

    public static function getFavicon($site){
        if(empty($site)) return null;
        $site = strtolower($site);
        return asset('images/favicon_site_china/' . $site . '.png');
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

    public function has_original_bill($original_bill){
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

    public function exist_freight_bill(){
        return OrderFreightBill::where([
            'order_id' => $this->id
        ])->count() > 0;
    }

    public function create_freight_bill($user_id, $freight_bill){
        $order_freight_bill = new OrderFreightBill();
        $order_freight_bill->user_id = $user_id;
        $order_freight_bill->order_id = $this->id;
        $order_freight_bill->freight_bill = $freight_bill;
        return $order_freight_bill->save();
    }

    public function create_original_bill($user_id, $original_bill){
        $order_original_bill = new OrderOriginalBill();
        $order_original_bill->user_id = $user_id;
        $order_original_bill->order_id = $this->id;
        $order_original_bill->original_bill = $original_bill;
        return $order_original_bill->save();
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

    public function total_order_quantity(){
        return DB::table('order_item')
            ->select(DB::raw('SUM(order_quantity) as total_quantity'))
            ->where([
                'order_id' => $this->id
            ])
            ->first()->total_quantity;
    }

    public function total_checking_quantity(){
        return DB::table('order_item')
            ->select(DB::raw('SUM(checking_quantity) as total_quantity'))
            ->where([
                'order_id' => $this->id
            ])
            ->first()->total_quantity;
    }

    public function total_receiver_quantity(){
        return DB::table('order_item')
            ->select(DB::raw('SUM(receiver_quantity) as total_quantity'))
            ->where([
                'order_id' => $this->id
            ])
            ->first()->total_quantity;
    }

    public function amount($vnd = false){
        $amount = 0;
        $items = $this->item()->get();
        if($items){
            foreach($items as $item){
                if(!$item || !$item instanceof OrderItem){
                    continue;
                }

                $amount_item = $item->price * $item->order_quantity;
                if($vnd){
                    $amount_item = $item->price * $item->order_quantity * $this->exchange_rate;
                }
                $amount += $amount_item;
            }
        }
        return $amount;
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
