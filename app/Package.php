<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    const STATUS_RECEIVED_FROM_SELLER = 'RECEIVED_FROM_SELLER';
    const STATUS_TRANSPORTING = 'TRANSPORTING';
    const STATUS_WAITING_FOR_DELIVERY = 'WAITING_DELIVERY';
    const STATUS_CUSTOMER_CONFIRM_DELIVERY = 'CONFIRM_DELIVERY';
    const STATUS_DELIVERING = 'DELIVERING';
    const STATUS_RECEIVED = 'RECEIVED';

    public static $statusLevel = array(
        self::STATUS_RECEIVED_FROM_SELLER ,
        self::STATUS_TRANSPORTING,
        self::STATUS_WAITING_FOR_DELIVERY,
        self::STATUS_CUSTOMER_CONFIRM_DELIVERY ,
        self::STATUS_DELIVERING ,
        self::STATUS_RECEIVED,
    );

    public static $statusTitle = array(
        self::STATUS_RECEIVED_FROM_SELLER => 'NhatMinh247 nhận',//tao kien & nhap kho TQ
        self::STATUS_TRANSPORTING => 'Vận chuyển',//kien xuat kho TQ
        self::STATUS_WAITING_FOR_DELIVERY => 'Chờ giao hàng',//kien nhap kho phan phoi
        self::STATUS_CUSTOMER_CONFIRM_DELIVERY => 'Yêu cầu giao',//khach an yeu cau giao don
        self::STATUS_DELIVERING => 'Đang giao hàng',//kien xuat kho phan phoi
        self::STATUS_RECEIVED => 'Khách nhận hàng',
    );

    protected static $_endingStatus = [
        self::STATUS_RECEIVED
    ];

    public function getWeightCalculator(){
        return $this->weight_manual > $this->weight_equivalent
            ? $this->weight_manual : $this->weight_equivalent;
    }
}
