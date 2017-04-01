<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    const STATUS_INIT = 'INIT';
    const STATUS_RECEIVED_FROM_SELLER = 'RECEIVED_FROM_SELLER';
    const STATUS_TRANSPORTING = 'TRANSPORTING';
    const STATUS_WAITING_FOR_DELIVERY = 'WAITING_DELIVERY';
    const STATUS_DELIVERING = 'DELIVERING';
    const STATUS_RECEIVED = 'RECEIVED';

    const WAREHOUSE_STATUS_IN = 'IN';
    const WAREHOUSE_STATUS_OUT = 'OUT';

    public static $statusLevel = array(
        self::STATUS_INIT ,
        self::STATUS_RECEIVED_FROM_SELLER ,
        self::STATUS_TRANSPORTING,
        self::STATUS_WAITING_FOR_DELIVERY,
        self::STATUS_DELIVERING ,
        self::STATUS_RECEIVED,
    );

    public static $statusTitle = array(
        self::STATUS_INIT => 'Khởi tạo',
        self::STATUS_RECEIVED_FROM_SELLER => 'NhatMinh247 nhận',//tao kien & nhap kho TQ
        self::STATUS_TRANSPORTING => 'Vận chuyển',//kien xuat kho TQ
        self::STATUS_WAITING_FOR_DELIVERY => 'Chờ giao hàng',//kien nhap kho phan phoi
        self::STATUS_DELIVERING => 'Đang giao hàng',//kien xuat kho phan phoi
        self::STATUS_RECEIVED => 'Đã giao hàng',
    );

    public static $warehouseStatusName = [
        self::WAREHOUSE_STATUS_IN => 'Trong kho',
        self::WAREHOUSE_STATUS_OUT => 'Xuất kho',
    ];

    protected static $_endingStatus = [
        self::STATUS_RECEIVED
    ];

    public static function getWarehouseStatusName($warehouse_status){
        if(!empty(self::$warehouseStatusName[$warehouse_status])){
            return self::$warehouseStatusName[$warehouse_status];
        }
        return null;
    }

    public static function getStatusTitle($status){
        if(!empty(self::$statusTitle[$status])){
            return self::$statusTitle[$status];
        }
        return null;
    }

    public function getWeightCalculator(){
        return $this->weight_manual > $this->weight_equivalent
            ? $this->weight_manual : $this->weight_equivalent;
    }

    /**
     * @author vanhs
     * @desc Ham kiem tra ma kien da ton tai tren he thong hay chua?
     * @param $code
     * @return mixed
     */
    public static function checkExistsCode($code){
        return Package::select('id')->where([
            'code' => $code
        ])->count();
    }

    /**
     * @author vanhs
     * @desc Ham tao ma kien
     * @param Order $order
     * @return string
     */
    public static function genPackageCode(Order $order){
        if(!$order || !$order instanceof Order){
            return '';
        }

        $order_code = $order->code;

        $total_packages_with_order = Package::select('id')->where([
            'order_id' => $order->id,
        ])->count();
        $total_packages_with_order++;

        $package_code = sprintf('%s_%s', $order_code, $total_packages_with_order);

        while(self::checkExistsCode($package_code)){
            $total_packages_with_order++;
            $package_code = sprintf('%s_%s', $order_code, $total_packages_with_order);
        }

        return $package_code;
    }

    /**
     * @author vanhs
     * @desc Ham lay ra trang thai kien dua vao hanh dong + kho hien tai
     * @param null $action
     * @param null $warehouse_code
     * @return string
     */
    public static function genStatusWithActionAndWarehouseCode($action = null, $warehouse_code = null){
        $warehouse = WareHouse::retrieveByCode($warehouse_code);
        if(!$warehouse || !$warehouse instanceof WareHouse){
            return '';
        }
        if($action == 'IN'){
            if($warehouse->type == WareHouse::TYPE_RECEIVE){
                return self::STATUS_RECEIVED_FROM_SELLER;
            }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){
                return self::STATUS_WAITING_FOR_DELIVERY;
            }
        }else if($action == 'OUT'){
            if($warehouse->type == WareHouse::TYPE_RECEIVE){
                return self::STATUS_TRANSPORTING;
            }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){
                return self::STATUS_DELIVERING;
            }
        }
        return '';
    }
}
