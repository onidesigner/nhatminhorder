<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    protected $table = 'warehouse';

    const TYPE_DISTRIBUTION = 'DISTRIBUTION';
    const WAREHOUSE_CNGZ = 'CNGZ';
    const WAREHOUSE_CNPX = 'CNPX';
    const WAREHOUSE_ALIAS_HN = 'HN';
    const WAREHOUSE_ALIAS_SG = 'SG';
//    const WAREHOUSE_HN_CITY_ID = 52;
//    const WAREHOUSE_SG_CITY_ID = 84;

    public static $receive_warehouse = array(
        self::WAREHOUSE_CNGZ => "Quảng Châu - CNGZ",
        self::WAREHOUSE_CNPX => "Bằng Tường - CNPX"
    );

    /**
     * @desc Lấy tên alias của kho dựa theo mã kho | Vd: VNHN, K-HN có alias là HN; VNSG, K-SG có alias là SG...
     * @param $code
     * @return mixed|string
     */
    public function getAliasByCode($code) {
        $alias = "";
        $result = $this->newQuery()->where([
            'code' => $code
        ])->first();

        if($result):
            $alias = $result->alias;
        endif;

        return $alias;
    }
}
