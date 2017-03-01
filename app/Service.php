<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service extends Model
{

    protected $table = 'services';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_IN_ACTIVE = 'IN_ACTIVE';

    const TYPE_BUYING = 'BUYING';
    const TYPE_CHECKING = 'CHECKING';
    const TYPE_SHIPPING_CHINA_VIETNAM = 'SHIPPING_CHINA_VIETNAM';
    const TYPE_HIGH_VALUE = 'HIGH_VALUE';
    const TYPE_FRAGILE = 'FRAGILE';
    const TYPE_WOOD_CRATING = 'WOOD_CRATING';

    public static $_serviceNaming = [
        self::TYPE_BUYING => 'Mua Hàng',
        self::TYPE_CHECKING => 'Kiểm Hàng',
        self::TYPE_SHIPPING_CHINA_VIETNAM => 'VCT Quốc Tế',
        self::TYPE_FRAGILE => 'Dễ Vỡ',
        self::TYPE_WOOD_CRATING => 'Đóng Gỗ',
        self::TYPE_HIGH_VALUE => 'Giá Trị Cao',
    ];

    public static function getServiceName($code) {
        return (isset(self::$_serviceNaming[$code]))? self::$_serviceNaming[$code] : 'Khác';
    }
}
