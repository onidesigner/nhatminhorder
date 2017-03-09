<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    protected $table = 'locations';

    const TYPE_DISTRICT = 'DISTRICT';
    const TYPE_STATE = 'STATE';

    public function getAllProvinces(){
        return $this->newQuery()->addSelect('*')
            ->where(['type' => self::TYPE_STATE, 'status' => 0])
            ->orderBy('logistic_code', 'asc')
            ->get();
    }

    public function getAllDistricts(){
        return $this->newQuery()->addSelect('*')
            ->where(['type' => self::TYPE_DISTRICT, 'status' => 0])
            ->orderBy('logistic_code', 'asc')
            ->get();
    }

    public function checkProvinceContainDistrict($province_id, $district_id){
        $total = $this->newQuery()
            ->select('id')
            ->where(['parent_id' => $province_id, 'id' => $district_id])
            ->count();
        if($total):
            return true;
        endif;

        return false;
    }
}

