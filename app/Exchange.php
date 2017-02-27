<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exchange extends Model
{
    protected $table = 'exchange';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DISABLED = 'DISABLED';

    public static function isStatusActive($status = null){
        return $status == self::STATUS_ACTIVE;
    }

    /**
     * @author vanhs
     * @desc Ham lay ti gia hien tai
     * @param null $apply_time
     * @return int
     */
    public static function getExchange($apply_time = null){
        $value = 0;
        if(!$apply_time):
            $apply_time = date('Y-m-d H:i:s');
        endif;

        $row = DB::table('exchange')->where('actived_time', '<=', $apply_time)
            ->where('deadline_time', '>', $apply_time)
            ->where('status', self::STATUS_ACTIVE)
            ->first();

        if($row):
            return $row->value;
        endif;

        return $value;
    }
}
