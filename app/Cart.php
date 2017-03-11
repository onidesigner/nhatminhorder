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
    public static $deposit_percent = 50;

    protected $table_name = 'carts';

    public static function getDepositPercent($apply_time = null){
        return self::$deposit_percent;
    }

    public static function getDepositAmount($deposit_percent, $total_amount){
        $deposit_amount = $total_amount * $deposit_percent / 100;
        return $deposit_amount;
    }

    public static function insertOrderServices($insert_id_order, $cart_services = null){
        if($cart_services):
            $service_data_insert = [];
            $cart_services = explode('|', $cart_services);
            foreach($cart_services as $cart_service):
                $service_data_insert[] = [
                    'order_id' => $insert_id_order,
                    'service_code' => $cart_service,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            endforeach;

            if(count($service_data_insert)):
                OrderService::insert($service_data_insert);
            endif;
        endif;
    }

    public static function insertOrderComment($comment, $user_id, $order_id){
        if($comment):
            Comment::insert([
                'user_id' => $user_id,
                'object_id' => $order_id,
                'object_type' => Comment::TYPE_OBJECT_ORDER,
                'scope' => Comment::TYPE_EXTERNAL,
                'message' => $user_id,
                'type_context' => Comment::TYPE_CONTEXT_CHAT,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        endif;
    }

    public static function removeCartAfterDepositOrder($user_id, $shop_id){
        Cart::where(['user_id' => $user_id])
            ->whereIn('shop_id', $shop_id)
            ->delete();

        CartItem::where(['user_id' => $user_id])
            ->whereIn('shop_id', $shop_id)
            ->delete();
    }

    /**
     * @vanhs
     * @desc Xu ly dat coc don hang
     * @param User $user
     * @param $destination_warehouse
     * @param $shop_id
     * @param $address_id
     * @param $exchange_rate
     * @param $deposit_percent
     * @param $deposit_amount
     * @return bool|array
     */
    public static function depositOrder(User $user, $destination_warehouse,
                                        $shop_id,
                                        $address_id, $exchange_rate,
                                        $deposit_percent, $deposit_amount){
        DB::beginTransaction();

        try{
            $data_order_success = [];

            $user_id = $user->id;

            $shops = Cart::where([
                'user_id' => $user_id
            ])
            ->whereIn('shop_id', $shop_id)
            ->get();

            if(!empty($shops)):

                foreach($shops as $shop):

                    if(!$shop || !$shop instanceof Cart):
                        continue;
                    endif;

                    $cart_comment = $shop->comment;
                    $cart_items = Cart::find($shop->id)->cart_item()->where(['user_id' => $user_id])->get();

                    $order_code = Order::createCode($user);

                    $amount = 0;
                    $total_order_quantity = 0;

                    $insert_id_order = Order::insertGetId([
                        'code' => $order_code,
                        'avatar' => $shop->avatar ? urldecode($shop->avatar) : '',
                        'status' => Order::STATUS_DEPOSITED,
                        'site' => strtolower($shop->site),
                        'exchange_rate' => $exchange_rate,
                        'user_id' => $user_id,
                        'user_address_id' => $address_id,
                        'destination_warehouse' => $destination_warehouse,
                        'deposit_percent' => $deposit_percent,
                        'deposit_amount' => $deposit_amount,
                        'deposited_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    $order_item_insert = [];
                    if($cart_items):
                        foreach($cart_items as $cart_item):
                            if(!$cart_item || !$cart_item instanceof CartItem):
                                continue;
                            endif;

                            $order_item_insert[] = [
                                'item_id' => $cart_item->item_id,
                                'user_id' => $cart_item->user_id,
                                'order_id' => $insert_id_order,
                                'title' => $cart_item->title_origin,
                                'title_translated' => $cart_item->title_translated,
                                'link' => $cart_item->link_origin,
                                'image' => $cart_item->image_model ? urldecode($cart_item->image_model) : '',
                                'property' => $cart_item->property,
                                'property_translated' => $cart_item->property_translated,
                                'location_sale' => $cart_item->location_sale,
                                'price' => $cart_item->price_origin,
                                'price_promotion' => $cart_item->price_promotion,
                                'price_table' => $cart_item->price_table,
                                'order_quantity' => $cart_item->quantity,
                                'step' => $cart_item->step,
                                'require_min' => $cart_item->require_min,
                                'stock' => $cart_item->stock,
                                'site' => strtolower($cart_item->site),
                                'created_at' => date('Y-m-d H:i:s'),
                            ];

                            $total_order_quantity += $cart_item->quantity;
                            $amount += $cart_item->quantity * $cart_item->getPriceCalculator();
                        endforeach;
                    endif;

                    if(count($order_item_insert)):
                        OrderItem::insert($order_item_insert);
                    endif;

                    Order::where([
                        'id' => $insert_id_order
                    ])->update([
                        'amount' => $amount,
                        'total_order_quantity' => $total_order_quantity,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    self::insertOrderServices($insert_id_order, $shop->services);

                    self::insertOrderComment($cart_comment, $user_id, $insert_id_order);

                    #region Tao giao dich & tru tien tk khach
                    $deposit_amount = 0 - $deposit_amount;
                    $transaction_note = sprintf('Dat coc don hang %s', $order_code);

                    User::where(['id' => $user_id])->update([
                        'account_balance' => DB::raw("account_balance+$deposit_amount")
                    ]);

                    $order = Order::find($insert_id_order);
                    $user_after = User::find($user_id);

                    UserTransaction::insert([
                        'user_id' => $user_id,
                        'state' => UserTransaction::STATE_COMPLETED,
                        'transaction_code' => UserTransaction::generateTransactionCode(),
                        'transaction_type' => UserTransaction::TRANSACTION_TYPE_ORDER_DEPOSIT,
                        'ending_balance' => $user_after->account_balance,
                        'created_by' => $user_id,
                        'object_id' => $insert_id_order,
                        'object_type' => UserTransaction::OBJECT_TYPE_ORDER,
                        'amount' => $deposit_amount,
                        'transaction_detail' => json_encode($order),
                        'transaction_note' => $transaction_note,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    #endregion

                    $data_order_success[] = $order->id;
                endforeach;

            endif;

            self::removeCartAfterDepositOrder($user_id, $shop_id);

            DB::commit();
            return $data_order_success;
        }catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    /**
     * @author vanhs
     * @desc Kiem tra xem thong tin shop da ton tai trong gio hang hay chua, doi voi tung user
     * @param $shop_id
     * @param $user_id
     * @return bool
     */
    public static function checkExistsShopWithUser($shop_id, $user_id){

        $row = self::select('id', 'shop_id')
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
            $user_id = Auth::user()->id;
            $insert_id_cart = null;

            //check exists shop with user
            $exist_shop_id = self::checkExistsShopWithUser($params['shop_id'], $user_id);
            if(!$exist_shop_id):

                $data_insert_cart = [
                    'user_id' => $user_id,
                    'shop_id' => $params['shop_id'],
                    'shop_name' => $params['shop_name'],
                    'shop_link' => null,
                    'avatar' => $params['image_model'],
                    'site' => $params['site'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $insert_id_cart = self::insertGetId($data_insert_cart);
            else:
                self::where([
                        'shop_id' => $params['shop_id'],
                        'user_id' => $user_id
                    ])
                    ->update(['updated_at' => date('Y-m-d H:i:s')]);
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
                $data_insert_item['user_id'] = $user_id;
                $data_insert_item['cart_id'] = $insert_id_cart;
                $data_insert_item['property_md5'] = CartItem::genPropertyMd5($params['item_id'], $params['data_value']);
                $data_insert_item['created_at'] = date('Y-m-d H:i:s');

                unset($data_insert_item['brand']);
                unset($data_insert_item['category_name']);
                unset($data_insert_item['category_id']);

                CartItem::insert($data_insert_item);
            else:
                CartItem::where([
                    'id', $exist_cart_item_with_property->id
                ])
                ->update([
                    'quantity' => DB::raw("quantity+{$params['quantity']}"),
                ]);
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
            DB::rollback();
            return false;
        }

    }

    public function cart_item(){
        return $this->hasMany('App\CartItem', 'cart_id');
    }
}
