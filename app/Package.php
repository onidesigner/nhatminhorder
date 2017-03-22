<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    const STATUS_RECEIVED_FROM_SELLER = 'RECEIVED_FROM_SELLER';
    const STATUS_TRANSPORTING = 'TRANSPORTING';
    const STATUS_WAITING_FOR_DELIVERY = 'WAITING_DELIVERY';
    const STATUS_DELIVERING = 'DELIVERING';
    const STATUS_RECEIVED = 'RECEIVED';

    public static $statusLevel = array(
        self::STATUS_RECEIVED_FROM_SELLER ,
        self::STATUS_TRANSPORTING,
        self::STATUS_WAITING_FOR_DELIVERY,
        self::STATUS_DELIVERING ,
        self::STATUS_RECEIVED,
    );

    public static $statusTitle = array(
        self::STATUS_RECEIVED_FROM_SELLER => 'NhatMinh247 nhận',//tao kien & nhap kho TQ
        self::STATUS_TRANSPORTING => 'Vận chuyển',//kien xuat kho TQ
        self::STATUS_WAITING_FOR_DELIVERY => 'Chờ giao hàng',//kien nhap kho phan phoi
        self::STATUS_DELIVERING => 'Đang giao hàng',//kien xuat kho phan phoi
        self::STATUS_RECEIVED => 'Đã giao hàng',
    );

    protected static $_endingStatus = [
        self::STATUS_RECEIVED
    ];

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
}
