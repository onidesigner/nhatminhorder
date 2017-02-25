<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartItem extends Model
{
    protected static $table_name = 'cart_items';

    /**
     * @author vanhs
     * @desc Kiem tra san pham voi cap thuoc tinh da ton tai trong gio hang hay chua?
     * @param $shop_id
     * @param $user_id
     * @param $item_id
     * @param $property
     * @return bool
     */
    public static function checkExistsCartItemWithItemAndProperty($shop_id, $user_id, $item_id, $property){
        $row = DB::table(self::$table_name)->select('id')
            ->where('property_md5', self::genPropertyMd5($item_id, $property))
            ->where('shop_id', $shop_id)
            ->where('user_id', $user_id)
            ->first();
        if($row):
            return $row;
        endif;
        return false;
    }

    /**
     * @author vanhs
     * @desc Tao ma md5 cua thuoc tinh
     * @param $item_id
     * @param $property
     * @return string
     */
    public static function genPropertyMd5($item_id, $property){
        return md5(sprintf('%s_%s', trim($item_id), trim($property)));
    }
}
