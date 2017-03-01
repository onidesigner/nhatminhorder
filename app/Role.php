<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    const STATE_ACTIVE = 'ACTIVE';
    const STATE_INACTIVE = 'INACTIVE';

    public static  $stateList = [
        self::STATE_ACTIVE => 'Kich hoat',
        self::STATE_INACTIVE => 'Ngung kich hoat'
    ];

    public static function getStateName($name){
        if(!empty(self::$stateList[$name])):
            return     self::$stateList[$name];
        endif;

        return '';
    }
}
