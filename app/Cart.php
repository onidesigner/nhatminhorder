<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Auth;
use App\CartItem;

class Cart extends Model
{
    protected $table_name = 'carts';

    /**
     * @author vanhs
     * @desc Kiem tra xem thong tin shop da ton tai trong gio hang hay chua, doi voi tung user
     * @param $shop_id
     * @param $user_id
     * @return bool
     */
    public static function checkExistsShopWithUser($shop_id, $user_id){
        $row = DB::table('carts')->select('id', 'shop_id')
            ->where('user_id', $user_id)
            ->where('shop_id', $shop_id)
            ->first();
        if($row):
            return $row;
        endif;
        return false;
    }

    /**
     * @author vanhs
     * @desc Them san pham vao gio hang
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public static function addCart($params){
        DB::beginTransaction();

        try {
            $params['site'] = strtolower($params['site']);
            $user_id = 1;//fixdata
            $now = date('Y-m-d H:i:s');

            //check exists shop with user
            $exist_shop_id = self::checkExistsShopWithUser($params['shop_id'], $user_id);
            if(!$exist_shop_id):
                $data_insert_cart = [
                    'user_id' => $user_id,
                    'shop_id' => $params['shop_id'],
                    'shop_name' => $params['shop_name'],
                    'shop_link' => null,
                    'avatar' => null,
                    'site' => $params['site'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $insert_id_cart = DB::table('carts')->insertGetId($data_insert_cart);
            else:
                DB::table('carts')
                    ->where(['shop_id' => $params['shop_id'], 'user_id' => $user_id])
                    ->update(['updated_at' => $now]);
                $insert_id_cart = $exist_shop_id->id;
            endif;

            $exist_cart_item_with_property = CartItem::checkExistsCartItemWithItemAndProperty(
                $params['shop_id'],
                $user_id,
                $params['item_id'],
                $params['data_value']
            );

            if(!$exist_cart_item_with_property):
                $data_insert_item = $params;
                $data_insert_item['cart_id'] = $insert_id_cart;
                $data_insert_item['user_id'] = $user_id;
                $data_insert_item['price_vnd'] = $data_insert_item['price_promotion'] * Exchange::getExchange();
                $data_insert_item['property_md5'] = CartItem::genPropertyMd5($params['item_id'], $params['data_value']);
                $data_insert_item['created_at'] = $now;
                unset($data_insert_item['version']);
                unset($data_insert_item['is_translate']);

                DB::table('cart_items')->insert($data_insert_item);
            else:
                DB::table('cart_items')
                ->where('id', $exist_cart_item_with_property->id)
                ->update([
                    'quantity' => DB::raw("quantity+{$params['quantity']}"),
                ]);
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return false;
        }

    }
}
